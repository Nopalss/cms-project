<?php

// mengambil data issues report berdasarkan id teknisi
$sql = "
        SELECT *
        FROM issues_report
        WHERE status = 'Pending' 
          AND created_at >= CURDATE()
          AND created_at < CURDATE() + INTERVAL 1 DAY";

$stmt = $pdo->prepare($sql);
$stmt->execute();

$issues_report = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header flex-wrap border-0 pt-6 pb-0">
        <div class="card-title">
            <h3 class="card-label">
                Data Schedule
            </h3>
        </div>
        <div class="card-toolbar">
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline mr-2">
                <button type="button" class="btn btn-light-warning font-weight-bolder" id="btn-issues" data-toggle="modal" data-target="#exampleModalScrollable">
                    <i class="flaticon2-warning"></i>Issues Report
                    <?php if (count($issues_report) > 0): ?>
                        <small class="ml-3 label label-danger mr-2"><?= count($issues_report) ?></small>
                    <?php endif; ?>
                </button>


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
                                    <option value="Pending">Pending</option>
                                    <option value="Rescheduled">Rescheduled</option>
                                    <option value="Cancelled">Cancelled</option>
                                    <option value="Done">Done</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 my-2 my-md-0">
                            <div class="d-flex align-items-center">
                                <label class="mr-3 mb-0 d-none d-md-block">Teknisi:</label>
                                <select class="form-control" id="kt_datatable_search_tech">
                                    <option value="">All</option>
                                    <?php foreach ($technicians as $t): ?>
                                        <option value="<?= $t['tech_id'] ?>"><?= $t['name'] ?></option>
                                    <?php endforeach; ?>
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
        <div class="table-responsive">
            <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
        </div>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->



<!-- modal detail -->
<div class="modal fade" id="detailModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content shadow-lg border-0 rounded-lg">
            <div class="modal-header">
                <h4 class="modal-title"><i class="la la-info-circle text-info"></i> Detail Schedule</h4>
                <button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Schedule ID</div>
                    <div class="col-8" id="detail_id"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Netpay ID</div>
                    <div class="col-8" id="detail_netpayId"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Teknisi</div>
                    <div class="col-8" id="detail_tech"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Tanggal</div>
                    <div class="col-8" id="detail_date"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Job Type</div>
                    <div class="col-8" id="detail_job"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Status</div>
                    <div class="col-8">
                        <div id="detail_status"></div>
                    </div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Location</div>
                    <div class="col-8" id="detail_location"></div>
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