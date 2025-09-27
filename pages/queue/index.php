<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'queue';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

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
                        Schedule Queue </h5>
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
                            Data Schedule Queue
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-light-success font-weight-bolder" id="btn-issues" data-toggle="modal" data-target="#exampleModalScrollable">
                            <i class="flaticon2-warning"></i>Schedule Now
                            <small id="scheduleNow" class="ml-3 label label-danger mr-2" style="display:none;"></small>
                        </button>
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
                                            <label class="mr-3 mb-0 d-none d-md-block">Type:</label>
                                            <select class="form-control" id="kt_datatable_search_type">
                                                <option value="">All</option>
                                                <option value="Install">Install</option>
                                                <option value="Maintenance">Maintenance</option>
                                                <option value="Dismantle">Dismantle</option>
                                            </select>
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
</div>
<!-- end::entry -->

<!-- Modal issues report -->
<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Schedule Now</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body" style="height: 300px;">
                <ul class="nav nav-light-success nav-bold nav-pills">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_4_1">
                            <span class="nav-text">All</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_2">
                            <span class="nav-text">Instalasi</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_3">
                            <span class="nav-text">Maintenance</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#kt_tab_pane_4_4">
                            <span class="nav-text">Dismantle</span>
                        </a>
                    </li>
                </ul>

                <div class="tab-content mt-3">
                    <!-- All -->
                    <div class="tab-pane fade show active" id="kt_tab_pane_4_1" role="tabpanel">
                        <div class="table-responsive-xl">
                            <table class="table text-sm">
                                <thead>
                                    <tr>
                                        <th>Queue ID</th>
                                        <th>Type Queue</th>
                                        <th>Request ID</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-scheduleNowAll"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Install -->
                    <div class="tab-pane fade" id="kt_tab_pane_4_2" role="tabpanel">
                        <div class="table-responsive-xl">
                            <table class="table text-sm">
                                <thead>
                                    <tr>
                                        <th>Queue ID</th>
                                        <th>Type Queue</th>
                                        <th>Request ID</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-scheduleNowInstall"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Maintenance -->
                    <div class="tab-pane fade" id="kt_tab_pane_4_3" role="tabpanel">
                        <div class="table-responsive-xl">
                            <table class="table text-sm">
                                <thead>
                                    <tr>
                                        <th>Queue ID</th>
                                        <th>Type Queue</th>
                                        <th>Request ID</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-scheduleNowMaintenance"></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Dismantle -->
                    <div class="tab-pane fade" id="kt_tab_pane_4_4" role="tabpanel">
                        <div class="table-responsive-xl">
                            <table class="table text-sm">
                                <thead>
                                    <tr>
                                        <th>Queue ID</th>
                                        <th>Type Queue</th>
                                        <th>Request ID</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="table-scheduleNowDismantle"></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
require __DIR__ . '/../../includes/footer.php';
?>