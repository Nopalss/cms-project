<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'customer';

$id = (int)($_GET['id'] ?? null);

if (!$id) {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'ID Customer tidak valid.',
        'button' => "Kembali",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/customers/");
    exit;
}

try {
    $sql = "SELECT *
            FROM customers
            WHERE netpay_key = :netpay_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':netpay_key' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Customer tidak ditemukan.',
            'button' => "Kembali",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/customers/");
        exit;
    }
} catch (PDOException $e) {
    $_SESSION['alert'] = [
        'icon' => 'error',
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

                    <h5 class="text-dark font-weight-bold my-1 mr-5"><a class="text-dark " href=" <?= BASE_URL ?>pages/customers/">Customers</a> </h5>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item"><a href="" class="text-muted">Detail Customers</a></li>
                        <li class="breadcrumb-item"><a href="" class="text-muted"><?= $row['netpay_id'] ?></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Entry -->
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="row justify-content-center">
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
                                        <?php
                                        $active = [
                                            "Inactive" => "danger",
                                            "Active" => "success"
                                        ]
                                        ?>
                                        <td> <span class="label label-<?= $active[$row['is_active']] ?> label-dot mr-2"></span><span class="font-weight-bold text-<?= $active[$row['is_active']] ?>"><?= $row['is_active'] ?></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td class="text-wrap"><?= $row['location'] ?></td>
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