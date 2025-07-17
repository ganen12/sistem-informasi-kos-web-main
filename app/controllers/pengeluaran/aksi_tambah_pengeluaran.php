<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ────── 1. Ambil & rapikan data dari form ────── */
    $expense_date    = $_POST['expense_date'] ?? '';
    $description = $_POST['description']  ?? '';
    $expense_total = trim($_POST['expense_total'] ?? '');

    /* ────── 2. Validasi sederhana ────── */
    if ($user_id == 0 || $expense_date == '' || $description == '' ||
        $expense_total == '') {

        header("Location: ../../views/propertiku/pengeluaran.php?error=" .
               urlencode(
               "Data tidak lengkap atau Anda belum login."));
        exit();
    }

    /* ────── 4. Simpan ke tabel complains ────── */
    $sql = "INSERT INTO expenses (expense_date, description, expense_total, user_id)
        VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt, "ssii",  // 2 string, 2 integer
        $expense_date, $description, $expense_total, $user_id
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    /* ────── 5. Beri notifikasi sukses ────── */
    header("Location: ../../views/propertiku/pengeluaran.php?success=" .
           urlencode("Data pengeluaran berhasil ditambah"));
    exit();
}

/* Jika bukan POST */
header("Location: ../../views/propertiku/pengeluaran.php");
exit();
?>
