<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'registrasi';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
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
                            Create Registrasi
                        </h3>
                    </div>
                </div>
                <form method="post" class="form" action="<?= BASE_URL ?>controllers/registrasi/create.php">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input id="name" type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone</label>
                            <input id="phone" type="tel" class="form-control" name="phone" placeholder="08xxxxxxxxxx"
                                pattern="^(?:\+62|62|0)8[0-9]{8,11}$"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="paket_internet">Paket</label>
                            <select class="form-control selectpicker" id="paket_internet" required name="paket_internet" data-size=" 7">
                                <option value="">Select</option>
                                <?php foreach ($paketInternet as $key => $value): ?>
                                    <option value='<?= $key ?>'><?= $value ?></option>"
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea">Kapan Anda ingin jadwal pemasangan?</label>
                            <input type="date" min="<?= date('Y-m-d', strtotime('+1 day')); ?>" required name="date" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Jam Kunjungan</label>
                            <select class="form-control selectpicker" required name="time" data-size=" 7">
                                <option value="">Select</option>
                                <?php foreach ($jamKerja as $j): ?>

                                    <option value="<?= $j ?>"><?= $j ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleTextarea">Alamat</label>
                            <textarea class="form-control" id="exampleTextarea" required name="location" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="<?= BASE_URL ?>pages/registrasi/" class="btn btn-light-danger font-weight-bold">Cancel</a>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Create</button>
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