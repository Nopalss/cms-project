<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'schedule';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

$id = $_GET['id'];
try {
    $sql = "SELECT s.*, c.*, ri.catatan,
                   EXISTS (
                       SELECT 1 
                       FROM issues_report ir 
                       WHERE ir.schedule_id = s.schedule_id
                        AND ir.status = 'Pending'
                   ) AS has_issue
            FROM schedules s
            JOIN customers c ON s.netpay_id = c.netpay_id
            JOIN request_ikr ri ON s.netpay_id = ri.netpay_id 
            WHERE s.schedule_id = :schedule_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':schedule_id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $dt = new DateTime($row['date']);
    $tanggal = $dt->format('d F Y');
    $actionDone = [
        "Instalasi" => "ikr",
        "Maintenance" => "service_report",
        "Dismantle" => "dismantle"
    ];
} catch (PDOException $e) {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
}
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!-- Subheader -->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Schedule</h5>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item"><a href="" class="text-muted">Detail Schedule</a></li>
                        <li class="breadcrumb-item"><a href="" class="text-muted"><?= $id ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Entry -->
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="row">
                <!-- Detail Schedule -->
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5>Detail Schedule</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Schedule ID</th>
                                        <td><?= $row['schedule_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= $row['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Technician ID</th>
                                        <td><?= $row['tech_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td><?= $tanggal ?></td>
                                    </tr>
                                    <tr>
                                        <th>Time</th>
                                        <td><?= date("H:i", strtotime($row['time'])) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Job Type</th>
                                        <td><?= $row['job_type'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><?= $row['status'] ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-wrapped">Catatan</th>
                                        <td><?= $row['catatan'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detail Customers -->
                <div class="col-md-6 mt-5 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Detail Customers</h5>
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
                                        <th>Paket Internet</th>
                                        <td><?= $row['paket_internet'] ?> Mbps</td>
                                    </tr>
                                    <tr>
                                        <th>Is Active?</th>
                                        <td><?= $row['is_active'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td class="text-wrapped"><?= $row['location'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <?php if ($row['status'] == 'Pending' || $row['status'] == 'Rescheduled'): ?>
                <div class="text-right mt-3">
                    <?php if (!$row['has_issue']): ?>
                        <a class="btn mr-5 btn-light-warning" href="<?= BASE_URL ?>pages/schedule/issue_report.php?id=<?= $row['schedule_id'] ?>">
                            <span class="navi-icon"><i class="flaticon2-warning"></i></span>
                            <span class="navi-text">Task Issue Report</span>
                        </a>
                        <div class="btn">
                            <form action=" <?= BASE_URL ?>pages/<?= $actionDone[$row['job_type']] ?>/create.php" method="post">
                                <button class=" btn btn-success" name="id" value="<?= $row['schedule_id'] ?>">
                                    <span class="navi-icon"><i class="flaticon2-check-mark"></i></span>
                                    <span class="navi-text">Mark as Done</span>
                                </button>
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>