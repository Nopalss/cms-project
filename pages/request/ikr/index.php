<?php
require_once __DIR__ . '/../../../includes/config.php';
$_SESSION['menu'] = 'request ikr';
require __DIR__ . '/../../../includes/header.php';
require __DIR__ . '/../../../includes/aside.php';
require __DIR__ . '/../../../includes/navbar.php';

$sql = "
        SELECT *
        FROM register
        WHERE is_verified = 'Unverified'";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$register = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                        Request IKR </h5>

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

            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header flex-wrap border-0 pt-6 pb-0">
                    <div class="card-title">
                        <h3 class="card-label">
                            Data Request IKR
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Dropdown-->
                        <div class="dropdown dropdown-inline mr-2">

                            <!--begin::Dropdown Menu-->
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                <!--begin::Navigation-->
                                <ul class="navi flex-column navi-hover py-2">
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-sm text-primary pb-2">
                                        Choose an option:
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon"><i class="la la-print"></i></span>
                                            <span class="navi-text">Print</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon"><i class="la la-copy"></i></span>
                                            <span class="navi-text">Copy</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon"><i class="la la-file-excel-o"></i></span>
                                            <span class="navi-text">Excel</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon"><i class="la la-file-text-o"></i></span>
                                            <span class="navi-text">CSV</span>
                                        </a>
                                    </li>
                                    <li class="navi-item">
                                        <a href="#" class="navi-link">
                                            <span class="navi-icon"><i class="la la-file-pdf-o"></i></span>
                                            <span class="navi-text">PDF</span>
                                        </a>
                                    </li>
                                </ul>
                                <!--end::Navigation-->
                            </div>
                            <!--end::Dropdown Menu-->
                        </div>
                        <!--end::Dropdown-->

                        <!--begin::Button-->
                        <a href="<?= BASE_URL ?>pages/request/ikr/create.php" class="btn btn-primary font-weight-bolder">
                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                                    </g>
                                </svg><!--end::Svg Icon--></span>New Request
                            <small id="unverifiedCount" class="ml-3 label label-danger mr-2" style="display:none;"></small>
                        </a>
                        <!--end::Button-->
                    </div>
                </div>
                <div class="card-body">
                    <!--begin: Search Form-->
                    <!--begin::Search Form-->
                    <div class="mb-7">
                        <div class="row align-items-center">
                            <div class="col-lg-12 col-xl-12">
                                <div class="row align-items-center">
                                    <div class="col-md-3 my-2 my-md-0">
                                        <div class="input-icon">
                                            <input type="text" class="form-control" placeholder="Search..." id="kt_datatable_search_query" />
                                            <span><i class="flaticon2-search-1 text-muted"></i></span>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-2 my-md-0">
                                        <div class="d-flex align-items-center">
                                            <label class="mr-3 mb-0 d-none d-md-block">Status:</label>
                                            <select class="form-control" id="kt_datatable_search_status">
                                                <option value="">All</option>
                                                <option value="Accepted">Accepted</option>
                                                <option value="Rejected">Rejected</option>
                                                <option value="Pending">Pending</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3 my-2 my-md-0">
                                        <div class=" d-flex align-items-center">
                                            <div class="input-group date">
                                                <input type="text" class="form-control" name="date" readonly placeholder="mm/dd/yyyy" id="kt_datepicker_3" />
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-calendar"></i>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Search Form-->
                    <!--end: Search Form-->

                    <!--begin: Datatable-->
                    <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!-- end::Container -->
    </div>
    <!-- end::entry -->
    <!-- modal detail registrasi-->
    <div class="modal fade" id="detailModalRIKR" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
            <div class="modal-content shadow-lg border-0 rounded-lg">
                <div class="modal-header">
                    <h4 class="modal-title"><i class="la la-info-circle text-info"></i> Detail Request IKR</h4>
                    <button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2 pl-2">
                        <div class="col-4 font-weight-bold">RIKR ID</div>
                        <div class="col-8" id="detail_rikrId"></div>
                    </div>
                    <div class="row mb-2 pl-2">
                        <div class="col-4 font-weight-bold">Netpay ID</div>
                        <div class="col-8" id="detail_netpayId"></div>
                    </div>
                    <div class="row mb-2 pl-2">
                        <div class="col-4 font-weight-bold">Registrasi ID</div>
                        <div class="col-8" id="detail_registrasiId"></div>
                    </div>
                    <div class="row mb-2 pl-2 justify-content-center align-items-center">
                        <div class="col-4 font-weight-bold">Jadwal Pemasangan</div>
                        <div class="col-8">
                            <div id="detail_jadwal"></div>
                        </div>
                    </div>
                    <div class="row mb-2 pl-2">
                        <div class="col-4 font-weight-bold">Jam</div>
                        <div class="col-8" id="detail_jam"></div>
                    </div>
                    <div class="row mb-2 pl-2">
                        <div class="col-4 font-weight-bold">Status</div>
                        <div class="col-8" id="detail_status"></div>
                    </div>
                    <div class="row mb-2 pl-2">
                        <div class="col-4 font-weight-bold">Request By</div>
                        <div class="col-8" id="detail_requestBy"></div>
                    </div>
                    <div class="row mb-2 pl-2">
                        <div class="col-4 font-weight-bold">Catatan</div>
                        <div class="col-8">
                            <div id="detail_catatan"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-dismiss="modal">
                        <i class="la la-times"></i> Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>

    <?php
    require __DIR__ . '/../../../includes/footer.php';
    ?>