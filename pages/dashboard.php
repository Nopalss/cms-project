<?php
require_once '../includes/config.php';
$_SESSION['menu'] = 'dashboard';
require __DIR__ . '/../includes/header.php';
require __DIR__ . '/../includes/aside.php';
require __DIR__ . '/../includes/navbar.php';

$schedules = [
    [
        "jam" => "09:00",
        "task" => "	New Installation",
        "technician" => "Budi",
    ],
    [
        "jam" => "10:00",
        "task" => "Maintenance Fiber",
        "technician" => "Asep",
    ],
    [
        "jam" => "10:00",
        "task" => "	Upgrade Speed",
        "technician" => "Agus",
    ]
];

$queue_count = $pdo->query("SELECT COUNT(*) AS total FROM queue_scheduling WHERE status = 'Pending'")->fetch(PDO::FETCH_ASSOC);
$ikr_count = $pdo->query("
    SELECT COUNT(*) AS total
    FROM schedules
    WHERE job_type = 'Instalasi'
      AND status NOT IN ('Cancelled','Done')
      AND DATE(`date`) = CURDATE()
")->fetch(PDO::FETCH_ASSOC);
$service_count = $pdo->query("
    SELECT COUNT(*) AS total
    FROM schedules
    WHERE job_type = 'Maintenance'
      AND status NOT IN ('Cancelled','Done')
      AND DATE(`date`) = CURDATE()
")->fetch(PDO::FETCH_ASSOC);
$dismantle_count = $pdo->query("
    SELECT COUNT(*) AS total
    FROM schedules
    WHERE job_type = 'Dismantle'
      AND status NOT IN ('Cancelled','Done')
      AND DATE(`date`) = CURDATE()
")->fetch(PDO::FETCH_ASSOC);
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
                        Dashboard </h5>
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
            <div class="row d-flex align-items-stretch">
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="bg-white  m-2 p-2 d-flex rounded shadow-sm h-100 align-items-center">
                        <div class=" w-25 p-3 text-center rounded font-weight-bold d-flex justify-content-center align-items-center mr-5">
                            <i class="flaticon2-hourglass-1 text-primary icon-2x"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="text-muted mb-2">Queue Schedules</p>
                            <h3><?= $queue_count['total'] ?></h3>
                        </div>
                    </div>
                </div>
                <!-- card -->
                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="bg-white m-2 p-2 d-flex rounded shadow-sm h-100 align-items-center">
                        <div class="w-25 p-3 text-center rounded font-weight-bold d-flex justify-content-center align-items-center mr-5">
                            <span class="svg-icon svg-icon-success svg-icon-2x"><!--begin::Svg Icon | path:C:\wamp64\www\keenthemes\themes\metronic\theme\html\demo1\dist/../src/media/svg/icons\Devices\Router1.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M15,15 L15,10 C15,9.44771525 15.4477153,9 16,9 C16.5522847,9 17,9.44771525 17,10 L17,15 L20,15 C21.1045695,15 22,15.8954305 22,17 L22,19 C22,20.1045695 21.1045695,21 20,21 L4,21 C2.8954305,21 2,20.1045695 2,19 L2,17 C2,15.8954305 2.8954305,15 4,15 L15,15 Z M5,17 C4.44771525,17 4,17.4477153 4,18 C4,18.5522847 4.44771525,19 5,19 L10,19 C10.5522847,19 11,18.5522847 11,18 C11,17.4477153 10.5522847,17 10,17 L5,17 Z" fill="#000000" />
                                        <path d="M20.5,7.7155722 L19.2133304,8.85714286 C18.425346,7.82897283 17.2569914,7.22292937 15.9947545,7.22292937 C14.7366498,7.22292937 13.571742,7.82497398 12.7836854,8.84737587 L11.5,7.70192243 C12.6016042,6.27273291 14.2349886,5.42857143 15.9947545,5.42857143 C17.7603123,5.42857143 19.3985009,6.27832502 20.5,7.7155722 Z M23.5,5.46053062 L22.1362873,6.57142857 C20.629466,4.78945909 18.4012066,3.73944576 15.9963045,3.73944576 C13.5947271,3.73944576 11.3692392,4.78653417 9.8623752,6.56427829 L8.5,5.45180053 C10.340077,3.28094376 13.0626024,2 15.9963045,2 C18.934073,2 21.6599771,3.28451636 23.5,5.46053062 Z" fill="#000000" fill-rule="nonzero" opacity="0.3" />
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="text-muted mb-2">Active Installations</p>
                            <h3><?= $ikr_count['total'] ?></h3>
                        </div>
                    </div>
                </div>

                <!-- end card -->

                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="bg-white m-2 p-2 d-flex rounded shadow-sm h-100 align-items-center">
                        <div class=" w-25 p-3 text-center rounded font-weight-bold d-flex justify-content-center align-items-center mr-5">
                            <i class="fas fa-tools icon-2x text-warning"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="text-muted mb-2">Active Maintenance </p>
                            <h3><?= $service_count['total'] ?></h3>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3 mb-4">
                    <div class="bg-white  m-2 p-2 d-flex rounded shadow-sm h-100 align-items-center">
                        <div class=" w-25 p-3 text-center rounded font-weight-bold d-flex justify-content-center align-items-center mr-5">
                            <i class="fas fa-trash-restore icon-2x text-danger"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <p class="text-muted mb-2">Active Dismantle</p>
                            <h3><?= $dismantle_count['total'] ?></h3>
                        </div>
                    </div>
                </div>
                <!-- end card -->

                <!-- begin:: card schedule -->
                <div class="col-xl-9 mt-10">
                    <div class="card shadow">
                        <div class="card-header pb-1 ">
                            <h3 class="card-title mb-2">
                                <a class="card-label text-dark">Monthly Reports <?= date('Y') ?></a>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chart_2"></div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 mt-10 mb-7">
                    <div class="card shadow">
                        <div class="card-header pb-1 ">
                            <h3 class="card-title mb-2">
                                <a class="card-label text-dark">Reports <?= date('F') ?></a>
                            </h3>
                        </div>
                        <div class="card-body pt-2">
                            <div id="chart_3"></div>
                        </div>
                    </div>
                </div>
                <!-- end: card schedule -->
                <!-- begin:: card report -->
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>


<?php
require __DIR__ . '/../includes/footer.php';
?>