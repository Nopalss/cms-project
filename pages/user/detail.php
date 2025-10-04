<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'user';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Username tidak valid.',
        'button' => "Kembali",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/user/");
    exit;
}

try {
    $sql = "SELECT 
                u.username,
                u.role,
                CASE 
                    WHEN u.role = 'admin' THEN a.admin_id
                    WHEN u.role = 'teknisi' THEN t.tech_id
                END AS person_id,
                CASE 
                    WHEN u.role = 'admin' THEN a.name
                    WHEN u.role = 'teknisi' THEN t.name
                END AS name,
                CASE 
                    WHEN u.role = 'admin' THEN a.phone
                    WHEN u.role = 'teknisi' THEN t.phone
                END AS phone
            FROM users u
            LEFT JOIN admin a ON u.username = a.username
            LEFT JOIN technician t ON u.username = t.username
            WHERE u.username = :username";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$row) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Customer tidak ditemukan.',
            'button' => "Kembali",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/user/");

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
    header("Location: " . BASE_URL . "pages/user/");
    exit;
}
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!-- Subheader -->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">

                    <h5 class="text-dark font-weight-bold my-1 mr-5"><a class="text-dark " href=" <?= BASE_URL ?>pages/customers/">Users</a> </h5>
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item"><a href="" class="text-muted">Detail users</a></li>
                        <li class="breadcrumb-item"><a href="" class="text-muted"><?= $id ?></a></li>
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
                            <h5>Detail Users</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Username</th>
                                        <td><?= $row['username'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Person ID</th>
                                        <td><?= $row['person_id'] ?></td>
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
                                        <th>Role</th>
                                        <td><?= $row['role'] ?></td>
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