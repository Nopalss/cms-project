<?php
require_once __DIR__ . '/../../includes/config.php';
$_SESSION['menu'] = 'user';
require __DIR__ . '/../../includes/header.php';
require __DIR__ . '/../../includes/aside.php';
require __DIR__ . '/../../includes/navbar.php';

?>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
    <!-- Subheader -->
    <div class="subheader py-2 py-lg-6 subheader-solid" id="kt_subheader">
        <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
            <div class="d-flex align-items-center flex-wrap mr-1">
                <div class="d-flex align-items-baseline flex-wrap mr-5">
                    <h5 class="text-dark font-weight-bold my-1 mr-5"><a class="text-dark " href=" <?= BASE_URL ?>pages/user/">Users</a> </h5>
                </div>
            </div>
        </div>
    </div>

    <!-- Entry -->
    <div class="d-flex flex-column-fluid">
        <div class="container">
            <div class="row justify-content-center">
                <!-- Detail Customers -->
                <div class="col-md-6 mt-5 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Create User</h5>
                        </div>
                        <div class="card-body">

                            <form method="post" class="form " action="<?= BASE_URL ?>controllers/user/create.php">
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input id="name" type="text" class="form-control" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input id="phone" type="tel" class="form-control" name="phone" placeholder="08xxxxxxxxxx"
                                        pattern="^08[0-9]{8,11}$"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <select class="form-control selectpicker" id="role" required name="role" data-size=" 7">
                                        <option value="">Select</option>
                                        <option value="admin">Admin</option>
                                        <option value="teknisi">Teknisi</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input id="username-disabled" type="text" class="form-control" disabled="disabled" required>
                                    <input id="username" type="hidden" class="form-control" name="username" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input id="password" type="password" class="form-control" minlength="5" name="password" required>
                                </div>
                                <div class="card-footer text-right">
                                    <a href="<?= BASE_URL ?>pages/user/" class="btn btn-light-danger font-weight-bold" data-dismiss="modal">Cancel</a>
                                    <button type="submit" name="submit" class="btn btn-primary font-weight-bold">Create</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php require __DIR__ . '/../../includes/footer.php'; ?>