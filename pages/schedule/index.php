<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'schedule';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

try {
    $sql = "SELECT * FROM technician";
    $stmt = $pdo->query($sql);
    $technicians = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
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
                        Schedule </h5>

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

            <?php
            if ($_SESSION['role'] == 'admin') {
                require __DIR__ . "/role/admin.php";
            }
            if ($_SESSION['role'] == 'teknisi') {
                require __DIR__ . "/role/teknisi.php";
            }
            ?>
        </div>
        <!-- end::Container -->
    </div>
</div>
<!-- end::entry -->

<script>
    var HOST_URL = "<?= BASE_URL ?>";
</script>
<?php
require __DIR__ . "/modal_create_schedule.php";
require __DIR__ . '/../../includes/footer.php';
?>