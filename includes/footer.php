<?php

require_once __DIR__ . '/config.php';
?>


<!--begin::Footer-->
<div class="footer bg-white py-4 d-flex flex-lg-column " id="kt_footer">
    <!--begin::Container-->
    <div class=" container-fluid  d-flex flex-column flex-md-row align-items-center justify-content-end">
        <!--begin::Copyright-->
        <div class="text-dark order-2 order-md-1">
            <span class="text-muted font-weight-bold mr-2"><?= date('Y') ?>&copy;</span>
            <a href="" target="_blank" class="text-dark-75 text-hover-primary">Jabbar23</a>
        </div>
        <!--end::Copyright-->

        <!--begin::Nav-->

        <!--end::Nav-->
    </div>
    <!--end::Container-->
</div>
<!--end::Footer-->
</div>
<!--end::Wrapper-->
</div>
<!--end::Page-->
</div>
<!--end::Main-->
<!-- end::Content -->
<!-- begin::User Panel-->
<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
    <!--begin::Header-->
    <div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
        <h3 class="font-weight-bold m-0">
            User Profile
        </h3>
        <a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
            <i class="ki ki-close icon-xs text-muted"></i>
        </a>
    </div>
    <!--end::Header-->

    <!--begin::Content-->
    <div class="offcanvas-content pr-5 mr-n5">
        <!--begin::Header-->
        <div class="d-flex align-items-center mt-5">
            <div class="symbol symbol-100 mr-5">
                <div class="symbol-label" style="background-image:url('<?= BASE_URL ?>assets/media/users/blank.png')"></div>
                <i class="symbol-badge bg-success"></i>
            </div>
            <div class="d-flex flex-column">
                <a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
                    <?= $_SESSION['name'] ?>
                </a>
                <div class="text-muted mt-1">
                    <?= $_SESSION['role'] ?>
                </div>
                <div class="navi mt-2">

                    <a href="<?= BASE_URL . "includes/signout.php" ?>" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Sign Out</a>
                </div>
            </div>
        </div>
        <!--end::Header-->

        <!--begin::Separator-->
        <div class="separator separator-dashed mt-8 mb-5"></div>
        <!--end::Separator-->

        <!--begin::Nav-->
        <div class="navi navi-spacer-x-0 p-0">
            <!--begin::Item-->
            <a href="custom/apps/user/profile-1/personal-information.html" class="navi-item">
                <div class="navi-link">
                    <div class="symbol symbol-40 bg-light mr-3">
                        <div class="symbol-label">
                            <span class="svg-icon svg-icon-md svg-icon-success"><!--begin::Svg Icon | path:assets/media/svg/icons/General/Notification2.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z" fill="#000000" />
                                        <circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                        </div>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            My Profile
                        </div>
                        <div class="text-muted">
                            Account settings and more
                            <span class="label label-light-danger label-inline font-weight-bold">update</span>
                        </div>
                    </div>
                </div>
            </a>
            <!--end:Item-->

            <!--begin::Item-->
            <a href="custom/apps/userprofile-1/overview.html" class="navi-item">
                <div class="navi-link">
                    <div class="symbol symbol-40 bg-light mr-3">
                        <div class="symbol-label">
                            <span class="svg-icon svg-icon-md svg-icon-primary"><!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-opened.svg--><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <rect x="0" y="0" width="24" height="24" />
                                        <path d="M6,2 L18,2 C18.5522847,2 19,2.44771525 19,3 L19,12 C19,12.5522847 18.5522847,13 18,13 L6,13 C5.44771525,13 5,12.5522847 5,12 L5,3 C5,2.44771525 5.44771525,2 6,2 Z M7.5,5 C7.22385763,5 7,5.22385763 7,5.5 C7,5.77614237 7.22385763,6 7.5,6 L13.5,6 C13.7761424,6 14,5.77614237 14,5.5 C14,5.22385763 13.7761424,5 13.5,5 L7.5,5 Z M7.5,7 C7.22385763,7 7,7.22385763 7,7.5 C7,7.77614237 7.22385763,8 7.5,8 L10.5,8 C10.7761424,8 11,7.77614237 11,7.5 C11,7.22385763 10.7761424,7 10.5,7 L7.5,7 Z" fill="#000000" opacity="0.3" />
                                        <path d="M3.79274528,6.57253826 L12,12.5 L20.2072547,6.57253826 C20.4311176,6.4108595 20.7436609,6.46126971 20.9053396,6.68513259 C20.9668779,6.77033951 21,6.87277228 21,6.97787787 L21,17 C21,18.1045695 20.1045695,19 19,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,6.97787787 C3,6.70173549 3.22385763,6.47787787 3.5,6.47787787 C3.60510559,6.47787787 3.70753836,6.51099993 3.79274528,6.57253826 Z" fill="#000000" />
                                    </g>
                                </svg><!--end::Svg Icon--></span>
                        </div>
                    </div>
                    <div class="navi-text">
                        <div class="font-weight-bold">
                            My Tasks
                        </div>
                        <div class="text-muted">
                            latest tasks and schedule
                        </div>
                    </div>
                </div>
            </a>
            <!--end:Item-->
        </div>
        <!--end::Nav-->
    </div>
    <!--end::Content-->
