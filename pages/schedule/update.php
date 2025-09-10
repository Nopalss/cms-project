<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'schedule';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';
try {
    $id = $_GET['id'];
    $sql = "SELECT * FROM schedules WHERE schedule_id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM technician";
    $stmt = $pdo->query($sql);
    $technicians = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
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
                            Update Schedule
                        </h3>
                    </div>
                </div>
                <form action="" class="form">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-right">Schedule ID</label>
                            <div>
                                <div class="input-group">
                                    <input type="text" class="form-control" name="schedule_id" value="<?= $row['schedule_id'] ?>" disabled="disabled" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Teknisi</label>
                            <select class="form-control selectpicker" required name="tech_id" data-size=" 7" data-live-search="true">
                                <option value="">Select</option>
                                <?php foreach ($technicians as $t): ?>
                                    <?php $selected = ($t['tech_id'] == $row['tech_id']) ? 'selected' : ''; ?>
                                    <option value="<?= $t['tech_id'] ?>" <?= $selected ?>><?= $t['name'] ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="text-right">Tanggal</label>
                            <div>
                                <div class="input-group date">
                                    <input type="text" class="form-control" required name="date" readonly value="<?= $row['date'] ?>" id="kt_datepicker_3" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="la la-calendar"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="">Jam</label>
                            <div>
                                <div class="input-group timepicker">
                                    <input class="form-control" id="kt_timepicker_2" required name="time" value="<?= $row['time'] ?>" type="text" />
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            <i class="la la-clock-o"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Tipe Job</label>
                            <select class="form-control selectpicker" required name="job_type" data-size="7">
                                <option value="">--Select--</option>
                                <option value="Instalasi">Instalasi</option>
                                <option value="Maintenance">Maintenance</option>
                                <option value="Perbaikan">Perbaikan</option>
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="exampleTextarea">Alamat</label>
                            <textarea class="form-control" id="exampleTextarea" required name="location" rows="3"><?= $row['location'] ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="<?= BASE_URL ?>pages/schedule/" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
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