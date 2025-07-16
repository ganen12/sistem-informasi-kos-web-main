<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $rental_property_id = intval($_POST['rental_property_id'] ?? 0);

    // Pastikan user login
    if ($user_id == 0 || $rental_property_id == 0) {
        header("Location: ../../views/login/login.php?login_error=" . urlencode("Anda harus login terlebih dahulu."));
        exit();
    }

    // Ambil input form
    $property_name = $_POST['property_name'];
    $property_type = $_POST['property_type'];
    $rental_duration = $_POST['rental_duration'];
    $rental_price = $_POST['rental_price'];
    $facilities = $_POST['facilities'];
    $location = $_POST['location'] ?? '';

    // Cek gambar baru
    $image_update_sql = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../uploads/sewa/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);

        $image_update_sql = ", image = '$image_name'";
    }

    // Update ke database
    $query = "
        UPDATE rental_properties 
        SET 
            property_name = '$property_name',
            location = '$location',
            property_type = '$property_type',
            rental_duration = '$rental_duration',
            rental_price = '$rental_price',
            facilities = '$facilities'
            $image_update_sql,
            updated_at = NOW()
        WHERE rental_property_id = $rental_property_id AND user_id = $user_id
    ";

    mysqli_query($link, $query) or die(mysqli_error($link));
    header("Location: ../../views/propertiku/kelolaproperti.php?success=edit_sewa");
    exit();
}
?>
