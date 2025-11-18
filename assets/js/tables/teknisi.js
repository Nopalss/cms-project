"use strict";
// Class definition

var KTDatatableTechProgress = function () {
    // Private function
    var demo = function () {
        var datatable = $('#kt_datatable').KTDatatable({
            // datasource definition
            data: {
                type: 'remote',
                source: {
                    read: {
                        url: HOST_URL + 'api/teknisi_progres.php',
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
                scroll: true,
                footer: false,
            },

            sortable: true,
            pagination: true,

            search: {
                input: $('#kt_datatable_search_query'),
                key: 'generalSearch'
            },

            // columns definition
            columns: [{
                field: 'tech_id',
                title: 'ID Teknisi',
                width: 100,
            }, {
                field: 'technician_name',
                title: 'Nama Teknisi',
                width: 150,
                template: function (row) {
                    return `
                        <a href="${HOST_URL}pages/teknisi/detail.php?id=${row.tech_id}" class="font-weight-bolder">${row.technician_name}</a>
                    `
                }
            }, {
                field: 'total_tugas',
                title: 'Total Tugas',
                textAlign: 'center',
                width: 150,
            }, {
                field: 'progress',
                title: 'Progress',
                autoHide: false,
                width: 260,
                template: function (row) {
                    let total = row.total_tugas;

                    let ins = row.total_instalasi;
                    let main = row.total_maintenance;
                    let dis = row.total_dismantle;

                    // Hitung persen tiap bagian
                    let pIns = total > 0 ? (ins / total * 100) : 0;
                    let pMain = total > 0 ? (main / total * 100) : 0;
                    let pDis = total > 0 ? (dis / total * 100) : 0;

                    return `
            <div class="d-flex flex-column w-100">
                
                <div class="d-flex justify-content-between mb-1">
                    <span class="font-size-xs text-muted font-weight-bolder">
                    <span class="text-primary">I</span>: ${ins} | <span class="text-warning">M</span>: ${main} | <span class="text-danger">D</span>: ${dis}
                    </span>
                    <span class="font-size-xs text-muted">Total: ${total}</span>
                    <span class="font-size-xs text-muted">Selesai: ${row.total_done}</span>
                </div>

                <div class="progress progress-sm" style="height: 12px;">
                    <!-- Instalasi (biru) -->
                    <div class="progress-bar bg-primary" role="progressbar"
                        style="width:${pIns}%"></div>

                    <!-- Maintenance (kuning) -->
                    <div class="progress-bar bg-warning" role="progressbar"
                        style="width:${pMain}%"></div>

                    <!-- Dismantle (hijau) -->
                    <div class="progress-bar bg-danger" role="progressbar"
                        style="width:${pDis}%"></div>
                </div>
            </div>
        `;
                }
            }
            ],
        });

        // 🔍 Filter
        $('#kt_datepicker_3').datepicker({
            format: 'mm/dd/yyyy',
            autoclose: true,
        }).on('changeDate', function () {
            datatable.search($(this).val(), 'date');
        });
        $('#kt_datatable_search_tech').on('change', function () {
            datatable.search($(this).val().toLowerCase(), 'tech_id');
        });
        $('#kt_datatable_search_query').on('keyup', function () {
            datatable.search($(this).val(), 'generalSearch');
        });
    };

    return {
        // public functions
        init: function () {
            demo();
        },
    };
}();

// Initialization
jQuery(document).ready(function () {
    KTDatatableTechProgress.init();
});
