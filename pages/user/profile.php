<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'profile';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

// --- LOGIKA 1: PROSES PENYIMPANAN DATA (POST) ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    try {
        $pdo->beginTransaction(); // Mulai transaksi database biar aman

        // 1. Cek User Role (Admin/Technician) untuk menentukan tabel mana yang diupdate
        // Kita ambil fresh dari database berdasarkan session biar aman, tidak dari input user
        $stmtRole = $pdo->prepare("SELECT role FROM users WHERE username = :username");
        $stmtRole->execute([':username' => $_SESSION['username']]);
        $userRole = $stmtRole->fetchColumn();

        // 2. Proses Upload Foto (Jika ada file yang dipilih)
        if (isset($_FILES['profile_avatar']) && $_FILES['profile_avatar']['error'] === UPLOAD_ERR_OK) {
            // Path Folder: Mundur 2 langkah dari folder ini -> masuk assets/media/users
            $uploadDir = dirname(__DIR__, 2) . '/assets/media/users/';

            $fileTmpPath = $_FILES['profile_avatar']['tmp_name'];
            $fileExt = strtolower(pathinfo($_FILES['profile_avatar']['name'], PATHINFO_EXTENSION));
            $allowedExts = ['jpg', 'jpeg', 'png'];

            if (in_array($fileExt, $allowedExts)) {
                $fileName = uniqid() . '.' . $fileExt;
                $destPath = $uploadDir . $fileName;

                // Buat folder jika belum ada
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                if (move_uploaded_file($fileTmpPath, $destPath)) {
                    // Update kolom avatar di tabel users
                    $updateAvatar = $pdo->prepare("UPDATE users SET avatar = :avatar WHERE username = :username");
                    $updateAvatar->execute([
                        ':avatar' => $fileName,
                        ':username' => $_SESSION['username']
                    ]);
                }
            }
        }

        // 3. Proses Update Nama & No HP
        // Pastikan input name dan phone tidak kosong
        if (!empty($_POST['name']) && !empty($_POST['phone'])) {
            $targetTable = ($userRole === 'admin') ? 'admin' : 'technician';

            $updateInfo = $pdo->prepare("UPDATE $targetTable SET name = :name, phone = :phone WHERE username = :username");
            $updateInfo->execute([
                ':name' => $_POST['name'],
                ':phone' => $_POST['phone'],
                ':username' => $_SESSION['username']
            ]);
        }

        $pdo->commit(); // Simpan perubahan permanen

        // Refresh halaman dengan JavaScript alert sukses
        echo "<script>
            alert('Data berhasil disimpan!');
            window.location.href='" . $_SERVER['PHP_SELF'] . "';
        </script>";
        exit;
    } catch (Exception $e) {
        $pdo->rollBack(); // Batalkan jika ada error
        echo "<script>alert('Gagal menyimpan: " . $e->getMessage() . "');</script>";
    }
}

// --- LOGIKA 2: MENAMPILKAN DATA SAAT INI ---
$sql = "SELECT 
            u.username,
            u.role,
            u.avatar,
            COALESCE(t.name, a.name) AS name,
            COALESCE(t.phone, a.phone) AS phone
        FROM users u
        LEFT JOIN technician t ON u.username = t.username
        LEFT JOIN admin a ON u.username = a.username
        WHERE u.username = :username";

$stmt = $pdo->prepare($sql);
$stmt->execute([":username" => $_SESSION['username']]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);

// Path untuk foto profil
$avatarPath = !empty($row['avatar']) ? "assets/media/users/" . htmlspecialchars($row['avatar']) : "assets/media/users/blank.png";
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5">User Profile</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="d-flex flex-row">
                <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
                    <div class="card card-custom">
                        <div class="card-body pt-4">
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                    <div class="symbol-label" style="background-image:url('<?= BASE_URL ?>assets/media/users/<?= $row['avatar'] ?>')"></div>
                                    <i class="symbol-badge bg-success"></i>
                                </div>
                                <div>
                                    <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">
                                        <?= htmlspecialchars($row['name']) ?>
                                    </a>
                                    <div class="text-muted"><?= htmlspecialchars($row['role']) ?></div>
                                </div>
                            </div>
                            <div class="py-9">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Username:</span>
                                    <a href="#" class="text-muted text-hover-primary"><?= htmlspecialchars($row['username']) ?></a>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Phone:</span>
                                    <span class="text-muted"><?= htmlspecialchars($row['phone']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex-row-fluid ml-lg-8">
                    <div class="card card-custom card-stretch">
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Personal Information</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Update your personal information</span>
                            </div>
                        </div>
                        <form class="form" method="POST" action="" enctype="multipart/form-data">

                            <input type="hidden" name="role" value="<?= $row['role'] ?>">

                            <div class="card-body">
                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mb-6">Customer Info</h5>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Avatar</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="image-input image-input-outline" id="kt_profile_avatar">
                                            <div class="image-input-wrapper" id="previewBox"
                                                style="background-image: url('<?= BASE_URL . $avatarPath ?>')">
                                            </div>

                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="profile_avatar" id="inputGambar" accept=".png, .jpg, .jpeg" onchange="previewFile(this)">
                                                <input type="hidden" name="profile_avatar_remove" />
                                            </label>

                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="remove" title="Remove avatar" onclick="resetFile()">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control form-control-lg form-control-solid" type="text" name="name" value="<?= $row['name'] ?>">
                                    </div>
                                </div>

                                <div class="row">
                                    <label class="col-xl-3"></label>
                                    <div class="col-lg-9 col-xl-6">
                                        <h5 class="font-weight-bold mt-10 mb-6">Contact Info</h5>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Contact Phone</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <div class="input-group input-group-lg input-group-solid">
                                            <div class="input-group-prepend"><span class="input-group-text"><i class="la la-phone"></i></span></div>
                                            <input type="text" class="form-control form-control-lg form-control-solid" name="phone" value="<?= $row['phone'] ?>" placeholder="Phone">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                        <button type="submit" class="btn btn-success mr-2">Save Changes</button>
                                        <button type="reset" class="btn btn-secondary">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
require __DIR__ . '/../../includes/footer.php';
?>