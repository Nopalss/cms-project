<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'queue';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

$id = $_GET['id'];
$sql = "SELECT q.*, r.*, c.* FROM queue_scheduling q 
            JOIN request_ikr r ON q.request_id = r.rikr_id 
            JOIN customers c ON r.netpay_id = c.netpay_id 
        WHERE q.queue_id = :queue_id";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':queue_id', $id, PDO::PARAM_STR); // karena ID string
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $jadwal = new DateTime($row['jadwal_pemasangan']);
    $tanggalPemasangan = $jadwal->format('d F Y'); // 18 September 2025
    $jamPemasangan    = $jadwal->format('H:i');
    $cr = new DateTime($row['created_at']);
    $cr = $cr->format("d F Y");
    $statusClasses = [
        'Pending' => "info",
        'Accepted' => "success",
        'Rejected' => "danger",
    ];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>


<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Subheader-->
    <div class="subheader py-2 py-lg-6  subheader-solid " id="kt_subheader">
        <div class=" container-fluid  d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <!--begin::Info-->
            <div class="d-flex align-items-center flex-wrap mr-1">
                <!--begin::Page Heading-->
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <!--begin::Page Title-->
                    <a href="<?= BASE_URL ?>pages/queue/" class="h4 text-dark font-weight-bold my-1 mr-5">
                        Queue </a>

                    <!--end::Page Title-->

                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                                Detail Queue </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="" class="text-muted">
                                <?= $id ?> </a>
                        </li>
                    </ul>
                    <!-- end::Breadcrumb -->
                </div>
                <!--end::Page Heading-->
            </div>
            <?php if ($row['status'] == 'Pending'): ?>
                <a class="btn btn-light-primary align-self-end" href="<?= BASE_URL ?>pages/schedule/create.php?id=<?= $row['queue_id'] ?>">Scheduling</a>
            <?php endif; ?>
            <!--end::Info-->
        </div>
    </div>
    <!--end::Subheader-->

    <!--begin::Entry-->
    <div class="d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <!--begin::Row-->
            <div class="row align-items-stretch">
                <div class="col-md-6 mb-5">
                    <div class="card card-stretch">
                        <div class="card-header">
                            <h3>Queue Info</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Queue ID</th>
                                        <td><?= $row['queue_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Type Queue</th>
                                        <td><?= $row['type_queue'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Request ID</th>
                                        <td><?= $row['request_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Status</th>
                                        <td><span class="badge badge-pill badge-<?= $statusClasses[$row['status']] ?>"><?= $row['status'] ?></span></td>
                                    </tr>
                                    <tr>
                                        <th>Created At</th>
                                        <td><?= $cr ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Data Request IKR -->
                    <div class="card card-stretch mb-5">
                        <div class="card-header">
                            <h3>Data Request IKR</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>RIKR ID</th>
                                        <td><?= $row['rikr_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= $row['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jadwal Pemasangan</th>
                                        <td><?= $tanggalPemasangan ?></td>
                                    </tr>
                                    <tr>
                                        <th>Jam</th>
                                        <td><?= $jamPemasangan ?> </td>
                                    </tr>
                                    <tr>
                                        <th>Request By</th>
                                        <td><?= $row['request_by'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Catatan</th>
                                        <td><?= $row['catatan'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- card customer -->
                    <div class="card card-stretch">
                        <div class="card-header">
                            <h3>Data Customer</h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= $row['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td><?= $row['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td><?= $row['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket</th>
                                        <td><?= $row['paket_internet'] ?> Mbps</td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td><?= $row['location'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

<?php
require __DIR__ . '/../../includes/footer.php';
?>