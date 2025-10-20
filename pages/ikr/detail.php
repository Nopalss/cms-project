<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'ikr';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Data tidak ditemukan',
        'text' => 'Parameter ID tidak valid.',
        'button' => 'Oke',
        'style' => 'warning'
    ];
    header("Location: " . BASE_URL . "pages/ikr/");
    exit;
}

try {
    $sql = "SELECT ikr.*, c.*
            FROM ikr
            JOIN customers c ON ikr.netpay_key = c.netpay_key
            WHERE ikr.ikr_key = :ikr_key";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':ikr_key', $id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Data tidak ditemukan',
            'text' => "IKR dengan ID $id tidak ada di database.",
            'button' => 'Oke',
            'style' => 'warning'
        ];
        header("Location: " . BASE_URL . "pages/ikr/");
        exit;
    }
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
                    <a href="<?= BASE_URL ?>pages/ikr/">
                        <h5 class="text-dark font-weight-bold my-1 mr-5"> IKR Reports</h5>
                    </a>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item"><a href="" class="text-muted">Detail Report IKR</a></li>
                        <li class="breadcrumb-item"><a href="" class="text-muted"><?= $row['ikr_id'] ?></a></li>
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
                            <h5>Detail Report IKR</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>IKR ID</th>
                                        <td><?= $row['ikr_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= $row['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Group IKR</th>
                                        <td><?= $row['group_ikr'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>IKR An</th>
                                        <td><?= $row['ikr_an'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>RT</th>
                                        <td><?= $row['rt'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>RW</th>
                                        <td><?= $row['rw'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Desa</th>
                                        <td><?= $row['desa'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kecamatan</th>
                                        <td><?= $row['kec'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Kabupaten</th>
                                        <td><?= $row['kab'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td><?= $row['alamat'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>SN</th>
                                        <td><?= $row['sn'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket</th>
                                        <td><?= $row['paket'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Type Ont</th>
                                        <td><?= $row['type_ont'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Redaman</th>
                                        <td><?= $row['redaman'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>ODP No</th>
                                        <td><?= $row['odp_no'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>ODC No</th>
                                        <td><?= $row['odc_no'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>JC No</th>
                                        <td><?= $row['jc_no'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mac Sebelum</th>
                                        <td><?= $row['mac_sebelum'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Mac Sesudah</th>
                                        <td><?= $row['mac_sesudah'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>ODP</th>
                                        <td><?= $row['odp'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>ODC</th>
                                        <td><?= $row['odc'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Enclosure</th>
                                        <td><?= $row['enclosure'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket No</th>
                                        <td><?= $row['paket_no'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td><?= $row['created_at'] ?></td>
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