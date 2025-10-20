<?php

require_once __DIR__ . "/../../../includes/config.php";

if (isset($_POST['submit'])) {
    // Ambil semua inputan
    $ikr_key      = $_POST['ikr_key'] ?? '';
    $group_ikr   = $_POST['group_ikr'] ?? '';
    $ikr_an      = $_POST['ikr_an'] ?? '';
    $alamat      = $_POST['alamat'] ?? '';
    $rt          = $_POST['rt'] ?? '';
    $rw          = $_POST['rw'] ?? '';
    $desa        = $_POST['desa'] ?? '';
    $kec         = $_POST['kec'] ?? '';
    $kab         = $_POST['kab'] ?? '';
    $telp        = $_POST['telp'] ?? '';
    $sn          = $_POST['sn'] ?? '';
    $paket       = $_POST['paket'] ?? '';
    $type_ont    = $_POST['type_ont'] ?? '';
    $redaman     = $_POST['redaman'] ?? '';
    $odp_no      = $_POST['odp_no'] ?? '';
    $odc_no      = $_POST['odc_no'] ?? '';
    $jc_no       = $_POST['jc_no'] ?? '';
    $mac_sebelum = $_POST['mac_sebelum'] ?? '';
    $mac_sesudah = $_POST['mac_sesudah'] ?? '';
    $odp         = $_POST['odp'] ?? '';
    $odc         = $_POST['odc'] ?? '';
    $enclosure   = $_POST['enclosure'] ?? '';
    $paket_no    = $_POST['paket_no'] ?? '';
    $check = $pdo->prepare("SELECT COUNT(*) FROM ikr WHERE ikr_key = :ikr_key");
    $check->execute([':ikr_key' => $ikr_key]);
    if ($check->fetchColumn() == 0) {
        $_SESSION['alert'] = [
            'icon' => 'error',
            'title' => 'Oops!',
            'text' => 'Data IKR tidak ditemukan.',
            'button' => "Oke",
            'style' => "danger"
        ];
        header("Location: " . BASE_URL . "pages/ikr/");
        exit;
    }
    // Satukan ke array
    $inputs = compact(
        'ikr_key',
        'group_ikr',
        'ikr_an',
        'alamat',
        'rt',
        'rw',
        'desa',
        'kec',
        'kab',
        'telp',
        'sn',
        'paket',
        'type_ont',
        'redaman',
        'odp_no',
        'odc_no',
        'jc_no',
        'mac_sebelum',
        'mac_sesudah',
        'odp',
        'odc',
        'enclosure',
        'paket_no'
    );
    function sanitize($data)
    {
        return htmlspecialchars(strip_tags(trim($data)), ENT_QUOTES, 'UTF-8');
    }

    foreach ($inputs as $key => $value) {
        $inputs[$key] = sanitize($value);
    }

    // Validasi field wajib
    $requiredFields = [
        'ikr_key',
        'group_ikr',
        'ikr_an',
        'alamat',
        'desa',
        'kec',
        'kab',
        'telp',
        'sn',
        'paket'
    ];

    foreach ($requiredFields as $field) {
        if (empty($inputs[$field])) {
            $_SESSION['alert'] = [
                'icon'   => 'error',
                'title'  => 'Oops!',
                'text'   => "Field <b>$field</b> tidak boleh kosong.",
                'button' => 'Coba Lagi',
                'style'  => 'danger'
            ];
            header("Location: " . BASE_URL . "pages/ikr/");
            exit;
        }
    }

    // Validasi tambahan sederhana
    if (!preg_match('/^[0-9]+$/', $telp)) {
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Oops!',
            'text'   => "Nomor telepon harus berupa angka.",
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/ikr/");
        exit;
    }

    if ($rt !== '' && !ctype_digit($rt)) {
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Oops!',
            'text'   => "RT harus berupa angka.",
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/ikr/");
        exit;
    }

    if ($rw !== '' && !ctype_digit($rw)) {
        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Oops!',
            'text'   => "RW harus berupa angka.",
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/ikr/");
        exit;
    }

    try {
        // Mulai transaksi
        $pdo->beginTransaction();

        // Update data di tabel ikr
        $sql = "UPDATE ikr SET
            group_ikr   = :group_ikr,
            ikr_an      = :ikr_an,
            alamat      = :alamat,
            rt          = :rt,
            rw          = :rw,
            desa        = :desa,
            kec         = :kec,
            kab         = :kab,
            telp        = :telp,
            sn          = :sn,
            paket       = :paket,
            type_ont    = :type_ont,
            redaman     = :redaman,
            odp_no      = :odp_no,
            odc_no      = :odc_no,
            jc_no       = :jc_no,
            mac_sebelum = :mac_sebelum,
            mac_sesudah = :mac_sesudah,
            odp         = :odp,
            odc         = :odc,
            enclosure   = :enclosure,
            paket_no    = :paket_no
        WHERE ikr_key = :ikr_key";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($inputs);

        // Commit
        $pdo->commit();

        $_SESSION['alert'] = [
            'icon'   => 'success',
            'title'  => 'Berhasil!',
            'text'   => 'Data IKR berhasil diperbarui.',
            'button' => 'Oke',
            'style'  => 'success'
        ];
        header("Location: " . BASE_URL . "pages/ikr/");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();

        $_SESSION['alert'] = [
            'icon'   => 'error',
            'title'  => 'Error!',
            'text'   => 'Gagal memperbarui data. Silakan coba lagi.',
            'button' => 'Coba Lagi',
            'style'  => 'danger'
        ];
        header("Location: " . BASE_URL . "pages/ikr/");
        exit;
    }
} else {
    $_SESSION['alert'] = [
        'icon' => 'warning',
        'title' => 'Oops!',
        'text' => 'Akses tidak valid.',
        'button' => "Oke",
        'style' => "warning"
    ];
    header("Location: " . BASE_URL . "pages/ikr/");
    exit;
}
