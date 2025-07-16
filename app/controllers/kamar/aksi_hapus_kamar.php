<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;
if ($user_id == 0) {
    header("Location: ../../views/login/login.php?login_error=" .
           urlencode("Anda harus login terlebih dahulu."));
    exit();
}

$room_no = $_POST['room_no'] ?? $_GET['room_no'] ?? '';

if ($room_no !== '') {
    /* hapus kamar milik user yang sedang login */
    $sql = "
        DELETE r
        FROM rooms AS r
        JOIN rental_properties AS p
          ON r.rental_property_id = p.rental_property_id
        WHERE r.room_no = ?
          AND p.user_id = ?
    ";               // ← LIMIT 1 dihapus

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "si", $room_no, $user_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: ../../views/propertiku/kamar.php?success=hapus_kamar");
    exit();
}

/* jika parameter kosong */
header("Location: ../../views/propertiku/kamar.php?error=room_kosong");
exit();
?>
