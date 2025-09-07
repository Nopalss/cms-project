<?php
require_once '../includes/config.php';
$_SESSION['menu'] = 'dashboard';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/aside.php';
require __DIR__ . '/../includes/navbar.php';
?>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">

                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <h5 class="text-dark font-weight-bold my-1 mr-5">
                        Dashboard </h5>

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
            <p>Page content goes here...</p>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

<!-- sweetalert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "Login Berhasi",
            text: '<?= $_SESSION['success'] ?>',
            confirmButtonText: 'Go!',
            customClass: {
                title: 'text-success',
                confirmButton: "btn font-weight-bold btn-outline-success",
                icon: 'm-auto'
            }

        });
    </script>
<?php unset($_SESSION['success']);
endif; ?>
<?php
require __DIR__ . '/../includes/footer.php';
?>