</div>
<!-- end::User Panel-->
<!-- sweetalert -->
<script>
    var HOST_URL = "<?= BASE_URL ?>";
</script>
<?php if (isset($_SESSION['alert'])): ?>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        Swal.fire({
            icon: "<?= $_SESSION['alert']['icon'] ?>",
            title: "<?= $_SESSION['alert']['title'] ?>",
            text: "<?= $_SESSION['alert']['text'] ?>",
            confirmButtonText: "<?= $_SESSION['alert']['button'] ?> ",
            customClass: {
                confirmButton: "btn font-weight-bold btn-<?= $_SESSION['alert']['style'] ?>",
                icon: "m-auto"
            }
        });
    </script>
    <?php unset($_SESSION['alert']); ?>
<?php endif; ?>

<!--begin::Global Config(global config for global JS scripts)-->
<script>
    var KTAppSettings = {
        "breakpoints": {
            "sm": 576,
            "md": 768,
            "lg": 992,
            "xl": 1200,
            "xxl": 1400
        },
        "colors": {
            "theme": {
                "base": {
                    "white": "#ffffff",
                    "primary": "#3699FF",
                    "secondary": "#E5EAEE",
                    "success": "#1BC5BD",
                    "info": "#8950FC",
                    "warning": "#FFA800",
                    "danger": "#F64E60",
                    "light": "#E4E6EF",
                    "dark": "#181C32"
                },
                "light": {
                    "white": "#ffffff",
                    "primary": "#E1F0FF",
                    "secondary": "#EBEDF3",
                    "success": "#C9F7F5",
                    "info": "#EEE5FF",
                    "warning": "#FFF4DE",
                    "danger": "#FFE2E5",
                    "light": "#F3F6F9",
                    "dark": "#D6D6E0"
                },
                "inverse": {
                    "white": "#ffffff",
                    "primary": "#ffffff",
                    "secondary": "#3F4254",
                    "success": "#ffffff",
                    "info": "#ffffff",
                    "warning": "#ffffff",
                    "danger": "#ffffff",
                    "light": "#464E5F",
                    "dark": "#ffffff"
                }
            },
            "gray": {
                "gray-100": "#F3F6F9",
                "gray-200": "#EBEDF3",
                "gray-300": "#E4E6EF",
                "gray-400": "#D1D3E0",
                "gray-500": "#B5B5C3",
                "gray-600": "#7E8299",
                "gray-700": "#5E6278",
                "gray-800": "#3F4254",
                "gray-900": "#181C32"
            }
        },
        "font-family": "Poppins"
    };
</script>
<!--end::Global Config-->

