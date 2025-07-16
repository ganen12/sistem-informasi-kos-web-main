
<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $rental_property_id = $_POST['rental_property_id'] ?? '';
    $room_no = $_POST['room_no'] ?? '';
    $status = $_POST['status'] ?? 'Tersedia';
    $price_per_month = $_POST['price_per_month'] ?? 0;

    // Validasi sederhana
    if ($user_id == 0 || !$rental_property_id || !$room_no) {
        header("Location: ../../views/propertiku/kamar.php?error=" . urlencode("Data tidak lengkap atau belum login."));
        exit();
    }

    // Cek duplikasi room_no pada properti yang sama
    $cek = mysqli_query($link, "SELECT 1 FROM rooms WHERE room_no='$room_no' AND rental_property_id='$rental_property_id' LIMIT 1");
    if (mysqli_num_rows($cek) > 0) {
        header("Location: ../../views/propertiku/kamar.php?error=" . urlencode("Nomor kamar sudah terdaftar pada properti ini."));
        exit();
    }
    // TODO: tambah prepared statement untuk keamanan 
    // Insert ke tabel kamar
    $query = "INSERT INTO rooms (room_no, status, price_per_month, rental_property_id, created_at, updated_at)
              VALUES ('$room_no', '$status', '$price_per_month', '$rental_property_id', NOW(), NOW())";
    mysqli_query($link, $query) or die(mysqli_error($link));

    header("Location: ../../views/propertiku/kamar.php?success=" . urlencode("Kamar berhasil ditambah"));
    exit();
}
?>