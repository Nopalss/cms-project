<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'dismantle';


$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'ID Tidak Valid',
        'text' => 'Service Report ID tidak ditemukan.',
        'button' => "Kembali",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/dismantle/");
    exit;
}
try {
    $sql = "SELECT dr.*, c.*
            FROM dismantle_reports dr
            JOIN customers c ON dr.netpay_key = c.netpay_key
            WHERE dr.dismantle_key = :dismantle_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':dismantle_key' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $dt = new DateTime($row['tanggal']);
    $tanggal = $dt->format('d F Y');
} catch (PDOException $e) {
    $_SESSION['alert'] = [
        'icon' => 'danger',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
}
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!-- Subheader -->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Dismantle Report </h5>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item"><a href="" class="text-muted">Detail Dismantle Report</a></li>
                        <li class="breadcrumb-item"><a href="" class="text-muted"><?= $row['dismantle_id'] ?></a></li>
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
                            <h5>Detail Dismantle Report</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Dismantle ID</th>
                                        <td><?= $row['dismantle_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= $row['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jam</th>
                                        <td><?= $row['jam'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Tanggal</th>
                                        <td><?= $row['tanggal'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alasan</th>
                                        <td class="text-wrap"><?= $row['alasan'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Action</th>
                                        <td class="text-wrap"><?= $row['action'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Part yang diambil</th>
                                        <td><?= $row['part_removed'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kondisi Perangkat</th>
                                        <td><?= $row['kondisi_perangkat'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>PIC</th>
                                        <td><?= $row['pic'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Keterangan</th>
                                        <td class="text-wrap"><?= $row['keterangan'] ?></td>
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
        </div>
    </div>
</div>

<?php require __DIR__ . '/../../includes/footer.php'; ?>