<!--begin::Global Theme Bundle(used by all pages)-->
<script src="<?= BASE_URL ?>assets/plugins/global/plugins.bundle.js"></script>
<script src="<?= BASE_URL ?>assets/plugins/custom/prismjs/prismjs.bundle.js"></script>
<script src="<?= BASE_URL ?>assets/js/scripts.bundle.js"></script>
<!--end::Global Theme Bundle-->
<?php if ($_SESSION['menu'] == "schedule"): ?>
    <script src="<?= BASE_URL ?>assets/js/pages/crud/ktdatatable/base/schedules.js"></script>
    <script src="assets/js/pages/features/cards/tools.js"></script>
<?php endif; ?>
<?php if ($_SESSION['menu'] == "issue report"): ?>
    <script src="<?= BASE_URL ?>assets/js/pages/crud/ktdatatable/base/issues-report.js"></script>
<?php endif; ?>
<?php if ($_SESSION['menu'] == "ikr"): ?>
    <script src="<?= BASE_URL ?>assets/js/pages/crud/ktdatatable/base/ikr.js"></script>
<?php endif; ?>
<?php if ($_SESSION['menu'] == "registrasi"): ?>
    <script src="<?= BASE_URL ?>assets/js/pages/crud/ktdatatable/base/registrasi.js"></script>
<?php endif; ?>
<?php if ($_SESSION['menu'] == "request ikr"): ?>
    <script src="<?= BASE_URL ?>assets/js/pages/crud/ktdatatable/base/request_ikr.js"></script>
<?php endif; ?>
<?php if ($_SESSION['menu'] == "request maintenance"): ?>
    <script src="<?= BASE_URL ?>assets/js/pages/crud/ktdatatable/base/request_maintenance.js"></script>
<?php endif; ?>
<?php if ($_SESSION['menu'] == "queue"): ?>
    <script src="<?= BASE_URL ?>assets/js/pages/crud/ktdatatable/base/queue.js"></script>
<?php endif; ?>

