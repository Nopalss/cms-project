<?php

require_once __DIR__ . '/../../../includes/config.php';
require_once __DIR__ . '/../../../helper/checkRowExist.php';
require_once __DIR__ . '/../../../helper/generateId.php';

$_SESSION['menu'] = 'request maintenance';

$id = isset($_POST['id']) ? trim($_POST['id']) : null;
$rm_id = "";
try {
    if ($id) {
        // === PANGGIL API EKSTERNAL MENGGANTIKAN QUERY DB ===
        $apiBase = "https://netpay.jabbar23.net/1_api/netpaydt.php";
        $token = NETPAY_API_TOKEN ?? ''; // pastikan NETPAY_API_TOKEN didefinisikan di includes/config.php
        $query = http_build_query([
            'path' => 'usernet',
            'netpay_id' => $id
        ]);
        $url = $apiBase . '?' . $query;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer {$token}",
            "Accept: application/json"
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10); // timeout 10s
        // jika development dan pakai self-signed (tidak disarankan di prod):
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            // cURL error
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Request gagal',
                'text' => 'Gagal menghubungi server Netpay. Error: ' . $curlErr,
                'button' => "Kembali",
                'style' => "danger"
            ];
            redirect("pages/request/maintenance/create.php");
            exit;
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            $data = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $_SESSION['alert'] = [
                    'icon' => 'error',
                    'title' => 'Response Error',
                    'text' => 'Data dari Netpay tidak valid JSON.',
                    'button' => "Kembali",
                    'style' => "danger"
                ];
                redirect("pages/request/maintenance/create.php");
                exit;
            }

            // Sesuaikan mapping ini dengan struktur JSON yang dikembalikan API-mu
            // Contoh asumsi: { "netpay_id":"123", "netpay_key":"abc", "name":"Nama", ... }
            if (empty($data)) {
                $_SESSION['alert'] = [
                    'icon' => 'warning',
                    'title' => 'Data tidak ditemukan',
                    'text' => 'Customer dengan Netpay ID ' . htmlspecialchars($id) . ' tidak ditemukan.',
                    'button' => "Kembali",
                    'style' => "warning"
                ];
                redirect("pages/request/maintenance/create.php");
                exit;
            }

            // Isi $row sesuai key yang dipakai di view
            $row = [
                'netpay_id'     => $data['netpay_id'] ?? $id,
                'netpay_key'    => $data['iduser'] ?? '',
                'name'          => $data['nama'] ?? '',
                'phone'         => $data['telepon'] ?? '',
                'paket_internet' => $data['paket'] ?? '',
                'is_active'     => $data['status'] ?? '',
                'perumahan'     => $data['alamat'] ?? '',
                'location'      => $data['jalan'] ?? '',
            ];

            // Bila perlu validasi row exist seperti helper-mu:
            checkRowExist($row, "pages/request/maintenance/create.php");

            $rm_id = generateId('RM');
        } else {
            // HTTP error dari API
            $_SESSION['alert'] = [
                'icon' => 'error',
                'title' => 'Request gagal',
                'text' => "Server Netpay merespon HTTP {$httpCode}. Response: " . substr($response, 0, 300),
                'button' => "Kembali",
                'style' => "danger"
            ];
            redirect("pages/request/maintenance/create.php");
            exit;
        }
    } else {
        // tanpa id: default kosong seperti semula
        $row = [
            "netpay_id" => '',
            "netpay_key" => '',
            "name" => '',
            "phone" => '',
            "paket_internet" => '',
            "is_active" => '',
            "perumahan" => '',
            "location" => '',
        ];
    }
} catch (PDOException $e) {
    // Jika kamu masih menggunakan DB di bagian lain, tangani PDOException
    $_SESSION['alert'] = [
        'icon' => 'error',
        'title' => 'Oops! Ada yang Salah',
        'text' => 'Silakan coba lagi nanti. Error: ' . $e->getMessage(),
        'button' => "Coba Lagi",
        'style' => "danger"
    ];
    redirect("pages/request/maintenance");
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

            <!-- card create request IKR -->
            <div class="row">
                <div class="col-md-6 mb-10">
                    <div class="card card-custom shadow-sm">
                        <div class="card-header pt-5">
                            <div class="card-title">
                                <h3 class="card-label">
                                    Create Request Maintenance
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
                            <form method="post" class="form" action="<?= BASE_URL ?>controllers/request/maintenance/create.php">
                                <div class="form-group mt-7">
                                    <label class="text-right">Request Maintenance ID</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="<?= $rm_id ?>" disabled="disabled" />
                                        <input type="hidden" class="form-control" name="rm_id" value="<?= $rm_id ?>" />
                                        <input type="hidden" class="form-control" name="netpay_id" required value="<?= $row['netpay_id'] ?>">
                                        <input type="hidden" class="form-control" name="netpay_key" required value="<?= $row['netpay_key'] ?>">
                                        <input type="hidden" class="form-control" name="name" required value="<?= $row['name'] ?>">
                                        <input type="hidden" class="form-control" name="phone" required value="<?= $row['phone'] ?>">
                                        <input type="hidden" class="form-control" name="is_active" required value="<?= $row['is_active'] ?>">
                                        <input type="hidden" class="form-control" name="perumahan" required value="<?= $row['perumahan'] ?>">
                                        <input type="hidden" class="form-control" name="location" required value="<?= $row['location'] ?>">
                                        <input type="hidden" class="form-control" name="paket_internet" required value="<?= $row['paket_internet'] ?>">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="text-right">Type Issue</label>
                                    <select class="form-control selectpicker" id="type_issue" required name="type_issue">
                                        <option value="">Select</option>
                                        <option value="Signal Lemah">Signal Lemah</option>
                                        <option value="Modem Rusak">Modem Rusak</option>
                                        <option value="Gangguan Internet">Gangguan Internet</option>
                                        <option value="Upgrade Paket">Upgrade Paket</option>
                                        <option value="Lainnya">Lainnya</option>
                                    </select>
                                </div>

                                <div class="form-group mb-1">
                                    <label for="exampleTextarea">Deskripsi Issue</label>
                                    <textarea class="form-control" id="exampleTextarea" required name="deskripsi_issue" rows="3"></textarea>
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