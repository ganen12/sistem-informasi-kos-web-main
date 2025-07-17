<?php
include '../../../config/database.php';

$room_transaction_id = $_POST['room_transaction_id'];
$payment_date        = $_POST['payment_date'];
$payment_total       = $_POST['payment_total'];
$amount_paid         = $_POST['amount_paid'];
$payment_due_date    = $_POST['payment_due_date'];
$status              = $_POST['status'];

// Cek validasi sederhana
if (
  !$room_transaction_id || !$payment_date || !$payment_total || 
  !$amount_paid || !$payment_due_date || !$status
) {
  die("Semua data harus diisi.");
}

// Insert ke tabel payments
$query = "INSERT INTO payments (
  payment_date, payment_total, amount_paid, payment_due_date, status
) VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, "siiss", 
  $payment_date, 
  $payment_total, 
  $amount_paid, 
  $payment_due_date, 
  $status
);

if (!mysqli_stmt_execute($stmt)) {
  die("Gagal simpan pembayaran: " . mysqli_error($link));
}

$payment_id = mysqli_insert_id($link);

// Update payment_id di room_transactions
$updateTrx = "UPDATE room_transactions SET payment_id = ?, status = ? WHERE room_transaction_id = ?";
$stmt2 = mysqli_prepare($link, $updateTrx);
mysqli_stmt_bind_param($stmt2, "isi", $payment_id, $status, $room_transaction_id);
mysqli_stmt_execute($stmt2);

header("Location: ../../views/propertiku/pembayaran.php?success=" . urlencode("pembayaran berhasil ditambah"));
exit;
