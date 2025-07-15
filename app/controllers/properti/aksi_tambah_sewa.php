<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $property_name = $_POST['property_name'];
    $property_type = $_POST['property_type'];
    $rental_duration = $_POST['rental_duration'];
    $rental_price = $_POST['rental_price'];
    $facilities = $_POST['facilities'] ?? '';

    // Upload gambar
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../uploads/sewa";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
    }

    $query = "INSERT INTO rental_properties 
    (user_id, property_type, property_name, rental_duration, rental_price, facilities, image, created_at, updated_at) 
    VALUES 
    ('$user_id', '$property_type', '$property_name', '$rental_duration', '$rental_price', '$facilities', '$image_name', NOW(), NOW())";

    mysqli_query($link, $query) or die(mysqli_error($link));
    header("Location: ../../views/propertiku/kelolaproperti.php?success=sewa");
    exit();
}
?>
