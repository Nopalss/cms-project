<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'schedule';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
try {
    $id = isset($_POST['id']) ? $_POST['id'] : null;
    $sql = "SELECT s.schedule_id ,s.netpay_id, c.* FROM schedules s JOIN customers c ON s.netpay_id = c.netpay_id WHERE s.schedule_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([":id" => $id]);
    $customer = $stmt->fetch(PDO::FETCH_ASSOC);
    $ikr_id = date("YmdHis");
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">
    <!--begin::Entry-->
    <div class=" d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <form method="post" class="form row" action="<?= BASE_URL ?>controllers/report/ikr/create.php">
                <!-- card create request IKR -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Create Report IKR
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Report IKR ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $ikr_id ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="ikr_id" value="<?= $ikr_id ?>" />
                                    <input type="hidden" class="form-control" name="schedule_id" value="<?= $id ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Netpay ID</label>
                                <input type="text" class="form-control" value="<?= $customer['netpay_id'] ?>" disabled="disabled" />
                                <input type="hidden" class="form-control" name="netpay_id" value="<?= $customer['netpay_id'] ?>" />
                            </div>
                            <!-- Group IKR -->
                            <div class="form-group">
                                <label for="group_ikr">Group IKR</label>
                                <input type="text" class="form-control" id="group_ikr" name="group_ikr" required>
                            </div>

                            <!-- IKR AN -->
                            <div class="form-group">
                                <label for="ikr_an">IKR AN</label>
                                <input type="text" class="form-control" id="ikr_an" name="ikr_an" value="<?= $customer['name'] ?>" required>
                            </div>

                            <!-- Alamat -->
                            <div class="form-group">
                                <label for="alamat">Alamat</label>
                                <input type="text" class="form-control" id="alamat" name="alamat" value="<?= $customer['location'] ?>" required>
                            </div>

                            <!-- RT & RW dalam row -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="rt">RT</label>
                                    <input type="text" class="form-control" id="rt" name="rt" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="rw">RW</label>
                                    <input type="text" class="form-control" id="rw" name="rw" required>
                                </div>
                            </div>

                            <!-- Desa, Kecamatan, Kabupaten -->
                            <div class="form-group">
                                <label for="desa">Desa</label>
                                <input type="text" class="form-control" id="desa" name="desa" required>
                            </div>
                            <div class="form-group">
                                <label for="kec">Kecamatan</label>
                                <input type="text" class="form-control" id="kec" name="kec" required>
                            </div>
                            <div class="form-group">
                                <label for="kab">Kabupaten</label>
                                <input type="text" class="form-control" id="kab" name="kab" required>
                            </div>

                            <!-- Telepon -->
                            <div class="form-group">
                                <label for="telp">Telepon</label>
                                <input type="text" class="form-control" id="telp" name="telp" value="<?= $customer['phone'] ?>" required>
                            </div>

                            <!-- S/N -->
                            <div class="form-group">
                                <label for="sn">S/N</label>
                                <input type="text" class="form-control" id="sn" name="sn" required>
                            </div>

                            <!-- Paket & Type ONT -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="paket">Paket</label>
                                    <input type="text" class="form-control" id="paket" name="paket" required>
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="type_ont">Type ONT</label>
                                    <input type="text" class="form-control" id="type_ont" name="type_ont" required>
                                </div>
                            </div>

                            <!-- Redaman -->
                            <div class="form-group">
                                <label for="redaman">Redaman</label>
                                <input type="text" class="form-control" id="redaman" name="redaman" required>
                            </div>

                            <!-- ODP, ODC, JC No -->
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="odp_no">ODP No</label>
                                    <input type="text" class="form-control" id="odp_no" name="odp_no" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="odc_no">ODC No</label>
                                    <input type="text" class="form-control" id="odc_no" name="odc_no" required>
                                </div>
                                <div class="form-group col-md-4">
                                    <label for="jc_no">JC No</label>
                                    <input type="text" class="form-control" id="jc_no" name="jc_no" required>
                                </div>
                            </div>

                            <!-- MAC sebelum & sesudah -->
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="mac_sebelum">MAC Sebelum</label>
                                    <input type="text" class="form-control" id="mac_sebelum" name="mac_sebelum">
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="mac_sesudah">MAC Sesudah</label>
                                    <input type="text" class="form-control" id="mac_sesudah" name="mac_sesudah">
                                </div>
                            </div>

                            <!-- ODP, ODC, Enclosure, Paket No -->
                            <div class="form-group">
                                <label for="odp">ODP</label>
                                <input type="text" class="form-control" id="odp" name="odp" required>
                            </div>
                            <div class="form-group">
                                <label for="odc">ODC</label>
                                <input type="text" class="form-control" id="odc" name="odc" required>
                            </div>
                            <div class="form-group">
                                <label for="enclosure">Enclosure</label>
                                <input type="text" class="form-control" id="enclosure" name="enclosure" required>
                            </div>
                            <div class="form-group">
                                <label for="paket_no">Paket No</label>
                                <input type="text" class="form-control" id="paket_no" name="paket_no" required>
                            </div>

                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/ikr/" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Create</button>
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
                                        <td id="data-netpay"><?= $customer['netpay_id'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Name</th>
                                        <td id="data-name"><?= $customer['name'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Phone</th>
                                        <td id="data-phone"><?= $customer['phone'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Paket</th>
                                        <td id="data-paket"><?= $customer['paket_internet'] ?> </td>
                                    </tr>
                                    <tr>
                                        <th>Is Active?</th>
                                        <td id="data-active"><?= $customer['is_active'] ?></td>
                                    </tr>
                                    <tr>
                                        <th>Location</th>
                                        <td id="data-location" class="text-wrap"><?= $customer['location'] ?></td>
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