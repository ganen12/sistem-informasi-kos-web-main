<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $room_no = $_POST['room_no'] ?? '';
    $old_room_no = $_POST['room_no'] ?? '';
    $new_room_no = $_POST['room_no'] ?? '';
    $rental_property_id = $_POST['rental_property_id'] ?? '';
    $status = $_POST['status'] ?? '';
    $price_per_month = $_POST['price_per_month'] ?? 0;

    // Validasi sederhana
    if ($user_id == 0 || !$room_no || !$rental_property_id) {
        header("Location: ../../views/propertiku/kamar.php?error=" . urlencode("Data tidak lengkap atau belum login."));
        exit();
    }

    // Cek duplikasi room_no pada properti yang sama
    // $cek = mysqli_query($link, "SELECT 1 FROM rooms WHERE room_no='$room_no' AND rental_property_id='$rental_property_id' LIMIT 1");
    // if (mysqli_num_rows($cek) > 0) {
    //     header("Location: ../../views/propertiku/kamar.php?error=" . urlencode("Nomor kamar sudah terdaftar pada properti ini."));
    //     exit();
    // }

    // Pastikan kamar yang akan diupdate milik user yang sedang login
    $cek = mysqli_query($link, "SELECT r.room_no FROM rooms r
        JOIN rental_properties p ON r.rental_property_id = p.rental_property_id
        WHERE r.room_no = '$old_room_no' AND r.rental_property_id = '$rental_property_id' AND p.user_id = '$user_id'
        LIMIT 1");
    if (mysqli_num_rows($cek) == 0) {
        header("Location: ../../views/propertiku/kamar.php?error=" . urlencode("Kamar tidak ditemukan atau bukan milik Anda."));
        exit();
    }

    // Update data kamar, termasuk room_no
    $query = "UPDATE rooms SET 
                room_no = '$new_room_no',
                status = '$status',
                price_per_month = '$price_per_month',
                rental_property_id = '$rental_property_id',
                updated_at = NOW()
              WHERE room_no = '$old_room_no'";

    if (mysqli_query($link, $query)) {
        header("Location: ../../views/propertiku/kamar.php?success=" . urlencode("Kamar berhasil diupdate"));
        exit();
    } else {
        header("Location: ../../views/propertiku/kamar.php?error=" . urlencode("Gagal update kamar: " . mysqli_error($link)));
        exit();
    }
}
header("Location: ../../views/propertiku/kamar.php");
exit();