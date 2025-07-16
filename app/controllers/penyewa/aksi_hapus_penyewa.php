<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;
if (!isset($_GET['tenant_id']) || $user_id == 0) {
    header("Location: ../../views/login/login.php?login_error=" .
           urlencode("Anda harus login terlebih dahulu."));
    exit();
}

$tenant_id = intval($_GET['tenant_id']);

if ($tenant_id != 0) {
    $stmt = mysqli_prepare($link, "DELETE FROM tenants WHERE tenant_id = ? AND user_id = ?");
    mysqli_stmt_bind_param($stmt, "ii", $tenant_id, $user_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $pesan = "Penyewa berhasil dihapus.";
    } else {
        $pesan = "Gagal menghapus penyewa atau penyewa tidak ditemukan.";
    }
    mysqli_stmt_close($stmt);
    header("Location: ../../views/propertiku/penyewa.php?success=" . urlencode($pesan));
    exit();
}

// Redirect ke halaman penyewa dengan pesan error
header("Location: ../../views/propertiku/penyewa.php?error=" . urlencode("Tenant id ini tidak ditemukan"));

?>