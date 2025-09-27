<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'dismantle';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
try {
    date_default_timezone_set('Asia/Jakarta');
    $id = isset($_GET['id']) ? $_GET['id'] : null;
    if (!$id) {
        $_SESSION['alert'] = [
            'icon' => 'warning',
            'title' => 'Oops!',
            'text' => 'ID dismantle tidak valid.',
            'button' => 'Oke',
            'style' => 'warning'
        ];
        header("Location: " . BASE_URL . "pages/dismantle/");
        exit;
    }
    $sql = "SELECT dr.*, c.* FROM dismantle_reports dr JOIN customers c ON dr.netpay_id = c.netpay_id WHERE dr.dismantle_id = :id";
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
            <form method="post" class="form row" action="<?= BASE_URL ?>controllers/report/dismantle/update.php">
                <!-- card update -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Update Dismantle Report
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Dismantle Report ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $customer['dismantle_id'] ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="dismantle_id" value="<?= $customer['dismantle_id'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Netpay ID</label>
                                <input type="text" class="form-control" value="<?= $customer['netpay_id'] ?>" disabled="disabled" />
                                <input type="hidden" class="form-control" name="netpay_id" value="<?= $customer['netpay_id'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>Tanggal</label>
                                <input type="date" class="form-control" name="tanggal" value="<?= $customer['tanggal'] ?>" readonly required />
                            </div>
                            <div class="form-group">
                                <label>Jam</label>
                                <input type="time" class="form-control" name="jam" value="<?= $customer['jam'] ?>" readonly required />
                            </div>
                            <div class="form-group">
                                <label>Alasan</label>
                                <textarea class="form-control" id="exampleTextarea" required name="alasan" rows="3"><?= $customer['alasan'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Action</label>
                                <textarea class="form-control" id="exampleTextarea" required name="action" rows="3"><?= $customer['action'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Part Removed</label>
                                <input type="text" class="form-control" name="part_removed" required value="<?= $customer['part_removed'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>Kondisi Perangkat</label>
                                <textarea class="form-control" id="exampleTextarea" required name="kondisi_perangkat" rows="3"><?= $customer['kondisi_perangkat'] ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>PIC</label>
                                <input type="text" class="form-control" name="pic" readonly value="<?= $customer['pic'] ?>" />
                            </div>
                            <div class="form-group">
                                <label>Keterangan</label>
                                <textarea class="form-control" id="exampleTextarea" required name="keterangan" rows="3"><?= $customer['keterangan'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/dismantle/" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Update</button>
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