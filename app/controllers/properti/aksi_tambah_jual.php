<?php
session_start();

include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;  // pastikan session login simpan id user

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil semua input
    $property_name = $_POST['property_name'];
    $sale_price = $_POST['sale_price'];
    $price_per_month = $_POST['price_per_month'] ?? null;
    $bedrooms = $_POST['bedrooms'] ?? 0;
    $bathrooms = $_POST['bathrooms'] ?? 0;
    $land_area_size = $_POST['land_area_size'] ?? 0;
    $building_area_size = $_POST['building_area_size'] ?? 0;
    $certificate_type = $_POST['certificate_type'] ?? '';
    $electricity_power = $_POST['electricity_power'] ?? 0;
    $floors = $_POST['floors'] ?? 0;
    $garage = $_POST['garage'] ?? 0;
    $property_condition = $_POST['property_condition'] ?? '';
    $description = $_POST['description'] ?? '';
    $facilities = $_POST['facilities'] ?? '';
    $location = $_POST['location'] ?? '';

    // Upload gambar
    $image_name = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../uploads/jual/";
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
    }

    // Validasi jika user belum login
    if ($user_id == 0) {
        header("Location: ../../views/login/login.php?login_error=" . urlencode("Anda harus login terlebih dahulu."));
        exit();
    }

    // Insert ke database
    $query = "INSERT INTO selling_properties 
    (user_id, property_name, sale_price, price_per_month, image, bedrooms, bathrooms, land_area_size, building_area_size, certificate_type, electricity_power, floors, garage, property_condition, description, facilities, location, created_at, updated_at) 
    VALUES 
    ('$user_id', '$property_name', '$sale_price', '$price_per_month', '$image_name', '$bedrooms', '$bathrooms', '$land_area_size', '$building_area_size', '$certificate_type', '$electricity_power', '$floors', '$garage', '$property_condition', '$description', '$facilities', $location, NOW(), NOW())";

    mysqli_query($link, $query) or die(mysqli_error($link));
    header("Location: ../../views/propertiku/kelolaproperti.php?success=jual");
    exit();
}
?>
