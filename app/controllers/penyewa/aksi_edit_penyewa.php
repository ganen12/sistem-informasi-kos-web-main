<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_id    = intval($_POST['tenant_id']);
    $name         = trim($_POST['nama']);
    $email        = trim($_POST['email']);
    $phone_number = trim($_POST['phone_number']);
    $address      = trim($_POST['address']);

    if ($tenant_id == 0 || $user_id == 0 || $name === '' || $email === '' ||
        $phone_number === '' || $address === '') {
        header("Location: ../../views/propertiku/penyewa.php?error=" .
               urlencode("Data tidak lengkap."));
        exit();
    }

    $sql = "UPDATE tenants SET name = ?, email = ?, phone_number = ?, address = ? 
            WHERE tenant_id = ? AND user_id = ?";
    
    // Validasi: hanya update penyewa milik user terkait
    $stmt = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt, "ssssii", $name, $email, $phone_number, $address, $tenant_id, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: ../../views/propertiku/penyewa.php?success=" . urlencode("Data penyewa berhasil diupdate."));
    exit();
}

header("Location: ../../views/propertiku/penyewa.php");
exit();
