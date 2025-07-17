<?php
require_once '../../../config/database.php';

$payment_id = $_POST['payment_id'] ?? null;

if (!$payment_id) {
  die('ID pembayaran tidak ditemukan.');
}

$query = "DELETE FROM payments WHERE payment_id = ?";
$stmt = mysqli_prepare($link, $query);
mysqli_stmt_bind_param($stmt, 'i', $payment_id);

if (!mysqli_stmt_execute($stmt)) {
  die("Gagal menghapus pembayaran: " . mysqli_error($link));
}

header("Location: ../../views/propertiku/pembayaran.php?success=" . urlencode("pembayaran berhasil dihapus"));
exit;
