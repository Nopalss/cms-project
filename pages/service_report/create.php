<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'service';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
try {
    date_default_timezone_set('Asia/Jakarta');
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    if (!$id) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'Schedule ID tidak ditemukan.',
            'button' => "Oke",
            'style' => "warning"
        ];
        header("Location: " . BASE_URL . "pages/service_report/");
        exit;
    }

    $sql = "SELECT s.schedule_id, s.tech_id ,s.netpay_id, c.* FROM schedules s JOIN customers c ON s.netpay_id = c.netpay_id WHERE s.schedule_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    $srv_id = "SR" . date("YmdHis");
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops!',
        'text' => 'Terjadi kesalahan, silakan coba lagi.',
        'button' => "Oke",
        'style' => "danger"
    ];
    header("Location: " . BASE_URL . "pages/service_report/");
    exit;
}


?>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class=" d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <form method="post" class="form row" action="<?= BASE_URL ?>controllers/report/service/create.php">
                <!-- card create request IKR -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Create Service Report
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Service Report ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $srv_id ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="srv_id" value="<?= $srv_id ?>" />
                                    <input type="hidden" class="form-control" name="schedule_id" value="<?= $customer['schedule_id'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Netpay ID</label>
                                <input type="text" class="form-control" value="<?= $customer['netpay_id'] ?>" disabled="disabled" />
                                <input type="hidden" class="form-control" name="netpay_id" value="<?= $customer['netpay_id'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" value="<?= date('Y-m-d') ?>" required />
                            </div>
                            <div class="form-group">
                                <label>Jam</label>
                                <input type="time" class="form-control" name="jam" value="<?= date('H:i') ?>" required />
                            </div>
                            <div class="form-group">
                                <label>Problem</label>
                                <textarea class="form-control" id="exampleTextarea" required name="problem" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Action</label>
                                <textarea class="form-control" id="exampleTextarea" required name="action" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Part</label>
                                <input type="text" class="form-control" name="part" required />
                            </div>
                            <div class="form-group">
                                <label>Redaman Sebelum</label>
                                <input type="text" class="form-control" name="red_bef" required />
                            </div>
                            <div class="form-group">
                                <label>Redaman Sesudah</label>
                                <input type="text" class="form-control" name="red_aft" required />
                            </div>
                            <div class="form-group">
                                <label>PIC</label>
                                <input type="text" class="form-control" readonly name="pic" value="<?= $customer['tech_id'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control" id="exampleTextarea" required name="keterangan" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/service_report/" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Create</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-10">
                    <div class="card">
                        <div class="card-header">
                            <h5>Detail Customers</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td><?= $customer['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td><?= $customer['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td><?= $customer['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket Internet</th>
                                        <td><?= $customer['paket_internet'] ?> Mbps</td>
                                    </tr>
                                    <tr>
                                        <th>Is Active?</th>
                                        <td><?= $customer['is_active'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td class="text-wrap"><?= $customer['location'] ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<?php
require __DIR__ . '/../../includes/footer.php';
?>