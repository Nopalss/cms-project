<?php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../helper/checkRowExist.php';
require_once __DIR__ . '/../../../helper/sanitize.php';
require_once __DIR__ . '/../../../helper/generateId.php';
$_SESSION['menu'] = 'request dismantle';
$id = isset($_POST['id']) ? sanitize($_POST['id']) : null;
$rd_id = "";
try {
    if ($id) {
        $sql = "SELECT * FROM customers WHERE is_active ='Active' AND netpay_id =:id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        checkRowExist($row, "pages/request/dismantle/create.php");
        $rd_id = generateId("RD");
    } else {
        $row = [
            "netpay_key" => '',
            "netpay_id" => '',
            "name" => '',
            "phone" => '',
            "paket_internet" => '',
            "is_active" => '',
            "perumahan" => '',
            "location" => '',
        ];
    }
} catch (PDOException $e) {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/dismantle");
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
            <div class="row">
                <!-- card create request IKR -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Create Request Dismantle
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="post" class="form">
                                <div class="form-group">
                                    <label class="text-right">Netpay ID</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" placeholder="Cari Netpay ID" name="id" autocomplete="off" required value="<?= $row['netpay_id'] ?>" aria-describedby="basic-addon2">
                                        <button type="submit" class="btn btn-light-primary"><i class="flaticon-search"></i></button>
                                    </div>
                                </div>
                            </form>
                            <form method="post" class="form" action="<?= BASE_URL ?>controllers/request/dismantle/create.php">
                                <div class="form-group mt-7">
                                    <label class="text-right">Request Dismantle ID</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?= $rd_id ?>" disabled="disabled" />
                                        <input type="hidden" class="form-control" name="rd_id" value="<?= $rd_id ?>" />
                                        <input type="hidden" class="form-control" name="netpay_id" required value="<?= $row['netpay_id'] ?>">
                                        <input type="hidden" class="form-control" name="netpay_key" required value="<?= $row['netpay_key'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="text-right">Type dismantle</label>
                                    <select class="form-control selectpicker" id="type_dismantle" required name="type_dismantle">
                                        <option value="">Select</option>
                                        <option value="Pindah Alamat">Pindah Alamat</option>
                                        <option value="Biaya Mahal">Biaya Mahal</option>
                                        <option value="Jarang Digunakan">Jarang Digunakan</option>
                                        <option value="Pelayanan Buruk">Pelayanan Buruk</option>
                                        <option value="Gangguan Berkepanjangan">Gangguan Berkepanjangan</option>
                                        <option value="Ganti Provider">Ganti Provider</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="form-group mb-1">
                                    <label for="exampleTextarea">Deskripsi Dismantle</label>
                                    <textarea class="form-control" id="exampleTextarea" required name="deskripsi_dismantle" rows="3"></textarea>
                                </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/request/maintenance" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Create</button>
                        </div>
                        </form>
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
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>
<?php
require __DIR__ . '/../../../includes/footer.php';
?>