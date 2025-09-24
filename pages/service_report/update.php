<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'service';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
try {
    date_default_timezone_set('Asia/Jakarta');
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    $sql = "SELECT srv.*, c.* FROM service_reports srv JOIN customers c ON srv.netpay_id = c.netpay_id WHERE srv.srv_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class=" d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <form method="post" class="form row" action="<?= BASE_URL ?>controllers/report/service/update.php">
                <!-- card create request IKR -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Update Service Report
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Service Report ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $customer['srv_id'] ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="srv_id" value="<?= $customer['srv_id'] ?>" />
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
                                <input type="date" class="form-control" name="tanggal" value="<?= $customer['tanggal'] ?>" required readonly />
                            </div>
                            <div class="form-group">
                                <label>Jam</label>
                                <input type="time" class="form-control" name="jam" value="<?= $customer['jam'] ?>" required readonly />
                            </div>
                            <div class="form-group">
                                <label>Problem</label>
                                <textarea class="form-control" id="exampleTextarea" required name="problem" rows="3"><?= $customer['problem'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Action</label>
                                <textarea class="form-control" id="exampleTextarea" required name="action" rows="3"><?= $customer['action'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Part</label>
                                <input type="text" class="form-control" name="part" required value="<?= $customer['part'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>Redaman Sebelum</label>
                                <input type="text" class="form-control" name="red_bef" required value='<?= $customer['red_bef'] ?>' />
                            </div>
                            <div class="form-group">
                                <label>Redaman Sesudah</label>
                                <input type="text" class="form-control" name="red_aft" required value="<?= $customer['red_aft'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>PIC</label>
                                <input type="text" class="form-control" name="pic" value="<?= $customer['pic'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control" id="exampleTextarea" required name="keterangan" rows="3"><?= $customer['keterangan'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/service_report/" class="btn btn-light-danger font-weight-bold">Cancel</a>
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