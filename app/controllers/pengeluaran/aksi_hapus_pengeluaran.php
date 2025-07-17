<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;
if (!isset($_GET['expense_id']) || $user_id == 0) {
    header("Location: ../../views/login/login.php?login_error=" .
           urlencode("Anda harus login terlebih dahulu."));
    exit();
}

$expense_id = intval($_GET['expense_id']);

if ($expense_id != 0) {
    $stmt = mysqli_prepare($link, "DELETE FROM expenses WHERE expense_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $expense_id);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $pesan = "Data pengeluaran berhasil dihapus.";
    } else {
        $pesan = "Gagal menghapus pengeluaran atau pengeluaran tidak ditemukan.";
    }
    mysqli_stmt_close($stmt);
    header("Location: ../../views/propertiku/pengeluaran.php?success=" . urlencode($pesan));
    exit();
}

// Redirect ke halaman pengeluaran dengan pesan error
header("Location: ../../views/propertiku/pengeluaran.php?error=" . urlencode("pengeluaran id ini tidak ditemukan"));

?>