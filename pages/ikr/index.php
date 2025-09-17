<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'ikr';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
$statusIssueClasses = [
    'Pending' => "info",
    'Approved' => "success",
    'Rejected' => "danger",
];

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
                        IKR </h5>

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
                            Data IKR
                        </h3>
                    </div>
                    <div class="card-toolbar">
                        <!--begin::Dropdown-->
                        <div class="dropdown dropdown-inline mr-2">
                            <!-- <button type="button" class="btn btn-light-warning font-weight-bolder" id="btn-issues" data-toggle="modal" data-target="#exampleModalScrollable">
                                <i class="flaticon2-warning"></i>Issues Report
                                <?php if (count($issues_report) > 0): ?>
                                    <small class="ml-3 label label-danger mr-2"><?= count($issues_report) ?></small>
                                <?php endif; ?>
                            </button> -->


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
                        <button type="button" class="btn btn-primary font-weight-bolder" data-toggle="modal" data-target="#staticBackdrop">
                            <span class="svg-icon svg-icon-md"><!--begin::Svg Icon | path:assets/media/svg/icons/Design/Flatten.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <circle fill="#000000" cx="9" cy="15" r="6" />
                                        <path d="M8.8012943,7.00241953 C9.83837775,5.20768121 11.7781543,4 14,4 C17.3137085,4 20,6.6862915 20,10 C20,12.2218457 18.7923188,14.1616223 16.9975805,15.1987057 C16.9991904,15.1326658 17,15.0664274 17,15 C17,10.581722 13.418278,7 9,7 C8.93357256,7 8.86733422,7.00080962 8.8012943,7.00241953 Z" fill="#000000" opacity="0.3" />
                                    </g>
                                </svg><!--end::Svg Icon--></span> New IKR
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
                    <div class="datatable datatable-bordered datatable-head-custom" id="kt_datatable"></div>
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

            <!-- Modal issues report -->
            <div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-labelledby="staticBackdrop" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Issues Report</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <i aria-hidden="true" class="ki ki-close"></i>
                            </button>
                        </div>
                        <div class="modal-body" style="height: 300px;">
                            <div class="table-responsive-xl">
                                <table class="table text-sm">
                                    <thead>
                                        <tr>
                                            <th scope="col">Issue Id</th>
                                            <th scope="col">Schedule Id</th>
                                            <th scope="col">Reported By</th>
                                            <th scope="col">Issue Type</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <?php if (count($issues_report) > 0): ?>
                                        <tbody>
                                            <?php foreach ($issues_report as $i): ?>
                                                <tr>
                                                    <th scope="row"><?= $i['issue_id'] ?></th>
                                                    <td><?= $i['schedule_id'] ?></td>
                                                    <td><?= $i['reported_by'] ?></td>
                                                    <td><?= $i['issue_type'] ?></td>
                                                    <td class="text-sm"><span class="badge badge-pill badge-<?= $statusIssueClasses[$i['status']] ?>"><?= $i['status'] ?></span></td>
                                                    <td>
                                                        <div class="dropdown dropdown-inline">
                                                            <a href="javascript:;" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" data-toggle="dropdown">
                                                                <span class="svg-icon svg-icon-md">
                                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                                                            <rect x="0" y="0" width="24" height="24" />
                                                                            <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000" />
                                                                        </g>
                                                                    </svg>
                                                                </span>
                                                            </a>
                                                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                                                                <ul class="navi flex-column navi-hover py-2">
                                                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">
                                                                        Choose an action:
                                                                    </li>
                                                                    <li class="navi-item cursor-pointer">
                                                                        <a
                                                                            class="navi-link btn-detail3"
                                                                            data-id="<?= $i['issue_id'] ?>"
                                                                            data-schedule="<?= $i['schedule_id'] ?>"
                                                                            data-reported="<?= $i['reported_by'] ?>"
                                                                            data-type="<?= $i['issue_type'] ?>"
                                                                            data-desc="<?= $i['description'] ?>"
                                                                            data-date="<?= $i['created_at'] ?>"
                                                                            data-status="<?= $i['status'] ?>"
                                                                            data-state="<?= $statusIssueClasses[$i['status']] ?>">

                                                                            <span class="navi-icon "><i class="flaticon-eye text-info"></i></span>
                                                                            <span class="navi-text">Detail</span>
                                                                        </a>
                                                                    </li>
                                                                    <li class="navi-item cursor-pointer">
                                                                        <a class="navi-link btn-approved" onclick="confirmApproved('<?= $i['issue_id'] ?>','<?= $i['schedule_id'] ?>')">
                                                                            <span class="navi-icon "><i class="flaticon2-check-mark text-success"></i></span>
                                                                            <span class="navi-text">Approved</span>
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    <?php else: ?>
                                        <tr>
                                            <td class="text-center text-muted text-weight-bold" colspan="6">Tidak ada Issue Report</td>
                                        </tr>
                                    <?php endif; ?>
                                </table>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
                            <!-- <button type="button" class="btn btn-primary font-weight-bold">Save changes</button> -->
                        </div>
                    </div>
                </div>
            </div>

        </div>
        <!-- end::Container -->
    </div>
</div>
<!-- end::entry -->
<!-- modal detail issue report-->
<div class=" modal fade" id="detailModalIssue" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content shadow-lg border-0 rounded-lg">
            <div class="modal-header">
                <h4 class="modal-title"><i class="la la-info-circle text-info"></i> Detail issue report</h4>
                <button type="button" class="close text-danger" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Issue ID</div>
                    <div class="col-8" id="detail_idIssue"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Schedule ID</div>
                    <div class="col-8" id="detail_schedule"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Reported</div>
                    <div class="col-8" id="detail_reported"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Issue Type</div>
                    <div class="col-8" id="detail_issue"></div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Created At</div>
                    <div class="col-8">
                        <div id="detail_dateIssue"></div>
                    </div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Status Report</div>
                    <div class="col-8">
                        <div id="detail_stat"></div>
                    </div>
                </div>
                <div class="row mb-2 pl-2">
                    <div class="col-4 font-weight-bold">Description</div>
                    <div class="col-8" id="detail_desc"></div>
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
require __DIR__ . '/../../includes/footer.php';
?>