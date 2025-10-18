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
                        url: HOST_URL + 'api/request_dismantle.php',
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
                scroll: true,   // biar tabel scrollable (horizontal/vertical)
                footer: false,
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
                field: 'rd_id',
                title: 'RD Id',
            }, {
                field: 'netpay_id',
                title: 'Netpay Id',
            },
            {
                field: 'type_dismantle',
                title: 'Type Dismantle',
            }, {
                field: 'request_by',
                title: 'Request By',
            }, {
                field: 'created_at',
                title: 'Created At',
            },
            {
                field: 'status',
                title: 'Status',
                autoHide: false,
                // callback function support for column rendering
                template: function (row) {
                    var status = {
                        'Accepted': {
                            'title': 'Accepted',
                            'state': 'success'
                        },
                        'Rejected': {
                            'title': 'Rejected',
                            'state': 'danger'
                        },
                        'Pending': {
                            'title': 'Pending',
                            'state': 'info'
                        },
                        'Not Queued': {
                            'title': 'Not Queued',
                            'state': 'muted'
                        },

                    };
                    var current = row.status ? row.status : 'Not Queued';

                    return '<span class="label label-' + status[current].state + ' label-dot mr-2"></span>' +
                        '<span class="font-weight-bold text-' + status[current].state + '">' +
                        status[current].title + '</span>';
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
                        'Accepted': {
                            'title': 'Accepted',
                            'state': 'success'
                        },
                        'Rejected': {
                            'title': 'Rejected',
                            'state': 'danger'
                        },
                        'Pending': {
                            'title': 'Pending',
                            'state': 'info'
                        },
                        'Not Queued': {
                            'title': 'Not Queued',
                            'state': 'muted'
                        },

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
                                    ${row.status === "Pending"
                            ? `<li class="navi-item cursor-pointer">
                                    <a href='${HOST_URL + 'pages/request/dismantle/update.php?id=' + row.rd_key}' class="navi-link">
                                        <span class="navi-icon "><i class="la la-pencil-alt text-warning"></i></span>
                                        <span class="navi-text">Edit</span>
                                    </a>
                                </li>`: ""
                        }
                                    <li class="navi-item cursor-pointer">\
                                        <a onclick="confirmDeleteTemplate('${row.rd_key}', 'controllers/request/dismantle/delete.php')" class="navi-link">\
                                            <span class="navi-icon "><i class="la la-trash text-danger"></i></span>\
                                            <span class="navi-text">Hapus</span>\
                                        </a>\
                                    </li>\
                                    <li class="navi-item cursor-pointer">\
                                        <a class="navi-link btn-detail-rd" data-rd-id="${row.rd_id}" data-status="${row.status}" data-state="${status[row.status].state}" data-netpay-id="${row.netpay_id}" data-type="${row.type_dismantle}" data-deskripsi="${row.deskripsi_dismantle}" data-request-by="${row.request_by}">\
                                            <span class="navi-icon"><i class="flaticon-eye text-info"></i></span>\
                                            <span class="navi-text"> Detail</span>\
                                        </a>\
                                    </li>\
                                </ul>\
                            </div>\
                        </div>\
                       
                    `;
                },
            }],
        });


        $('#kt_datatable_search_status').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'status');
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



        $('#kt_datatable_search_status, #kt_datatable_search_type').selectpicker();
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
