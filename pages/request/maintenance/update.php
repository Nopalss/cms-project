<?php

require_once __DIR__ . '/../../../includes/config.php';
$_SESSION['menu'] = 'request maintenance';
require __DIR__ . '/../../../includes/header.php';
require __DIR__ . '/../../../includes/aside.php';
require __DIR__ . '/../../../includes/navbar.php';
try {
    $rm_id = isset($_GET['id']) ? $_GET['id'] : null;
    $sql = "SELECT netpay_id FROM customers";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $sql = "SELECT * FROM request_maintenance WHERE rm_id = :rm_id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':rm_id', $rm_id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $type_issue = ['Signal Lemah', 'Modem Rusak', 'Kabel Bermasalah', 'Gangguan Internet', 'Upgrade Paket', 'Lainnya'];
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class=" d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <form method="post" class="form row" action="<?= BASE_URL ?>controllers/request/maintenance/update.php">
                <!-- card Update request Maintenance -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Update Request Maintenance
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Request Maintenance ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $rm_id ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="rm_id" value="<?= $rm_id ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Netpay ID</label>
                                <select class="form-control selectpicker" id="netpay_id" required name="netpay_id" data-size=" 7" data-live-search="true">
                                    <option value="">Select</option>
                                    <?php foreach ($customers as $c): ?>
                                        <?php $selected = $c['netpay_id'] == $row['netpay_id'] ? 'selected' : '' ?>
                                        <option value="<?= $c['netpay_id'] ?>" <?= $selected ?>><?= $c['netpay_id'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label class="text-right">Type Issue</label>
                                <select class="form-control selectpicker" id="type_issue" required name="type_issue">
                                    <option value="">Select</option>
                                    <?php foreach ($type_issue as $t): ?>
                                        <?php $selected = $t == $row['type_issue'] ? 'selected' : '' ?>
                                        <option value="<?= $t ?>" <?= $selected ?>><?= $t ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-1">
                                <label for="exampleTextarea">Deskripsi Issue</label>
                                <textarea class="form-control" id="exampleTextarea" required name="deskripsi_issue" rows="3"><?= $row['deskripsi_issue'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/request/maintenance" class="btn btn-light-danger font-weight-bold">Cancel</a>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Update</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-10">
                    <div class="card card-custom mb-5" data-card="true">
                        <div class="card-header">
                            <div class="card-title">
                                <h3 class="card-label">Data Customer</h3>
                            </div>
                            <div class="card-toolbar">
                                <a href="#" class="btn btn-icon btn-sm btn-hover-light-primary mr-1" data-card-tool="toggle" data-toggle="tooltip" data-placement="top">
                                    <i class="ki ki-arrow-down icon-nm"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tr>
                                        <th>Netpay ID</th>
                                        <td id="data-netpay"></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td id="data-name"></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td id="data-phone"></td>
                                    </tr>
                                    <tr>
                                        <th>Paket</th>
                                        <td id="data-paket"> </td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td id="data-location" class="text-wrap"></td>
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
require __DIR__ . '/../../../includes/footer.php';
?>