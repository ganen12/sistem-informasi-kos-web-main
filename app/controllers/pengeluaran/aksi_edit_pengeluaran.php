<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ────── 1. Ambil & rapikan data dari form ────── */
    $expense_id = intval($_POST['expense_id']);
    $expense_date = $_POST['expense_date'] ?? date('Y-m-d');
    $expense_total = intval($_POST['expense_total']) ?? 0;
    $description = $_POST['description']  ?? '';

    /* ────── 2. Validasi sederhana ────── */
    if ($user_id == 0 || $expense_id == '' || $description == '' ) {

        header("Location: ../../views/propertiku/pengeluaran.php?error=" .
               urlencode(
               "Data tidak lengkap atau Anda belum login."));
        exit();
    }

    $sql = "UPDATE expenses SET expense_date = ?, description = ?, expense_total = ? WHERE expense_id = ?";
    

    $stmt = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt, "ssii", $expense_date, $description, $expense_total, $expense_id); 
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: ../../views/propertiku/pengeluaran.php?success=" . urlencode("Data pengeluaran berhasil diupdate. Statusnya " . $status));
    exit();
}

header("Location: ../../views/propertiku/pengeluaran.php");
exit();
