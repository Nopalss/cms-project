<?php
$tech_id = 1;
// Mengambil data scehdule berdasarkan id teknisi
$sql = "SELECT * FROM schedules
        WHERE date = CURDATE()
        AND tech_id = :tech_id
        ORDER BY time ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute([':tech_id' => $tech_id]);
$schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);

// mengambil data issues report berdasarkan id teknisi
$sql = "SELECT *
        FROM issues_report
        WHERE reported_by = :reported_by
          AND created_at >= CURDATE()
          AND created_at < CURDATE() + INTERVAL 1 DAY";

$stmt = $pdo->prepare($sql);
$stmt->execute([':reported_by' => $tech_id]);

$issues_report = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ini buat style
$badgeClasses = [
    'Instalasi'   => 'success',
    'Maintenance' => 'danger',
    'Perbaikan'   => 'warning'
];
$statusClasses = [
    'Pending' => "info",
    'On Progress' => "primary",
    'Rescheduled' => "warning",
    'Cancelled' => "danger",
    'Done' => "success"
];

?>

<div class="row pt-2">
    <div class="col-lg-6">
        <div class="card card-custom  gutter-b">
            <div class="card-header align-items-center border-0 mt-4">
                <h3 class="card-title align-items-start flex-column">
                    <span class="font-weight-bolder text-dark">Today’s Schedule</span>
                </h3>
            </div>
            <div class="card-body pt-4">
                <div class="table-responsive-sm">
                    <table class="table text-sm">
                        <thead>
                            <tr>
                                <th scope="col">Schedule Id</th>
                                <th scope="col">Job Type</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($schedules) > 0): ?>
                                <?php foreach ($schedules as $s): ?>
                                    <tr>
                                        <th scope="row"><?= $s['schedule_id'] ?></th>
                                        <td><?= $s['job_type'] ?></td>
                                        <td><span class="badge badge-pill text-sm badge-<?= $statusClasses[$s['status']] ?>"><?= $s['status'] ?></span></td>
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
                                                        <?php if ($s['status'] == 'Pending' || $s['status'] == 'Rescheduled'): ?>
                                                            <li class="navi-item cursor-pointer">
                                                                <a class="navi-link" href="<?= BASE_URL ?>pages/schedule/issue_report.php?id=<?= $s['schedule_id'] ?>">
                                                                    <span class="navi-icon "><i class="flaticon2-warning text-warning"></i></span>
                                                                    <span class="navi-text">Task Issue Report</span>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                        <li class="navi-item cursor-pointer">
                                                            <a class="navi-link btn-detail2" data-id="<?= $s['schedule_id'] ?>" data-tech="<?= $s['tech_id'] ?>" data-date="<?= $s['date'] ?>" data-job="<?= $s['job_type'] ?>" data-state="<?= $statusClasses[$s['status']] ?>" data-status="<?= $s['status'] ?>" data-location="<?= $s['location'] ?>">
                                                                <span class="navi-icon "><i class="flaticon-eye text-info"></i></span>
                                                                <span class="navi-text">Detail</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td class="text-center text-muted text-weight-bold" colspan="4">Tidak ada schedule yang terdaftar untuk hari ini</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <!--begin::List Widget 9-->
        <div class="card card-custom pb-2 gutter-b">
            <!--begin::Header-->
            <div class="card-header align-items-center border-0 mt-4">
                <h3 class="card-title align-items-start flex-column">
                    <span class="font-weight-bolder text-dark">My Activities</span>
                </h3>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline">
                        <a href="#" class="btn btn-clean btn-hover-light-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="ki ki-bold-more-hor"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                            <!--begin::Navigation-->
                            <ul class="navi navi-hover">
                                <li class="navi-header font-weight-bold py-4">
                                    <span class="font-size-lg">Info Label:</span>
                                    <i class="flaticon2-information icon-md text-muted" data-toggle="tooltip" data-placement="right"></i>
                                </li>
                                <li class="navi-separator mb-3 opacity-70"></li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-text">
                                            <span class="label label-xl label-inline label-light-success">Instalasi</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-text">
                                            <span class="label label-xl label-inline label-light-danger">Maintenance</span>
                                        </span>
                                    </a>
                                </li>
                                <li class="navi-item">
                                    <a href="#" class="navi-link">
                                        <span class="navi-text">
                                            <span class="label label-xl label-inline label-light-warning">Perbaikan</span>
                                        </span>
                                    </a>
                                </li>
                            </ul>
                            <!--end::Navigation-->
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Header-->

            <!--begin::Body-->
            <div class="card-body pt-4">
                <!--begin::Timeline-->
                <?php if (count($schedules) > 0): ?>
                    <div class="timeline timeline-6 mt-3">
                        <?php foreach ($schedules as $s): ?>
                            <?php if ($s['status'] != 'Cancelled'): ?>
                                <!--begin::Item-->
                                <div class="timeline-item align-items-start">
                                    <!--begin::Label-->
                                    <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg"><?= substr($s['time'], 0, 5) ?></div>
                                    <!--end::Label-->

                                    <!--begin::Badge-->
                                    <div class="timeline-badge">
                                        <i class="fa fa-genderless text-<?= $badgeClasses[$s['job_type']]; ?> icon-xl"></i>
                                    </div>
                                    <!--end::Badge-->

                                    <!--begin::Text-->
                                    <div class="font-weight-mormal font-size-lg timeline-content pl-3 ">
                                        <p class="mb-0 btn-detail2 cursor-pointer"
                                            data-id="<?= $s['schedule_id'] ?>"
                                            data-tech="<?= $s['tech_id'] ?>"
                                            data-date="<?= $s['date'] ?>"
                                            data-job="<?= $s['job_type'] ?>"
                                            data-state="<?= $statusClasses[$s['status']] ?>"
                                            data-status="<?= $s['status'] ?>"
                                            data-location="<?= $s['location'] ?>">
                                            <?= $s['job_type'] ?>
                                            <a class="text-muted btn-detail font-size-sm cursor-pointer">
                                                #<?= $s['schedule_id'] ?>
                                            </a>
                                        </p>
                                    </div>
                                    <!--end::Text-->
                                </div>
                                <!--end::Item-->
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="text-center text-muted text-weight-bold">Tidak ada schedule yang terdaftar untuk hari ini</p>
                <?php endif; ?>
                <!--end::Timeline-->
            </div>
            <!--end: Card Body-->
        </div>
        <div class="card">
            <div class="card-header align-items-center border-0 mt-4 pb-2">
                <h3 class="card-title align-items-start flex-column">
                    <span class="font-weight-bolder text-dark">Task Issue Report</span>
                </h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive">
                    <table class="table text-sm">
                        <thead>
                            <tr>
                                <th scope="col">Issue Id</th>
                                <th scope="col">Schedule Id</th>
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
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        <?php else: ?>
                            <tr>
                                <td class="text-center text-muted text-weight-bold" colspan="4">Tidak ada Issue Report</td>
                            </tr>
                        <?php endif; ?>
                    </table>
                </div>
            </div>
        </div>
        <!--end: List Widget 9-->
    </div>

