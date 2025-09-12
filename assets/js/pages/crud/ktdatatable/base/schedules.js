"use strict";
// Class definition

var KTDatatableLocalSortDemo = function () {
    console.log(HOST_URL);
    // Private functions

    // basic demo
    var demo = function () {
        var datatable = $('#kt_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: HOST_URL + 'api/schedules.php',
                    },
                },
                pageSize: 10,
                serverPaging: false,
                serverFiltering: true,
                serverSorting: false,
                saveState: {
                    cookie: false,
                    webstorage: false,
                },
            },

            // layout definition
            layout: {
                scroll: false, // enable/disable datatable scroll both horizontal and vertical when needed.
                footer: false, // display/hide footer
            },

            // column sorting
            sortable: true,

            pagination: true,

            search: {
                input: $('#kt_datatable_search_query'),
                key: 'generalSearch'
            },
            // columns definition
            columns: [{
                field: 'schedule_id',
                title: 'Schedule Id',
            }, {
                field: 'technician_name',
                title: 'Teknisi',
            }, {
                field: 'date',
                title: 'Date',
                template: function (row) {
                    const date = new Date(row.date);
                    const options = { year: 'numeric', month: 'numeric', day: 'numeric' };
                    const formattedDate = date.toLocaleDateString('id-ID', options);
                    return formattedDate;
                }
            }, {
                field: 'time',
                title: 'Time',
            }, {
                field: 'location',
                title: 'Location',
            }, {
                field: 'job_type',
                title: 'Job Type',
                autoHide: false,
            }, {
                field: 'status',
                title: 'Status',
                autoHide: false,
                // callback function support for column rendering
                template: function (row) {
                    var status = {
                        'Pending': {
                            'title': 'Pending',
                            'state': 'info'
                        },
                        'On Progress': {
                            'title': 'On Progress',
                            'state': 'primary'
                        },
                        'Rescheduled': {
                            'title': 'Rescheduled',
                            'state': 'warning'
                        },
                        'Cancelled': {
                            'title': 'Cancelled',
                            'state': 'danger'
                        },
                        'Done': {
                            'title': 'Done',
                            'state': 'success'
                        },
                    };
                    return '<span class="label label-' + status[row.status].state + ' label-dot mr-2"></span><span class="font-weight-bold text-' + status[row.status].state + '">' +
                        status[row.status].title + '</span>';
                },
            }, {
                field: 'Actions',
                title: 'Actions',
                sortable: false,
                width: 125,
                overflow: 'visible',
                autoHide: false,
                template: function (row) {
                    var status = {
                        'Pending': {
                            'title': 'Pending',
                            'state': 'info'
                        },
                        'On Progress': {
                            'title': 'On Progress',
                            'state': 'primary'
                        },
                        'Rescheduled': {
                            'title': 'Rescheduled',
                            'state': 'warning'
                        },
                        'Cancelled': {
                            'title': 'Cancelled',
                            'state': 'danger'
                        },
                        'Done': {
                            'title': 'Done',
                            'state': 'success'
                        }
                    };
                    return `\
                        <div class="dropdown dropdown-inline">\
                            <a href="javascript:;" class="btn btn-sm btn-light btn-text-primary btn-icon mr-2" data-toggle="dropdown">\
                                <span class="svg-icon svg-icon-md">\
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
                                            <rect x="0" y="0" width="24" height="24"/>\
                                            <path d="M5,8.6862915 L5,5 L8.6862915,5 L11.5857864,2.10050506 L14.4852814,5 L19,5 L19,9.51471863 L21.4852814,12 L19,14.4852814 L19,19 L14.4852814,19 L11.5857864,21.8994949 L8.6862915,19 L5,19 L5,15.3137085 L1.6862915,12 L5,8.6862915 Z M12,15 C13.6568542,15 15,13.6568542 15,12 C15,10.3431458 13.6568542,9 12,9 C10.3431458,9 9,10.3431458 9,12 C9,13.6568542 10.3431458,15 12,15 Z" fill="#000000"/>\
                                        </g>\
                                    </svg>\
                                </span>\
                            </a>\
                            <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">\
                                <ul class="navi flex-column navi-hover py-2">\
                                    <li class="navi-header font-weight-bolder text-uppercase font-size-xs text-primary pb-2">\
                                        Choose an action:\
                                    </li>\
                                    <li class="navi-item">\
                                        <a href='${HOST_URL + 'pages/schedule/update.php?id=' + row.schedule_id}' class="navi-link">\
                                            <span class="navi-icon "><i class="la la-pencil-alt text-warning"></i></span>\
                                            <span class="navi-text">Edit</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item cursor-pointer">\
                                        <a onclick="confirmDelete('${row.schedule_id}')" class="navi-link">\
                                            <span class="navi-icon "><i class="la la-trash text-danger"></i></span>\
                                            <span class="navi-text">Hapus</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item cursor-pointer">\
                                        <a class="navi-link btn-detail" data-id="${row.schedule_id}" data-tech="${row.technician_name}" data-date="${row.date}" data-job="${row.job_type}" data-state="${status[row.status].state}" data-status="${row.status}" data-location="${row.location}">\
                                            <span class="navi-icon "><i class="flaticon-eye text-info"></i></span>\
                                            <span class="navi-text">Detail</span>\
                                        </a>\
                                    </li>\
                                </ul>\
                            </div>\
                        </div>\
                       
                    `;
                },
            }],
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
                    // Redirect ke delete.php dengan parameter ID
                    window.location.href = "<?= BASE_URL ?>controllers/schedules/delete.php?id=" + scheduleId;
                }
            });
        }

        $('#kt_datatable_search_status').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'status');
        });

        $('#kt_datatable_search_type').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'job_type');
        });
        $('#kt_datatable_search_tech').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'tech_id');
        });
        $('#kt_datepicker_3').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function (e) {
            let val = $(this).val(); // contoh: 09/18/2025
            if (val) {
                let parts = val.split('/');
                let formatted = parts[2] + '-' + parts[0] + '-' + parts[1]; // 2025-09-18
                datatable.search($(this).val(), 'date');
            }
        });



        $('#kt_datatable_search_status, #kt_datatable_search_type, #kt_datatable_search_tech').selectpicker();
    };

    return {
        // public functions
        init: function () {
            demo();
        },
    };
}();

jQuery(document).ready(function () {
    KTDatatableLocalSortDemo.init();
});
