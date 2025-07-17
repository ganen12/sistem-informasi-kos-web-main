<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tenant_id        = $_POST['tenant_id']        ?? '';
    $room_no          = $_POST['room_no']          ?? '';
    $rental_duration  = $_POST['rental_duration']  ?? 1;
    // registration date 
    $registration_date = $_POST['registration_date'] ?? date('Y-m-d');

    // Validasi
    if ($user_id == 0 || $tenant_id === '' || $room_no === '') {
        header("Location: ../../views/propertiku/pemesanan.php?error=" . urlencode("Data tidak lengkap atau Anda belum login."));
        exit();
    }

    // Cek apakah kamar tersedia dan milik user
    $cekRoom = mysqli_prepare($link, "
        SELECT 1 FROM rooms r
        JOIN rental_properties p ON r.rental_property_id = p.rental_property_id
        WHERE r.room_no = ? AND r.status = 'Tersedia' AND p.user_id = ?
    ");
    mysqli_stmt_bind_param($cekRoom, "si", $room_no, $user_id);
    mysqli_stmt_execute($cekRoom);
    mysqli_stmt_store_result($cekRoom);

    if (mysqli_stmt_num_rows($cekRoom) == 0) {
        mysqli_stmt_close($cekRoom);
        header("Location: ../../views/propertiku/pemesanan.php?error=" . urlencode("Kamar tidak tersedia atau bukan milik Anda."));
        exit();
    }
    mysqli_stmt_close($cekRoom);

    // Insert pemesanan
    $stmt = mysqli_prepare($link, "
        INSERT INTO room_transactions (registration_date, rental_duration, tenant_id, room_no)
        VALUES (?, ?, ?, ?)
    ");
    mysqli_stmt_bind_param($stmt, "ssis", $registration_date, $rental_duration, $tenant_id, $room_no);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Update status kamar ke "Disewa"
    $update = mysqli_prepare($link, "
        UPDATE rooms SET status = 'Disewa' WHERE room_no = ?
    ");
    mysqli_stmt_bind_param($update, "s", $room_no);
    mysqli_stmt_execute($update);
    mysqli_stmt_close($update);

    // Redirect success
    header("Location: ../../views/propertiku/pemesanan.php?success=" . urlencode("Pemesanan berhasil disimpan."));
    exit();
}

header("Location: ../../views/propertiku/pemesanan.php");
exit();
