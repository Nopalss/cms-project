<?php

require_once __DIR__ . '/../../../includes/config.php';
$_SESSION['menu'] = 'request ikr';
require __DIR__ . '/../../../includes/header.php';
require __DIR__ . '/../../../includes/aside.php';
require __DIR__ . '/../../../includes/navbar.php';
try {
    $rikr_key = isset($_GET['id']) ? $_GET['id'] : null;
    $perumahan = [
        "Puri Lestari"   => "Puri Lestari - 01",
        "Gramapuri Persada"   => "Gramapuri Persada - 02",
        "Telaga Harapan"   => "Telaga Harapan - 03",
        "Telaga Murni"   => "Telaga Murni - 04"
    ];
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

    $jamKerja = [
        "08:00",
        "09:00",
        "10:00",
        "11:00",
        "13:00",
        "14:00",
        "15:00",
        "16:00"
    ];
    if ($rikr_key) {
        $sql = "SELECT 
                    r.rikr_key,
                    r.rikr_id,
                    r.netpay_key,
                    r.registrasi_key,
                    r.jadwal_pemasangan,
                    r.catatan,
                    r.request_by,
                    c.netpay_id,
                    c.name,
                    c.location,
                    c.phone,
                    c.paket_internet,
                    c.perumahan,
                    rg.registrasi_id,
                    rg.`date` as date_request,
                    rg.`time` as time_request
                FROM request_ikr r
                JOIN customers c 
                    ON r.netpay_key = c.netpay_key
                JOIN register rg 
                    ON r.registrasi_key = rg.registrasi_key
                WHERE r.rikr_key = :rikr_key";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':rikr_key', $rikr_key, PDO::PARAM_STR);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        list($date, $time) = explode("T", $row['jadwal_pemasangan']);
        $daerah_id = substr($row['netpay_id'], 0, 2);
        $netpay_id = substr($row['netpay_id'], 2);
        if (!$row) {
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Oops! Ada yang Salah, Id Tidak Ditemukan',
                'button' => "Coba Lagi",
                'style' => "danger"
            ];
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
                                    Data Register Yang Menunggu Verifikasi
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
                                        <input type="hidden" name="registrasi_key" value="<?= $row['registrasi_key'] ?>" />
                                        <input type="hidden" name="netpay_key" value="<?= $row['netpay_key'] ?>" />
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="rikr_key" value="<?= $row['rikr_key'] ?>" />
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
                                <label for="is_verified">Status Verifikasi</label>
                                <input type="text" readonly name="is_verified" class="form-control" value="Unverified">
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Kapan Anda ingin jadwal pemasangan? <small class="text-muted ml-3">(Permintaan Customer)</small></label>
                                <input type="date" id="date" min="<?= date('Y-m-d'); ?>" required readonly value="<?= $row['date_request'] ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Jam Kunjungan <small class="text-muted ml-3">(Permintaan Customer)</small></label>
                                <input type="text" value="<?= $row['time_request'] ?>" readonly id="time" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="perumahan">Perumahan</label>
                                <select class="form-control selectpicker" id="perumahan" required name="perumahan" data-size=" 7">
                                    <option value="">Select</option>
                                    <?php foreach ($perumahan as $key => $value): ?>
                                        <?php $selected = ($key == $row['perumahan']) ? 'selected' : ''; ?>
                                        <option value='<?= $key ?>' <?= $selected ?>><?= $value ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                                    Update Permintaan IKR
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
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-right">Registrasi Id</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="registrasi_id2" value="<?= $row['registrasi_id'] ?>" disabled="disabled" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="exampleTextarea">Tanggal Pemasangan<small class="text-muted ml-3"> (Jadwal yang sudah dikonfirmasi)</small></label>
                                <input type="date" id="date_pemasangan" min="<?= date('Y-m-d'); ?>" required name="date_pemasangan" value="<?= $date ?>" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Jam pemasangan <small class="text-muted ml-3">(Jam yang sudah dikonfirmasi)</small></label>
                                <select class="form-control selectpicker" required id="time_pemasangan" name="time_pemasangan" data-size=" 7">
                                    <option value="">Select</option>
                                    <?php foreach ($jamKerja as $j): ?>
                                        <?php $selected = $j == $time ? 'selected' : ''; ?>
                                        <option value="<?= $j ?>" <?= $selected ?>><?= $j ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mb-1">
                                <label for="exampleTextarea">Catatan</label>
                                <textarea class="form-control" id="exampleTextarea" required name="catatan" rows="3"><?= $row['catatan'] ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer text-right">
                            <a href="<?= BASE_URL ?>pages/request/ikr/" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
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