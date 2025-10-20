<?php
require_once __DIR__ . '/../../includes/config.php';
date_default_timezone_set('Asia/Jakarta');
$id = isset($_POST['id']) ? $_POST['id'] : null;
$issue_id = isset($_POST['issue_id']) ? $_POST['issue_id'] : null;
$job_type = isset($_POST['job_type']) ? $_POST['job_type'] : null;
if ($id && $job_type) {
    $_SESSION['menu'] = 'schedule';


    try {
        $status = ['Pending', 'Rescheduled', 'Cancelled', 'Done'];
        $requestTables = [
            "Instalasi"    => ["table" => "request_ikr", "id" => "rikr_id"],
            "Maintenance" => ["table" => "request_maintenance", "id" => "rm_id"],
            "Dismantle"  => ["table" => "request_dismantle", "id" => "rd_id"],
        ];
        function formatDate($datetime, $type = 'date')
        {
            $dt = new DateTime($datetime);
            switch ($type) {
                case 'date':
                    return $dt->format('Y-m-d');
                case 'full':
                    return $dt->format('d F Y');
                case 'time':
                    return $dt->format('H:i');
            }
        }
        if (!isset($requestTables[$job_type])) {
            header("Location: " . BASE_URL . "pages/schedule/");
            exit;
        }

        if (isset($requestTables[$job_type])) {
            $table = $requestTables[$job_type]['table'];
            $idCol = $requestTables[$job_type]['id'];

            $sql = "SELECT s.*, s.catatan as deskripsi, s.status as status_schedule, q.*, r.*, c.* 
            FROM schedules s
            JOIN queue_scheduling q ON s.queue_key = q.queue_key
            JOIN $table r ON q.request_id = r.$idCol
            JOIN customers c ON r.netpay_key = c.netpay_key
            WHERE s.schedule_key = :schedule_key";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':schedule_key', $id, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            if (!$row) {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Data tidak ditemukan',
                    'text' => 'Schedule dengan ID ' . htmlspecialchars($id) . ' tidak tersedia.',
                    'button' => 'Kembali',
                    'style' => 'danger'
                ];
                header("Location: " . BASE_URL . "pages/schedule/");
                exit;
            }
        }
        $tanggalSchedule   = isset($row['date']) ? formatDate($row['date'], 'date') : '';
        $tanggalPemasangan = isset($row['jadwal_pemasangan']) ? formatDate($row['jadwal_pemasangan'], 'full') : '';
        $jamPemasangan     = isset($row['jadwal_pemasangan']) ? formatDate($row['jadwal_pemasangan'], 'time') : '';
        $cr                = formatDate($row['created_at'], 'full');
        $statusClasses = [
            'Pending' => "info",
            'Accepted' => "success",
            'Rejected' => "danger",
        ];
        $sql = "SELECT * FROM technician";
        $stmt = $pdo->query($sql);
        $technicians = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if ($issue_id) {
            $sql = "SELECT * FROM issues_report WHERE issue_id = :issue_id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':issue_id' => $issue_id]);
            $issue = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        require __DIR__ . '/../../includes/header.php';
        require __DIR__ . '/../../includes/aside.php';
        require __DIR__ . '/../../includes/navbar.php';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
?>

<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <a href="<?= BASE_URL ?>pages/schedule/" class="h4 text-dark font-weight-bold my-1 mr-5">
                        Schedule </a>

                    <!--end::Page Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                                Update Schedule </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                                <?= $row['schedule_id'] ?> </a>
                        </li>
                    </ul>
                    <!-- end::Breadcrumb -->
                </div>
                <!--end::Page Heading-->
            </div>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Subheader-->

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Row-->
            <div class="row align-items-stretch">
                <div class="col-md-7">
                    <div class="card">
                        <form action="<?= BASE_URL ?>controllers/schedules/update.php" method="post">
                            <div class="card-header">
                                <h3>Update Schedule</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Netpay ID</label>
                                    <input type="text" class="form-control" value="<?= $row['netpay_id'] ?>" disabled="disabled" required>
                                    <input type="hidden" class="form-control" name="schedule_key" value="<?= $row['schedule_key'] ?>">
                                    <input type="hidden" class="form-control" name="queue_key" value="<?= $row['queue_key'] ?>">
                                    <input type="hidden" class="form-control" name="netpay_key" value="<?= $row['netpay_key'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Teknisi</label>
                                    <select class="form-control selectpicker" id="tech_id" required name="tech_id" data-size=" 7" data-live-search="true">
                                        <option value="">Select</option>
                                        <?php foreach ($technicians as $t): ?>
                                            <?php $selected = $row['tech_id'] ==  $t['tech_id'] ? "selected" : "" ?>
                                            <option value="<?= $t['tech_id'] ?>" <?= $selected ?>><?= $t['name'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="text-right">Tanggal</label>
                                    <div>
                                        <div class="input-group date">
                                            <input type="date" class="form-control" required name="date" value="<?= $tanggalSchedule ?>" id="date" min="<?= date('Y-m-d') ?>" />
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="">Jam</label>
                                    <div>
                                        <select class="form-control selectpicker" required name="time" data-size="7" id="time">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Tipe Job</label>
                                    <?php
                                    $typeJob = [
                                        "Instalasi" => "Instalasi",
                                        "Maintenance" => "Maintenance",
                                        "Dismantle" => "Dismantle",
                                    ];
                                    ?>
                                    <input type="text" class="form-control" value="<?= $typeJob[$row['job_type']] ?>" disabled='disabled'>
                                    <input type="hidden" name="job_type" value="<?= $typeJob[$row['job_type']] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control selectpicker" required name="status" data-size="7">
                                        <option value="">--Select--</option>
                                        <?php foreach ($status as $s): ?>
                                            <?php $selected = ($s == $row['status_schedule']) ? 'selected' : ''; ?>
                                            <option value="<?= $s ?>" <?= $selected ?>><?= $s ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <?php if (isset($issue)): ?>
                                    <input type="hidden" name="issue_id" value="<?= $issue['issue_id'] ?>">
                                <?php endif; ?>
                                <div class="form-group mb-1">
                                    <label for="exampleTextarea">Alamat</label>
                                    <textarea class="form-control" id="exampleTextarea" readonly name="location" rows="3"><?= $row['location'] ?></textarea>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="exampleTextarea">Catatan</label>
                                    <textarea class="form-control" id="exampleTextarea" name="catatan" rows="3"><?= $row['deskripsi'] ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= BASE_URL ?>pages/schedule/" class="btn btn-light-danger font-weight-bold">Cancel</a>

                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-5 mb-5">
                    <div class="card card-custom pb-2 gutter-b">
                        <!--begin::Header-->
                        <div class="card-header align-items-center border-0 mt-4">
                            <h3 class="card-title align-items-start flex-column">
                                <span class="font-weight-bolder text-dark">Technician Activities</span>
                            </h3>
                            <div class="card-toolbar">
                                <div class="dropdown dropdown-inline">
                                    <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="ki ki-bold-more-hor"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                        <!--begin::Navigation-->
                                        <ul class="navi navi-hover">
                                            <li class="navi-header font-weight-bold py-4">
                                                <span class="font-size-lg">Info Label:</span>
                                                <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right"></i>
                                            </li>
                                            <li class="navi-separator mb-3 opacity-70"></li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span class="label label-xl label-inline label-light-success">Instalasi</span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span class="label label-xl label-inline label-light-warning ">Maintenance</span>
                                                    </span>
                                                </a>
                                            </li>
                                            <li class="navi-item">
                                                <a href="#" class="navi-link">
                                                    <span class="navi-text">
                                                        <span class="label label-xl label-inline  label-light-danger">Dismantle</span>
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                        <!--end::Navigation-->
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--end::Header-->


                        <div class="card-body pt-4" id="card-timeline">
                            <!--begin::Timeline-->
                            <div class="timeline timeline-6 mt-3" id="timeline">
                            </div>
                            <!--end::Timeline-->
                        </div>
                        <!--end: Card Body-->
                    </div>
                    <?php if (isset($issue)): ?>
                        <div class="card card-custom card-collapsed mb-5" data-card="true">

                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Issue Report</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top">
                                        <i class="ki ki-arrow-down icon-nm"></i>
                                    </a>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>Issue ID</th>
                                            <td><?= $issue['issue_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Schedule ID</th>
                                            <td><?= $issue['schedule_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Reported By</th>
                                            <td><?= $issue['reported_by'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Issue_type</th>
                                            <td><?= $issue['issue_type'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Status</th>
                                            <td><?= $issue['status'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Description</th>
                                            <td class="text-wrap"><?= $issue['description'] ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="card card-custom mb-5" data-card="true">
                        <?php if ($row['type_queue'] == "Install"): ?>
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Data Request IKR</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top">
                                        <i class="ki ki-arrow-down icon-nm"></i>
                                    </a>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>RIKR ID</th>
                                            <td><?= $row['rikr_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Netpay ID</th>
                                            <td><?= $row['netpay_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jadwal Pemasangan</th>
                                            <td><?= $tanggalPemasangan ?></td>
                                        </tr>
                                        <tr>
                                            <th>Jam</th>
                                            <td><?= $jamPemasangan ?> </td>
                                        </tr>
                                        <tr>
                                            <th>Request By</th>
                                            <td><?= $row['request_by'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Catatan</th>
                                            <td><?= $row['catatan'] ?></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php elseif ($row['type_queue'] == "Maintenance"): ?>
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Data Request Maintenance</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top">
                                        <i class="ki ki-arrow-down icon-nm"></i>
                                    </a>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>RM ID</th>
                                            <td><?= $row['rm_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Netpay ID</th>
                                            <td><?= $row['netpay_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Type Issue</th>
                                            <td><?= $row['type_issue'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Request By</th>
                                            <td><?= $row['request_by'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi Issue</th>
                                            <td><?= $row['deskripsi_issue'] ?> </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php elseif ($row['type_queue'] == "Dismantle"): ?>
                            <div class="card-header">
                                <div class="card-title">
                                    <h3 class="card-label">Data Request Dismantle</h3>
                                </div>
                                <div class="card-toolbar">
                                    <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top">
                                        <i class="ki ki-arrow-down icon-nm"></i>
                                    </a>

                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>RD ID</th>
                                            <td><?= $row['rd_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Netpay ID</th>
                                            <td><?= $row['netpay_id'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Type Dismantle</th>
                                            <td><?= $row['type_dismantle'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Request By</th>
                                            <td><?= $row['request_by'] ?></td>
                                        </tr>
                                        <tr>
                                            <th>Deskripsi Dismantle</th>
                                            <td><?= $row['deskripsi_dismantle'] ?> </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="card card-custom card-collapsed mb-5" data-card="true">
                        <div class="card-header">
                            <div class="card-title">
                                <h5 class="card-label">Queue Info</h5>
                            </div>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top">
                                    <i class="ki ki-arrow-down icon-nm"></i>
                                </a>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Queue ID</th>
                                        <td><?= $row['queue_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Type Queue</th>
                                        <td><?= $row['type_queue'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Request ID</th>
                                        <td><?= $row['request_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span class="badge badge-pill badge-<?= $statusClasses[$row['status']] ?>"><?= $row['status'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td><?= $cr ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="card card-custom card-collapsed mb-5" data-card="true">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Data Customer</h3>
                            </div>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top">
                                    <i class="ki ki-arrow-down icon-nm"></i>
                                </a>

                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= $row['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td><?= $row['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td><?= $row['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket</th>
                                        <td><?= $row['paket_internet'] ?> Mbps</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td><?= $row['location'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

<?php
require __DIR__ . '/../../includes/footer.php';
?>