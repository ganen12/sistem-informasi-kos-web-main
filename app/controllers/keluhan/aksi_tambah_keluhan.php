<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ────── 1. Ambil & rapikan data dari form ────── */
    $tenant_id    = $_POST['tenant_id'] ?? '';
    $complaint_date = $_POST['complaint_date']  ?? date('Y-m-d');
    $complaint_description = trim($_POST['complaint_description'] ?? '');
    $status      = trim($_POST['status'] ?? '');

    /* ────── 2. Validasi sederhana ────── */
    if ($user_id == 0 || $tenant_id == '' || $complaint_date == '' ||
        $complaint_description == '' || $status == '') {

        header("Location: ../../views/propertiku/keluhan.php?error=" .
               urlencode(
               "Data tidak lengkap atau Anda belum login."));
        exit();
    }

    /* ────── 4. Simpan ke tabel complains ────── */
    $sql = "INSERT INTO complaints (complaint_date, complaint_description, status, tenant_id)
        VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt, "sssi",  // 3 string, 1 integer
        $complaint_date, $complaint_description, $status, $tenant_id
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    /* ────── 5. Beri notifikasi sukses ────── */
    header("Location: ../../views/propertiku/keluhan.php?success=" .
           urlencode("Data keluhan berhasil ditambah"));
    exit();
}

/* Jika bukan POST */
header("Location: ../../views/propertiku/keluhan.php");
exit();
?>