<script src="<?= BASE_URL ?>assets/js/pages/crud/forms/widgets/bootstrap-timepicker.js"></script>
<!--end::Page Scripts-->
<!--begin::Page Scripts(used by this page)-->
<script src="<?= BASE_URL ?>assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js"></script>
<script>
    //  delete template
    function confirmDeleteTemplate(id, url, title = "Yakin mau hapus?", text = "Data akan dihapus permanen!") {
        Swal.fire({
            title: title,
            text: text,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = `${HOST_URL}${url}?id=${id}`;
            }
        });
    }

    // registrasi
    $(document).on("click", ".btn-detail-registrasi", function() {
        $("#detail_registrasiId").text($(this).data("id"));
        $("#detail_name").text($(this).data("name"));
        $("#detail_location").text($(this).data("location"));
        $("#detail_phone").text($(this).data("phone"));
        $("#detail_paketInternet").text($(this).data("paket") + ' mbps');
        const color = {
            "Verified": 'success',
            "Unverified": 'danger'
        }
        $("#detail_isVerified").text($(this).data("verified")).addClass(`badge badge-pill text-weight-bold badge-${color[$(this).data("verified")]}`);
        const datetime = $(this).data("schedule");
        const date = new Date(datetime.replace(" ", "T"));

        // Bagian tanggal → pakai locale Indonesia
        const tanggal = date.toLocaleDateString("id-ID", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric"
        });

        // Bagian jam → pakai locale Inggris (pemisah :)
        const waktu = date.toLocaleTimeString("en-GB", {
            hour: "2-digit",
            minute: "2-digit",
        });
        $("#detail_requestSchedule").text(`${tanggal}`);
        $("#detail_requestJam").text(`${waktu}`);

        $("#detailModalRegistrasi").modal("show");
    });

    function confirmDeleteRegistrasi(registrasiId) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data Registrasi akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = HOST_URL + "controllers/registrasi/delete.php?id=" + registrasiId;
            }
        });
    }


    // request ikr
    $(document).ready(function() {

        $('#registrasi_id').on('change', function() {
            let id = $(this).val();
            if (id) {
                $.ajax({
                    url: '<?= BASE_URL ?>api/get_register.php',
                    type: 'GET',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (Object.keys(data).length > 0) {
                            $('#name').val(data.name);
                            $('#registrasi_id2').val(data.registrasi_id);
                            $('#phone').val(data.phone);
                            $('#paket_internet').val(data.paket_internet).selectpicker('refresh');;
                            $('#is_verified').val(data.is_verified).selectpicker('refresh');;
                            $('#location').val(data.location);
                            let schedule = data.request_schedule ? data.request_schedule.replace(' ', 'T').substring(0, 16) : '';
                            $('#request_schedule').val(schedule);
                            $('#jadwal_pemasangan').val(schedule);
                        } else {
                            // reset kalau tidak ada
                            $('#name, #phone, #paket_internet, #location, #request_schedule').val('');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + status + " - " + error);
                    }
                });
            } else {
                $('#name, #phone, #paket_internet, #location, #request_schedule').val('');
            }
        });
        $('#jadwal_pemasangan, #request_schedule').on('change', function() {
            let date = $(this).val();
            $('#request_schedule').val(date);
            $('#jadwal_pemasangan').val(date);
        })
    });
    // modal detail rikr
    $(document).on("click", ".btn-detail-rikr", function() {
        $("#detail_rikrId").text($(this).data("rikr-id"));
        $("#detail_netpayId").text($(this).data("netpay-id"));
        $("#detail_registrasiId").text($(this).data("registrasi-id"));
        $("#detail_status").text($(this).data("status")).addClass(`font-weight-bold text-${$(this).data("state")}`);
        const datetime = $(this).data("jadwal");
        const date = new Date(datetime.replace(" ", "T"));
        // Tanggal → format Indonesia
        const tanggal = date.toLocaleDateString("id-ID", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric"
        });

        // Jam → format 24 jam dengan pemisah :
        const waktu = date.toLocaleTimeString("en-GB", {
            hour: "2-digit",
            minute: "2-digit",
        });

        $("#detail_jadwal").text(tanggal);
        $("#detail_jam").text(`${waktu} WIB`);
        $("#detail_catatan").text($(this).data("catatan"));
        $("#detail_requestBy").text($(this).data("request-by"));
        console.log($(this).data());
        $("#detailModalRIKR").modal("show");
    });

    // notifikasi untuk menambahkan rikr
    function updateUnverifiedCount() {
        $.ajax({
            url: "<?= BASE_URL ?>api/get_unverified_register.php",
            method: "GET",
            dataType: "json",
            success: function(result) {
                if (result.status === "success") {
                    if (result.total > 0) {
                        $("#unverifiedCount")
                            .text(result.total)
                            .show();
                    } else {
                        $("#unverifiedCount").hide();
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // load pertama kali
    updateUnverifiedCount();

    // ulangi tiap 10 detik
    setInterval(updateUnverifiedCount, 10000);


    // deledet rikr
    function confirmDeleteRIKR(rikrId) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = HOST_URL + "controllers/request/ikr/delete.php?id=" + rikrId;
            }
        });
    }

    // rm 
    // get customer
    function getCustomer() {
        $.ajax({
            url: "<?= BASE_URL ?>api/get_customer.php",
            method: "POST",
            data: {
                netpay_id: $("#netpay_id").val()
            },
            dataType: "json",
            success: function(res) {
                if (res.data) {
                    $("#data-netpay").text(res.data.netpay_id);
                    $("#data-name").text(res.data.name);
                    $("#data-phone").text(res.data.phone);
                    $("#data-paket").text(`${res.data.paket_internet} Mbps`);
                    $("#data-location").text(res.data.location);
                }

            }
        })
    }
    $("#netpay_id").on("change", getCustomer);

    // modal detail rm
    $(document).on("click", ".btn-detail-rm", function() {
        $("#detail_RmId").text($(this).data("rm-id"));
        $("#detail_netpayId").text($(this).data("netpay-id"));
        $("#detail_type").text($(this).data("type"));
        $("#detail_status").text($(this).data("status")).addClass(`font-weight-bold text-${$(this).data("state")}`);
        // const datetime = $(this).data("jadwal");
        // const date = new Date(datetime.replace(" ", "T"));
        // // Tanggal → format Indonesia
        // const tanggal = date.toLocaleDateString("id-ID", {
        //     weekday: "long",
        //     day: "numeric",
        //     month: "long",
        //     year: "numeric"
        // });

        // // Jam → format 24 jam dengan pemisah :
        // const waktu = date.toLocaleTimeString("en-GB", {
        //     hour: "2-digit",
        //     minute: "2-digit",
        // });

        // $("#detail_jadwal").text(tanggal);
        // $("#detail_jam").text(`${waktu} WIB`);
        $("#detail_catatan").text($(this).data("deskripsi"));
        $("#detail_requestBy").text($(this).data("request-by"));
        console.log($(this).data());
        $("#detailModalRm").modal("show");
    });

    // Schedule
    // count scheduleNow
    function scheduleNowCount() {
        $.ajax({
            url: "<?= BASE_URL ?>api/get_scheduleNow_count.php",
            method: "GET",
            dataType: "json",
            success: function(result) {
                if (result.status === "success") {
                    // update badge total
                    if (result.total > 0) {
                        $("#scheduleNow").text(result.total).show();
                    } else {
                        $("#scheduleNow").hide();
                    }

                    // list kategori yang mau ditampilkan
                    const categories = {
                        data: "#table-scheduleNowAll",
                        install: "#table-scheduleNowInstall",
                        maintenance: "#table-scheduleNowMaintenance",
                        dismantle: "#table-scheduleNowDismantle"
                    };

                    // looping setiap kategori
                    Object.keys(categories).forEach((key) => {
                        const target = $(categories[key]); // definisikan dulu di luar if
                        target.empty();

                        if (result[key] && result[key].length > 0) {
                            result[key].forEach((item) => {
                                target.append(`
                                    <tr>
                                        <td class="align-middle text-center">${item.queue_id}</td>
                                        <td class="align-middle text-center">${item.type_queue}</td>
                                        <td class="align-middle text-center">${item.request_id}</td>
                                        <td class="align-middle text-center">
                                            <span class="badge badge-pill badge-info">${item.status}</span>
                                        </td>
                                        <td class="align-middle text-center">${item.created_at}</td>
                                        <td class="align-middle text-center">
                                            <form action="${HOST_URL}pages/schedule/create.php" method="post">
                                                <span>
                                                    <input type="hidden" name="type_queue" value="${item.type_queue}">
                                                    <button type="submit" name="id" class="btn btn-primary border-0 btn-detail-rikr" value="${item.queue_id}">
                                                        <span class="navi-icon"><i class="flaticon-calendar-with-a-clock-time-tools"></i></span>
                                                        <span class="navi-text">Schedule Now</span>
                                                    </button>
                                                </span>
                                            </form>
                                        </td>
                                    </tr>
                                `);
                            });
                        } else {
                            target.append(`
                                    <tr>
                                        <td colspan="6" class="text-center">
                                            <p class="text-muted font-weight-bold mb-0">Tidak ada antrian</p>
                                        </td>
                                    </tr>
                                `);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("AJAX Error:", error);
            }
        });
    }

    // load pertama kali
    scheduleNowCount();
    setInterval(scheduleNowCount, 10000);


    // get tanggal 
    $("#date, #tech_id").on("change", getJadwalTeknisi);
    $(document).ready(function() {
        getJadwalTeknisi();
        getCustomer()
    });

    function getJadwalTeknisi() {
        let date = $("#date").val();
        let tech_id = $("#tech_id").val();

        if (date && tech_id) {
            $.ajax({
                url: "<?= BASE_URL ?>api/get_jadwal_teknisi.php",
                method: "POST",
                data: {
                    date: date,
                    tech_id: tech_id
                },
                dataType: "json",
                success: function(res) {
                    // kosongkan time & timeline setiap kali request
                    $("#time").empty().append('<option value="">-- pilih Jam --</option>');
                    $("#timeline").empty();
                    let jam = <?= isset($row['time']) ? json_encode(substr($row['time'], 0, 5)) : 'null' ?>;
                    console.log(jam);
                    // === isi select jam ===
                    if (res.jamKosong && res.jamKosong.length > 0) {
                        if (jam) {
                            $("#time").append(`<option value = "${jam}" selected>${jam}</option>`);
                        }
                        res.jamKosong.forEach(function(time) {
                            $("#time").append(`<option value = "${time}">${time}</option>`);
                        });
                    } else {
                        $("#time").append('<option value="">Tidak ada jam kosong</option>');
                    }

                    // === isi timeline ===
                    if (res.jadwal && res.jadwal.length > 0) {
                        const badgeClasses = {
                            'Instalasi': 'success',
                            'Maintenance': 'warning',
                            'dismantle': 'danger'
                        };
                        const statusClasses = {
                            'Pending': "info",
                            'On Progress': "primary",
                            'Rescheduled': "warning",
                            'Cancelled': "danger",
                            'Done': "success"
                        };

                        res.jadwal.forEach((jadwal) => {
                            const badgeClass = badgeClasses[jadwal['job_type']] || "secondary";
                            const statusClass = statusClasses[jadwal['status']] || "dark";

                            $("#timeline").append(`
                            <div class="timeline-item align-items-start">
                                <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg">
                                    ${jadwal.time.substr(0, 5)}
                                </div>
                                <!--begin::Badge-->
                                <div class="timeline-badge">
                                    <i class="fa fa-genderless text-${badgeClass} icon-xl"></i>
                                </div>
                                <!--end::Badge-->
                                <!--begin::Text-->
                                <div class="font-weight-normal font-size-lg timeline-content pl-3">
                                    <p class="mb-0 btn-detail2 cursor-pointer"
                                        data-id="${jadwal['schedule_id']}"
                                        data-tech="${jadwal['tech_id']}"
                                        data-netpay="${jadwal['netpay_id']}"
                                        data-date="${jadwal['date']}"
                                        data-time="${jadwal['time'].substr(0, 5)}"
                                        data-job="${jadwal['job_type']}"
                                        data-state="${statusClass}"
                                        data-status="${jadwal['status']}"
                                        data-location="${jadwal['location']}">
                                        ${jadwal['job_type']}
                                        <a class="text-muted btn-detail font-size-sm cursor-pointer">
                                            #${jadwal['schedule_id']}
                                        </a>
                                    </p>
                                </div>
                                <!--end::Text-->
                            </div>
                        `);
                        });

                    } else {
                        $("#timeline").append(
                            `<p class="text-center text-muted font-weight-bold">
                            Tidak ada schedule yang terdaftar untuk hari ini
                        </p>`
                        );
                    }

                    $('#time').selectpicker('refresh');
                }
            });
        }
    }

    // modal detail schedule
    $(document).on("click", ".btn-detail, .btn-detail2", function() {
        $("#detail_id").text($(this).data("id"));
        $("#detail_tech").text($(this).data("tech"));

        const date = new Date($(this).data("date"));
        const options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        $("#detail_date").text(date.toLocaleDateString('id-ID', options));

        $("#detail_job").text($(this).data("job"));
        $("#detail_status")
            .removeClass()
            .addClass(`badge badge-pill badge-${$(this).data("state")}`)
            .text($(this).data("status"));

        $("#detail_location").text($(this).data("location"));
        $("#detail_netpayId").text($(this).data("netpay"));

        // hanya untuk btn-detail2 → kasih link report

        if ($(this).hasClass("btn-detail2")) {
            $("#detail_btn_report").attr(
                "href", `${HOST_URL}pages/schedule/issue_report.php?id = ${$(this).data("id")}`
            )
            if ($(this).data("status") == "Cancelled" || $(this).data("status") == "Done") {
                $("#detail_btn_report").addClass('d-none');
                $("#detail_btn_done").addClass('d-none');
            }
            if ($(this).data("status") == "Pending" || $(this).data("status") == "Rescheduled") {
                $("#detail_btn_report").removeClass('d-none');
                $("#detail_btn_done").removeClass('d-none');
            }
            $("#detail_btn_done").attr(
                "href", `${HOST_URL}pages/ikr/create.php?id=${$(this).data("id")}`
            )
        }
        $("#detailModal").modal("show");
    });

    function confirmDelete(scheduleId) {
        Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data schedule akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = HOST_URL + "controllers/schedules/delete.php?id=" + scheduleId;
            }
        });
    }


    function confirmApproved(issueId, scheduleId) {
        console.log(Swal.version)
        Swal.fire({
            title: 'Apa yang harus dilakukan?',
            text: "Schedule ini bisa dibatalkan atau dijadwalkan ulang",
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Confirm',
            confirmButtonColor: '#d33',
            cancelButtonColor: '#ffc800',
            cancelButtonText: 'Reschedule',
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = HOST_URL + "controllers/schedules/approve_report.php?id=" + issueId + "&scheduleId=" + scheduleId;
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                window.location.href = HOST_URL + "pages/schedule/update.php?id=" + scheduleId + "&issueId=" + issueId;
            }
        });
    }

    $(".btn-detail3").on("click", function() {
        $("#detail_idIssue").text($(this).data("id"));
        console.log($(this).data("id"))
        $("#detail_schedule").text($(this).data("schedule"));
        $("#detail_reported").text($(this).data("reported"));
        $("#detail_issue").text($(this).data("type"));
        const datetime = $(this).data("date");
        const date = new Date(datetime.replace(" ", "T"));

        // Bagian tanggal → pakai locale Indonesia
        const tanggal = date.toLocaleDateString("id-ID", {
            weekday: "long",
            day: "numeric",
            month: "long",
            year: "numeric"
        });

        // Bagian jam → pakai locale Inggris (pemisah :)
        const waktu = date.toLocaleTimeString("en-GB", {
            hour: "2-digit",
            minute: "2-digit",
        });
        $("#detail_dateIssue").text(`
                                            Jam $ {
                                                waktu
                                            }.$ {
                                                tanggal
                                            }
                                            `);
        $("#detail_stat").text($(this).data("status")).addClass(`
                                            text - $ {
                                                $(this).data("state")
                                            }
                                            font - weight - bold`);
        $("#detail_desc").text($(this).data("desc"));
        $("#detailModalIssue").modal("show");
    });




    $(".btn-detail4").on("click", function() {
        console.log("halo");
        $("#detail_id_issue").text($(this).data("issue-id"));
        $("#detail_schedule").text($(this).data("schedule-id"));
        $("#detail_reported").text($(this).data("reported-by"));
        $("#detail_issue").text($(this).data("issue-type"));
        $("#detail_created_at").text($(this).data("created-at"));
        $("#detail_issue_status").text($(this).data("issue-status"));
        $("#detail_desc").text($(this).data("description"));
        $("#detail_date").text($(this).data("date"));
        $("#detail_job_type").text($(this).data("job-type"));
        $("#detail_schedule_status").text($(this).data("schedule-status"));
        $("#detail_location").text($(this).data("location"));
        // const datetime = $(this).data("date");
        // const date = new Date(datetime.replace(" ", "T"));

        // // Bagian tanggal → pakai locale Indonesia
        // const tanggal = date.toLocaleDateString("id-ID", {
        //     weekday: "long",
        //     day: "numeric",
        //     month: "long",
        //     year: "numeric"
        // });

        // // Bagian jam → pakai locale Inggris (pemisah :)
        // const waktu = date.toLocaleTimeString("en-GB", {
        //     hour: "2-digit",
        //     minute: "2-digit",
        // });
        $("#detailModalIssue").modal("show");
    });
</script>
<!--end::Page Scripts-->
</body>
<!--end::Body-->

</html>