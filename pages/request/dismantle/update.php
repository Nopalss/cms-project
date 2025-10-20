<?php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../helper/checkRowExist.php';
$_SESSION['menu'] = 'request dismantle';

$rd_key = isset($_GET['id']) ? (int)$_GET['id'] : null;
try {
    if (!$rd_key) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops! ID Request  Tidak Ditemukan',
            'text' => 'Silakan coba lagi.',
            'button' => "Coba Lagi",
            'style' => "danger"
        ];
        redirect("pages/request/dismantle/");
    }
    $sql = "SELECT rd.*, c.* FROM request_dismantle rd JOIN customers c ON rd.netpay_key = c.netpay_key WHERE rd_key = :rd_key";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':rd_key' => $rd_key]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    checkRowExist($row, "pages/request/dismantle/");
    $type_dismantle = ['Pindah Alamat', 'Biaya Mahal', 'Jarang Digunakan', 'Pelayanan Buruk', 'Gangguan Berkepanjangan', 'Ganti Provider', 'Lainnya'];
} catch (PDOException $e) {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal mendapatkan data, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/dismantle/");
}
require __DIR__ . '/../../../includes/header.php';
require __DIR__ . '/../../../includes/aside.php';
require __DIR__ . '/../../../includes/navbar.php';
?>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class=" d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <form method="post" class="form row" action="<?= BASE_URL ?>controllers/request/dismantle/update.php">
                <!-- card Update request Maintenance -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Update Request Dismantle
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Request Dismantle ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['rd_id'] ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="rd_id" value="<?= $row['rd_id'] ?>" />
                                    <input type="hidden" class="form-control" name="rd_key" value="<?= $row['rd_key'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-right">Netpay ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['netpay_id'] ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="netpay_id" value="<?= $row['netpay_id'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-right">Type Dismantle</label>
                                <select class="form-control selectpicker" id="type_dismantle" required name="type_dismantle">
                                    <option value="">Select</option>
                                    <?php foreach ($type_dismantle as $t): ?>
                                        <?php $selected = $t == $row['type_dismantle'] ? 'selected' : '' ?>
                                        <option value="<?= $t ?>" <?= $selected ?>><?= $t ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group mb-1">
                                <label for="exampleTextarea">Deskripsi Dismantle</label>
                                <textarea class="form-control" id="exampleTextarea" required name="deskripsi_dismantle" rows="3"><?= $row['deskripsi_dismantle'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/request/dismantle/" class="btn btn-light-danger font-weight-bold">Cancel</a>
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
                                        <td><?= $row['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Nama</th>
                                        <td><?= $row['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>No Hp</th>
                                        <td id="data-phone"><?= $row['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket</th>
                                        <td><?= $row['paket_internet'] ?> </td>
                                    </tr>
                                    <tr>
                                        <th>Is Active?</th>
                                        <td><?= $row['is_active'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Perumahan</th>
                                        <td class="text-wrap"><?= $row['perumahan'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td class="text-wrap"><?= $row['location'] ?></td>
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