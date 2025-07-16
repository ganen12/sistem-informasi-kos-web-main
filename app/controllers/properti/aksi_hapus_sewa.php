<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($user_id == 0) {
    header("Location: ../../views/login/login.php?login_error=" . urlencode("Anda harus login terlebih dahulu."));
    exit();
}

$id = intval($_POST['id'] ?? 0);

if ($id > 0) {
    $query = "DELETE FROM rental_properties WHERE rental_property_id = $id AND user_id = $user_id";
    mysqli_query($link, $query) or die(mysqli_error($link));
}

header("Location: ../../views/propertiku/kelolaproperti.php?success=hapus_sewa");
exit();
?>
