<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'profile';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_FILES['profile_avatar']) && $_FILES['profile_avatar']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../../assets/media/users/';
        $fileTmpPath = $_FILES['profile_avatar']['tmp_name'];
        $fileName = uniqid() . '_' . basename($_FILES['profile_avatar']['name']);
        $destPath = $uploadDir . $fileName;

        // Buat folder jika belum ada
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Pindahkan file
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            // Simpan nama file ke database
            $update = $pdo->prepare("UPDATE users SET avatar = :avatar WHERE username = :username");
            $update->execute([
                ':avatar' => $fileName,
                ':username' => $_SESSION['username']
            ]);
        }
    }

    // Optional: update nama dan phone
    if (!empty($_POST['name']) && !empty($_POST['phone']) && !empty($_POST['role'])) {
        $table = $_POST['role'] == "admin" ? "admin" : "technician";
        $updateInfo = $pdo->prepare("
            UPDATE  $table
            SET name = :name, phone = :phone
            WHERE username = :username
        ");
        $updateInfo->execute([
            ':name' => $_POST['name'],
            ':phone' => $_POST['phone'],
            ':username' => $_SESSION['username']
        ]);
    }

    // Refresh halaman biar reload data baru
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

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

$avatarPath = !empty($row['avatar'])
    ? "assets/media/users/" . htmlspecialchars($row['avatar'])
    : "assets/media/users/blank.png";

?>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-conten bt-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">

                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Users</h5>

                    <!--end::Page Title-->

                    <!--begin::Breadcrumb-->
                    <!-- <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                                General </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                                Empty Page </a>
                        </li>
                    </ul> -->
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
        <div class=" container ">
            <!--begin::Profile Personal Information-->
            <div class="d-flex flex-row">
                <!--begin::Aside-->
                <div class="flex-row-auto offcanvas-mobile w-250px w-xxl-350px" id="kt_profile_aside">
                    <!--begin::Profile Card-->
                    <div class="card card-custom ">
                        <!--begin::Body-->
                        <div class="card-body pt-4">
                            <!--begin::User-->
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
                                    <div class="symbol-label" style="background-image:url('<?= BASE_URL ?>assets/media/users/<?= $row['avatar'] ?>')"></div>
                                    <i class="symbol-badge bg-success"></i>
                                </div>
                                <div>
                                    <a href="#" class="font-weight-bolder font-size-h5 text-dark-75 text-hover-primary">
                                        <?= $row['name'] ?>
                                    </a>
                                    <div class="text-muted">
                                        <?= $row['role'] ?>
                                    </div>

                                </div>
                            </div>
                            <!--end::User-->

                            <!--begin::Contact-->
                            <div class="py-9">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">username:</span>
                                    <a href="#" class="text-muted text-hover-primary"><?= $row['username'] ?></a>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="font-weight-bold mr-2">Phone:</span>
                                    <span class="text-muted"><?= $row['phone'] ?></span>
                                </div>
                            </div>
                            <!--end::Nav-->
                        </div>
                        <!--end::Body-->
                    </div>
                    <!--end::Profile Card-->
                </div>
                <!--end::Aside-->
                <!--begin::Content-->
                <div class="flex-row-fluid ml-lg-8">
                    <!--begin::Card-->
                    <div class="card card-custom card-stretch">
                        <!--begin::Header-->
                        <div class="card-header py-3">
                            <div class="card-title align-items-start flex-column">
                                <h3 class="card-label font-weight-bolder text-dark">Personal Information</h3>
                                <span class="text-muted font-weight-bold font-size-sm mt-1">Update your personal informaiton</span>
                            </div>
                            <div class="card-toolbar">
                                <button type="reset" class="btn btn-success mr-2">Save Changes</button>
                                <button type="reset" class="btn btn-secondary">Cancel</button>
                            </div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Form-->
                        <form class="form" method="POST" enctype="multipart/form-data">
                            <!--begin::Body-->
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
                                            <div class="image-input-wrapper"
                                                style="background-image: url('<?= BASE_URL . $avatarPath ?>')">
                                            </div>

                                            <label class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="change" data-toggle="tooltip" title="Change avatar">
                                                <i class="fa fa-pen icon-sm text-muted"></i>
                                                <input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg">
                                                <input type="hidden" name="profile_avatar_remove" />
                                            </label>

                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="cancel" title="Cancel avatar">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>

                                            <span class="btn btn-xs btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow"
                                                data-action="remove" title="Remove avatar">
                                                <i class="ki ki-bold-close icon-xs text-muted"></i>
                                            </span>
                                        </div>
                                        <span class="form-text text-muted">Allowed file types: png, jpg, jpeg.</span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-xl-3 col-lg-3 col-form-label">Name</label>
                                    <div class="col-lg-9 col-xl-6">
                                        <input class="form-control form-control-lg form-control-solid" type="text" value="<?= $row['name'] ?>">
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
                                            <input type="text" class="form-control form-control-lg form-control-solid" value="<?= $row['phone'] ?>" placeholder="Phone">
                                        </div>
                                        <span class="form-text text-muted">We'll never share your email with anyone else.</span>
                                    </div>
                                </div>
                            </div>
                            <!--end::Body-->
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
                <!--end::Content-->
            </div>
            <!--end::Profile Personal Information-->
        </div>
    </div>
    <!--end::Container-->
</div>
<!--end::Card-->

<?php
require __DIR__ . '/../../includes/footer.php';
?>