<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'ikr';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

$id = $_GET['id'];
try {
    $sql = "SELECT srv.*, c.*
            FROM service_reports srv
            JOIN customers c ON srv.netpay_id = c.netpay_id
            WHERE srv.srv_id = :srv_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':srv_id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $dt = new DateTime($row['created_at']);
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
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!-- Subheader -->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">Service Report </h5>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item"><a href="" class="text-muted">Detail Service Report</a></li>
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
                            <h5>Detail Service Report</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Service Report ID</th>
                                        <td><?= $row['srv_id'] ?></td>
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
                                        <th>Problem</th>
                                        <td class="text-wrap"><?= $row['problem'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Action</th>
                                        <td class="text-wrap"><?= $row['action'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Part</th>
                                        <td><?= $row['part'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Redaman Sebelum</th>
                                        <td><?= $row['red_bef'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Redaman Sesudah</th>
                                        <td><?= $row['red_aft'] ?></td>
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