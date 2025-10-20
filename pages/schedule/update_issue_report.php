<?php
require_once __DIR__ . '/../../includes/config.php';
require_once __DIR__ . '/../../helper/redirect.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    $_SESSION['info'] = "ID tidak ditemukan";
    redirect("pages/schedule/");
}

$sql = "SELECT * FROM issues_report WHERE issue_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id' => $id]);
$issueReport = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$issueReport) {
    $_SESSION['info'] = "Data issue report tidak ditemukan";
    redirect("pages/schedule/");
}

$_SESSION['menu'] = 'schedule';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

$issues = ['Absence', 'Equipment', 'Customer not available', 'Other'];
?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <div class="d-flex flex-column-fluid">
        <div class="container d-flex justify-content-center">
            <div class="card col-lg-7 card-custom shadow-sm">
                <div class="card-header pt-5">
                    <div class="card-title">
                        <h3 class="card-label">
                            Update Task Issue Report
                        </h3>
                    </div>
                </div>
                <form method="post" class="form" action="<?= BASE_URL ?>controllers/schedules/issue_update.php">
                    <div class="card-body">
                        <input type="hidden" name="issue_id" value="<?= $issueReport['issue_id'] ?>" />

                        <div class="form-group">
                            <label class="text-right">Schedule ID</label>
                            <div>
                                <div class="input-group">
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($issueReport['schedule_id']) ?>" disabled />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Issue Type</label>
                            <select class="form-control selectpicker" required name="issue_type" data-size="7">
                                <option value="">Select</option>
                                <?php foreach ($issues as $i): ?>
                                    <option value="<?= $i ?>" <?= ($issueReport['issue_type'] === $i) ? 'selected' : '' ?>>
                                        <?= $i ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-1">
                            <label for="exampleTextarea">Deskripsi</label>
                            <textarea class="form-control" id="exampleTextarea"
                                required name="description" rows="3"><?= htmlspecialchars($issueReport['description']) ?></textarea>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="<?= BASE_URL ?>pages/schedule/" class="btn btn-light-danger font-weight-bold">Cancel</a>
                        <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php
require __DIR__ . '/../../includes/footer.php';
?>