<?php

require_once __DIR__ . '/../../../includes/config.php';
$_SESSION['menu'] = 'request ikr';
require __DIR__ . '/../../../includes/header.php';
require __DIR__ . '/../../../includes/aside.php';
require __DIR__ . '/../../../includes/navbar.php';
try {
    $rikr_id = isset($_GET['id']) ? $_GET['id'] : null;
    $paketInternet = [
        "5"   => "5 mbps - 150rb/bln",
        "10"  => "10 mbps - 300rb/bln",
        "30"  => "30 mbps - 650rb/bln",
        "50"  => "50 mbps - 850rb/bln",
        "100" => "100 mbps - 1jt/bln"
    ];
    $netpay_kode = [
        "20"   => "Cikarang - 20",
        "21"  => "Cikarang - 21",
        "22"  => "Cikarang - 22",
        "52"  => "Tasik Kab - 52",
        "55" => "Tasik Kot - 55",
        "27" => "Cipatat - 27",
        "24" => "Indramayu - 24",
        "28" => "Cibinong - 28",
    ];

    if ($rikr_id) {
        $sql = "SELECT 
                    r.rikr_id,
                    r.netpay_id,
                    r.registrasi_id,
                    r.jadwal_pemasangan,
                    r.catatan,
                    r.request_by,
                    rg.name,
                    rg.location,
                    rg.phone,
                    rg.paket_internet,
                    rg.is_verified,
                    rg.request_schedule
                FROM request_ikr r
                JOIN register rg 
                    ON r.registrasi_id = rg.registrasi_id
                WHERE r.rikr_id = :rikr_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':rikr_id', $rikr_id, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $daerah_id = substr($row['netpay_id'], 0, 2);
        $netpay_id = substr($row['netpay_id'], 2);
        if (!$row) {
            $_SESSION['error'] = "Data Registrasi Tidak Ditemukan";
            header("Location: " . BASE_URL . "pages/request/ikr/");
            exit;
        }
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>

<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">

    <!--begin::Entry-->
    <div class=" d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container">
            <form method="post" class="form row" action="<?= BASE_URL ?>controllers/request/ikr/update.php">
                <!-- card Data REGISTER -->
                <div class="col-md-6  mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Data Register
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Registrasi ID</label>
                                <div>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?= $row['registrasi_id'] ?>" disabled="disabled" />
                                        <input type="hidden" name="registrasi_id" value="<?= $row['registrasi_id'] ?>" />
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input id="name" type="text" class="form-control" name="name" value="<?= $row['name'] ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="phone">Phone</label>
                                <input id="phone" type="tel" class="form-control" name="phone" value="<?= $row['phone'] ?>" placeholder="08xxxxxxxxxx"
                                    pattern="^08[0-9]{8,11}$"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="paket_internet">Paket</label>
                                <select class="form-control selectpicker" id="paket_internet" required name="paket_internet" data-size=" 7">
                                    <option value="">Select</option>
                                    <?php foreach ($paketInternet as $key => $value): ?>
                                        <?php $selected = ($key == $row['paket_internet']) ? 'selected' : ''; ?>
                                        <option value='<?= $key ?>' <?= $selected ?>><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="is_verified">Is Verified?</label>
                                <select class="form-control selectpicker" id="is_verified" required name="is_verified" data-size=" 7">
                                    <option value="">Select</option>
                                    <?php
                                    $is_verified = ['Verified', 'Unverified'];
                                    foreach ($is_verified as $i): ?>
                                        <?php $selected = ($i == $row['is_verified']) ? 'selected' : ''; ?>
                                        <option value='<?= $i ?>' <?= $selected ?>><?= $i ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Kapan Anda ingin jadwal pemasangan?</label>
                                <input type="datetime-local" id="request_schedule" min="<?= date('Y-m-d\T08:00', strtotime('+1 day')); ?>" value="<?= $row['request_schedule'] ?>" required name="request_schedule" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Alamat</label>
                                <textarea class="form-control" id="location" required name="location" rows="3"><?= $row['location'] ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- card create request IKR -->
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Update Request IKR
                                </h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="text-right">Request IKR ID</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['rikr_id'] ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="rikr_id" value="<?= $row['rikr_id'] ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Netpay ID</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <select name="netpay_kode" class="form-control selectpicker ">
                                            <option value="">select</option>
                                            <?php foreach ($netpay_kode as $key => $value): ?>
                                                <?php $selected = ($key == $daerah_id) ? 'selected' : ''; ?>
                                                <option value="<?= $key ?>" <?= $selected ?>><?= $value ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <input type="text" class="form-control" value="<?= $netpay_id ?>" disabled="disabled" />
                                    <input type="hidden" class="form-control" name="netpay_id" value="<?= $netpay_id ?>" />
                                    <input type="hidden" class="form-control" name="netpay_old" value="<?= $row["netpay_id"] ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-right">Registrasi Id</label>

                                <div class="input-group">
                                    <input type="text" class="form-control" id="registrasi_id2" value="<?= $row['registrasi_id'] ?>" disabled="disabled" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="text-right">Jadwal Pemasangan</label>
                                <div class="input-group">
                                    <input type="datetime-local" id="jadwal_pemasangan" min=" <?= date('Y-m-d\T08:00', strtotime('+1 day')); ?>" value="<?= $row['request_schedule'] ?>" required name="jadwal_pemasangan" class="form-control">
                                </div>
                            </div>

                            <div class="form-group mb-1">
                                <label for="exampleTextarea">Catatan</label>
                                <textarea class="form-control" id="exampleTextarea" required name="catatan" rows="3"><?= $row['catatan'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/schedule/" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
                            <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Update</button>
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