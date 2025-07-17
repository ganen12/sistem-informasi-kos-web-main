<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ────── 1. Ambil & rapikan data dari form ────── */
    $tenant_id    = intval($_POST['tenant_id']);
    $complaint_id = intval($_POST['complaint_id']);
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

    $sql = "UPDATE complaints SET complaint_date = ?, complaint_description = ?, status = ?, tenant_id = ?  WHERE complaint_id = ?";
    

    $stmt = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt, "sssii", $complaint_date, $complaint_description, $status, $tenant_id, $complaint_id); 
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: ../../views/propertiku/keluhan.php?success=" . urlencode("Data keluhan berhasil diupdate. Statusnya " . $status));
    exit();
}

header("Location: ../../views/propertiku/keluhan.php");
exit();
