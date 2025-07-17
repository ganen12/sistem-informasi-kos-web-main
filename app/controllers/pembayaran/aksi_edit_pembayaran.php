<?php
require_once '../../../config/database.php';

$payment_id        = $_POST['payment_id'] ?? null;
$payment_date      = $_POST['payment_date'] ?? null;
$payment_total     = $_POST['payment_total'] ?? null;
$amount_paid       = $_POST['amount_paid'] ?? null;
$payment_due_date  = $_POST['payment_due_date'] ?? null;
$status            = $_POST['status'] ?? null;

if (!$payment_id || !$payment_date || !$payment_total || !$amount_paid || !$payment_due_date || !$status) {
  die('Semua data wajib diisi.');
}

$query = "UPDATE payments SET 
            payment_date = ?, 
            payment_total = ?, 
            amount_paid = ?, 
            payment_due_date = ?, 
            status = ?, 
            updated_at = NOW()
          WHERE payment_id = ?";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'sddssi',
  $payment_date, 
  $payment_total, 
  $amount_paid, 
  $payment_due_date, 
  $status, 
  $payment_id
);

if (!mysqli_stmt_execute($stmt)) {
  die("Gagal update pembayaran: " . mysqli_error($link));
}

header("Location: ../../views/propertiku/pembayaran.php?success=" . urlencode("pembayaran berhasil diupdate"));
exit;
