<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'schedule';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

// ambil id/job_type dari POST dulu, fallback ke GET
$id = $_POST['id'] ?? $_GET['id'] ?? null;
$job_type = $_POST['job_type'] ?? $_GET['job_type'] ?? null;

$requestTables = [
    "Instalasi"    => ["table" => "request_ikr", "id" => "rikr_id"],
    "Maintenance"  => ["table" => "request_maintenance", "id" => "rm_id"],
    "Dismantle"    => ["table" => "request_dismantle", "id" => "rd_id"]
];

try {
    if (empty($id)) {
        throw new Exception("ID schedule tidak ditemukan.");
    }

    // default row null
    $row = null;

    if (isset($requestTables[$job_type])) {
        $table  = $requestTables[$job_type]['table'];
        $idCol  = $requestTables[$job_type]['id'];

        // gunakan LEFT JOIN supaya ketiadaan child-row tidak menghilangkan schedule
        $sql = "SELECT s.*, c.*, q.request_id,
                       EXISTS (
                           SELECT 1
                           FROM issues_report ir
                           WHERE ir.schedule_id = s.schedule_id
                             AND ir.status = 'Pending'
                       ) AS has_issue
                FROM schedules s
                LEFT JOIN customers c ON s.netpay_id = c.netpay_id
                LEFT JOIN queue_scheduling q ON s.queue_id = q.queue_id
                LEFT JOIN $table r ON q.request_id = r.$idCol
                WHERE s.schedule_id = :schedule_id
                LIMIT 1";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([':schedule_id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } else {
        // fallback: ambil schedule + customer tanpa join request table
        $sql = "SELECT s.*, c.*,
                       EXISTS (
                           SELECT 1 FROM issues_report ir WHERE ir.schedule_id = s.schedule_id AND ir.status = 'Pending'
                       ) AS has_issue
                FROM schedules s
                LEFT JOIN customers c ON s.netpay_id = c.netpay_id
                WHERE s.schedule_id = :schedule_id
                LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':schedule_id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    }

    if (!$row) {
        // tidak ada data → redirect atau tampil pesan (pilih salah satu)
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Tidak Ditemukan',
            'text' => "Schedule dengan ID <b>" . htmlspecialchars($id) . "</b> tidak ditemukan.",
            'button' => "Kembali",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/schedule/");
        exit;
    }

    // tentukan kolom tanggal (beberapa DB menggunakan nama berbeda)
    $dateField = null;
    foreach (['date', 'tanggal', 'jadwal_pemasangan', 'created_at'] as $f) {
        if (!empty($row[$f])) {
            $dateField = $f;
            break;
        }
    }
    if ($dateField) {
        $dt = new DateTime($row[$dateField]);
        $tanggal = $dt->format('d F Y');
    } else {
        $tanggal = "-";
    }

    $actionDone = [
        "Instalasi"   => "ikr",
        "Maintenance" => "service_report",
        "Dismantle"   => "dismantle"
    ];
} catch (Exception $e) {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/schedule/");
    exit;
}
?>

<!-- HTML: gunakan safe-access (null coalescing) untuk menghindari notice -->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!-- Subheader... (tetap sama) -->

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
                                        <td><?= htmlspecialchars($row['schedule_id'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= htmlspecialchars($row['netpay_id'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Technician ID</th>
                                        <td><?= htmlspecialchars($row['tech_id'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Date</th>
                                        <td><?= htmlspecialchars($tanggal) ?></td>
                                    </tr>
                                    <tr>
                                        <th>Time</th>
                                        <td><?= isset($row['time']) ? date("H:i", strtotime($row['time'])) : '-' ?></td>
                                    </tr>
                                    <tr>
                                        <th>Job Type</th>
                                        <td><?= htmlspecialchars($row['job_type'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><?= htmlspecialchars($row['status'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th class="text-wrapped">Catatan</th>
                                        <td><?= htmlspecialchars($row['catatan']) ?></td>
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
                                        <td><?= htmlspecialchars($row['netpay_id'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td><?= htmlspecialchars($row['name'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>No HP</th>
                                        <td><?= htmlspecialchars($row['phone'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket Internet</th>
                                        <td><?= htmlspecialchars($row['paket_internet'] ?? '-') ?> Mbps</td>
                                    </tr>
                                    <tr>
                                        <th>Is Active?</th>
                                        <td><?= htmlspecialchars($row['is_active'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Perumahan</th>
                                        <td class="text-wrapped"><?= htmlspecialchars($row['perumahan'] ?? '-') ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td class="text-wrapped"><?= htmlspecialchars($row['location'] ?? '-') ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <?php if (($row['status'] ?? '') === 'Pending' || ($row['status'] ?? '') === 'Rescheduled'): ?>
                <div class="text-right mt-3">
                    <?php if (empty($row['has_issue'])): ?>
                        <a class="btn mr-5 btn-light-warning" href="<?= BASE_URL ?>pages/schedule/issue_report.php?id=<?= htmlspecialchars($row['schedule_id']) ?>">
                            <span class="navi-icon"><i class="flaticon2-warning"></i></span>
                            <span class="navi-text">Task Issue Report</span>
                        </a>

                        <div class="btn">
                            <button onclick="confirmActiveTask('<?= $row['schedule_id'] ?>', 'controllers/schedules/actived.php')" class=" btn btn-success">
                                <span class="navi-icon"><i class="fas fa-hourglass-start"></i></span>
                                <span class="navi-text">Mulai Kerja</span>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            <?php if (($row['status'] ?? '') === 'Actived'): ?>
                <div class="text-right mt-3">
                    <?php if (empty($row['has_issue'])): ?>
                        <a class="btn mr-5 btn-light-warning" href="<?= BASE_URL ?>pages/schedule/issue_report.php?id=<?= htmlspecialchars($row['schedule_id']) ?>">
                            <span class="navi-icon"><i class="flaticon2-warning"></i></span>
                            <span class="navi-text">Task Issue Report</span>
                        </a>
                        <div class="btn">
                            <?php $jobKey = $row['job_type'] ?? $job_type; ?>
                            <?php if (!empty($actionDone[$jobKey])): ?>
                                <form action="<?= BASE_URL ?>pages/<?= htmlspecialchars($actionDone[$jobKey]) ?>/create.php" method="post">
                                    <button class=" btn btn-success" name="id" value="<?= htmlspecialchars($row['schedule_id']) ?>">
                                        <span class="navi-icon"><i class="flaticon2-check-mark"></i></span>
                                        <span class="navi-text">Mark as Done</span>
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>


        </div>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>