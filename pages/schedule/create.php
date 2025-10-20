<?php
require_once __DIR__ . '/../../includes/config.php';
date_default_timezone_set('Asia/Jakarta');
$id = isset($_POST['id']) ? $_POST['id'] : null;
$type_queue = isset($_POST['type_queue']) ? $_POST['type_queue'] : null;
if ($id && $type_queue) {
    $_SESSION['menu'] = 'schedule';
    require __DIR__ . '/../../includes/header.php';
    require __DIR__ . '/../../includes/aside.php';
    require __DIR__ . '/../../includes/navbar.php';
    try {
        $requestTables = [
            "Install"    => ["table" => "request_ikr", "id" => "rikr_id", "catatan" => "catatan"],
            "Maintenance"  => ["table" => "request_maintenance", "id" => "rm_id", "catatan" => "deskripsi_issue"],
            "Dismantle"    => ["table" => "request_dismantle", "id" => "rd_id", "catatan" => "deskripsi_dismantle"],
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
        if (isset($requestTables[$type_queue])) {
            $table = $requestTables[$type_queue]['table'];
            $idCol = $requestTables[$type_queue]['id'];
            $catKey = $requestTables[$type_queue]['catatan'];

            $sql = "SELECT q.*, r.*, c.*, r.$catKey as catatan 
            FROM queue_scheduling q
            JOIN $table r ON q.request_id = r.$idCol
            JOIN customers c ON r.netpay_key = c.netpay_key
            WHERE q.queue_key = :queue_key";

            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':queue_key', $id, PDO::PARAM_STR);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
        }
        $tanggalSchedule   = isset($row['jadwal_pemasangan']) ? formatDate($row['jadwal_pemasangan'], 'date') : '';
        $tanggalPemasangan = isset($row['jadwal_pemasangan']) ? formatDate($row['jadwal_pemasangan'], 'full') : '';
        $jamPemasangan     = isset($row['jadwal_pemasangan']) ? formatDate($row['jadwal_pemasangan'], 'time') : '';
        $cr                = formatDate($row['created_at'], 'full');
        $statusClasses = [
            'Pending' => "info",
            'Accepted' => "success",
            'Rejected' => "danger",
        ];
        $schedule_id = "S" . date("YmdHis");
        $sql = "SELECT * FROM technician";
        $stmt = $pdo->query($sql);
        $technicians = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
                                Create Schedule </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                                <?= $schedule_id ?> </a>
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
                        <form action="<?= BASE_URL ?>controllers/schedules/create.php" method="post">
                            <div class="card-header">
                                <h3>Create Schedule</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="name">Netpay ID</label>
                                    <input type="text" class="form-control" value="<?= $row['netpay_id'] ?>" disabled="disabled" required>
                                    <input type="hidden" class="form-control" name="schedule_id" value="<?= $schedule_id ?>">
                                    <input type="hidden" class="form-control" name="queue_key" value="<?= $row['queue_key'] ?>">
                                    <input type="hidden" class="form-control" name="netpay_key" value="<?= $row['netpay_key'] ?>">
                                </div>
                                <div class="form-group">
                                    <label>Teknisi</label>
                                    <select class="form-control selectpicker" id="tech_id" required name="tech_id" data-size=" 7" data-live-search="true">
                                        <option value="">Select</option>
                                        <?php foreach ($technicians as $t): ?>
                                            <option value="<?= $t['tech_id'] ?>"><?= $t['name'] ?></option>
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
                                        "Install" => "Instalasi",
                                        "Maintenance" => "Maintenance",
                                        "Dismantle" => "Dismantle",
                                    ];
                                    ?>
                                    <input type="text" class="form-control" value="<?= $typeJob[$row['type_queue']] ?>" disabled='disabled'>
                                    <input type="hidden" name="job_type" value="<?= $typeJob[$row['type_queue']] ?>">
                                </div>
                                <div class="form-group ">
                                    <label>Perumahan</label>
                                    <input type="text" class="form-control" readonly name="perumahan" value="<?= $row['perumahan'] ?>">
                                </div>
                                <div class="form-group ">
                                    <label for="exampleTextarea">Alamat</label>
                                    <textarea class="form-control" id="exampleTextarea" readonly name="location" rows="3"><?= $row['location'] ?></textarea>
                                </div>
                                <div class="form-group ">
                                    <label for="exampleTextarea">Catatan</label>
                                    <textarea class="form-control" id="exampleTextarea" name="catatan" rows="3"><?= $row['catatan'] ?></textarea>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= BASE_URL ?>pages/schedule/" class="btn btn-light-danger font-weight-bold">Cancel</a>
                                <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Create</button>
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

                        <!--begin::Body-->
                        <div class="card-body pt-4" id="card-timeline">
                            <!--begin::Timeline-->
                            <div class="timeline timeline-6 mt-3" id="timeline">
                            </div>
                            <!--end::Timeline-->
                        </div>
                        <!--end: Card Body-->
                    </div>
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
                                        <th>Nama</th>
                                        <td><?= $row['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>No Hp</th>
                                        <td><?= $row['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket</th>
                                        <td><?= $row['paket_internet'] ?> Mbps</td>
                                    </tr>
                                    <tr>
                                        <th>Is Active?</th>
                                        <td><?= $row['is_active'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Perumahan</th>
                                        <td><?= $row['perumahan'] ?></td>
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