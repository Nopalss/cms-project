<?php

require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'schedule';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

$id = $_GET['id'];
$issues = ['Absence', 'Equipment', 'Customer not available', 'Other'];
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
                            Task Issue Report
                        </h3>
                    </div>
                </div>
                <form method="post" class="form" action="<?= BASE_URL ?>controllers/schedules/issue_report.php">
                    <div class="card-body">
                        <div class="form-group">
                            <label class="text-right">Schedule ID</label>
                            <div>
                                <div class="input-group">
                                    <input type="text" class="form-control" value="<?= $id ?>" disabled="disabled" />
                                    <input type="hidden" name="schedule_id" value="<?= $id ?>" />
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Issue Type</label>
                            <select class="form-control selectpicker" required name="issue_type" data-size=" 7">
                                <option value="">Select</option>
                                <?php foreach ($issues as $i): ?>
                                    <option value="<?= $i ?>"><?= $i ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group mb-1">
                            <label for="exampleTextarea">Deskripsi</label>
                            <textarea class="form-control" id="exampleTextarea" required name="description" rows="3"></textarea>
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