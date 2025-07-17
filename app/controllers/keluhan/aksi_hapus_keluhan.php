<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;
if (!isset($_GET['complaint_id']) || $user_id == 0) {
    header("Location: ../../views/login/login.php?login_error=" .
           urlencode("Anda harus login terlebih dahulu."));
    exit();
}

$complaint_id = intval($_GET['complaint_id']);

if ($complaint_id != 0) {
    $stmt = mysqli_prepare($link, "DELETE FROM complaints WHERE complaint_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $complaint_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $pesan = "Data keluhan berhasil dihapus.";
    } else {
        $pesan = "Gagal menghapus keluhan atau keluhan tidak ditemukan.";
    }
    mysqli_stmt_close($stmt);
    header("Location: ../../views/propertiku/keluhan.php?success=" . urlencode($pesan));
    exit();
}

// Redirect ke halaman keluhan dengan pesan error
header("Location: ../../views/propertiku/keluhan.php?error=" . urlencode("Keluhan id ini tidak ditemukan"));

?>