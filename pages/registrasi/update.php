<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'registrasi';

require_once __DIR__ . '/../../helper/redirect.php';
require_once __DIR__ . '/../../helper/checkRowExist.php';
try {
    $id = (int) $_GET['id'];
    if (!$id) {
        redirect("pages/registrasi/");
    }
    $paketInternet = [
        "5"   => "5 mbps - 150rb/bln",
        "10"  => "10 mbps - 300rb/bln",
        "30"  => "30 mbps - 650rb/bln",
        "50"  => "50 mbps - 850rb/bln",
        "100" => "100 mbps - 1jt/bln"
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
    $sql = "SELECT * FROM register WHERE registrasi_key = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    checkRowExist($row, "pages/registrasi/");
} catch (PDOException $e) {
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Gagal mendapatkan data, silakan coba lagi',
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/registrasi/");
}
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
?>

<div class="content  d-flex flex-column flex-column-fluid" id="kt_content">

    <!--begin::Entry-->
    <div class=" d-flex flex-column-fluid">
        <!--begin::Container-->
        <div class="container d-flex justify-content-center">
            <div class="card col-lg-7 card-custom shadow-sm">
                <div class="card-header pt-5">
                    <div class="card-title">
                        <h3 class="card-label">
                            Update Registrasi
                        </h3>
                    </div>
                </div>
                <form method="post" class="form" action="<?= BASE_URL ?>controllers/registrasi/update.php">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-right">Registrasi ID</label>
                            <div>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $row['registrasi_id'] ?>" disabled="disabled" />
                                    <input type="hidden" name="registrasi_key" value="<?= $row['registrasi_key'] ?>" />
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
                            <label for="exampleTextarea">Kapan Anda ingin jadwal pemasangan?</label>
                            <input type="date" min="<?= date('Y-m-d', strtotime('+1 day')); ?>" required name="date" value="<?= $row['date'] ?>" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Jam Kunjungan</label>
                            <select class="form-control selectpicker" required name="time" data-size=" 7">
                                <option value="">Select</option>
                                <?php foreach ($jamKerja as $j): ?>
                                    <?php $selected = $j == $row['time'] ? 'selected' : ''; ?>
                                    <option value="<?= $j ?>" <?= $selected ?>><?= $j ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea">Alamat</label>
                            <textarea class="form-control" id="exampleTextarea" required name="location" rows="3"><?= $row['location'] ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="<?= BASE_URL ?>pages/registrasi/" class="btn btn-light-danger font-weight-bold">Cancel</a>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Update</button>
                    </div>
                </form>
            </div>
        </div>
        <!--end::Container-->
    </div>
    <!--end::Entry-->
</div>

<?php
require __DIR__ . '/../../includes/footer.php';
?>