</div>


<!-- modal detail schedule-->
<div class=" modal fade" id="detailModal" tabindex="-1" role="dialog">
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
                <a class="btn btn-warning" id="detail_btn_report">
                    <i class="flaticon2-warning"></i>Task Issue Report
                </a>
                <a class="btn btn-success" id="detail_btn_done">
                    <i class="flaticon2-check-mark"></i> Done
                </a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if (!empty($_SESSION['info'])): ?>
    <script>
        Swal.fire({
            icon: "info",
            title: "Oops! Schedule ini sudah dilaporkan.",
            text: '<?= $_SESSION['info'] ?>',
            confirmButtonText: 'Oke',
            customClass: {
                confirmButton: "btn font-weight-bold btn-outline-warning",
                icon: 'm-auto'
            }

        });
    </script>
<?php unset($_SESSION['info']);
endif; ?>
<?php if (!empty($_SESSION['error'])): ?>
    <script>
        Swal.fire({
            icon: "error",
            title: "Oops! Ada error saat menyimpan data.",
            text: '<?= $_SESSION['error'] ?>',
            confirmButtonText: 'Coba Lagi',
            customClass: {
                title: 'text-danger',
                confirmButton: "btn font-weight-bold btn-outline-danger",
                icon: 'm-auto'
            }

        });
    </script>
<?php unset($_SESSION['error']);
endif; ?>

<?php if (!empty($_SESSION['success'])): ?>
    <script>
        Swal.fire({
            icon: "success",
            title: "Selamat! Proses Berhasil",
            text: '<?= $_SESSION['success'] ?>',
            confirmButtonText: 'Done!',
            customClass: {
                title: 'text-success',
                confirmButton: "btn font-weight-bold btn-outline-success",
                icon: 'm-auto'
            }

        });
    </script>
<?php unset($_SESSION['success']);
endif; ?>