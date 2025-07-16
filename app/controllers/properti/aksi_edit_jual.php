<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $selling_property_id = intval($_POST['selling_property_id'] ?? 0);

    // Cek user login
    if ($user_id == 0) {
        header("Location: ../../views/login/login.php?login_error=" . urlencode("Anda harus login terlebih dahulu."));
        exit();
    }

    // Validasi ID
    if ($selling_property_id <= 0) {
        header("Location: ../../views/propertiku/kelolaproperti.php?error=" . urlencode("ID properti tidak valid."));
        exit();
    }

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

    // Handle gambar baru (optional)
    $image_sql = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../../uploads/jual/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        $image_name = time() . "_" . basename($_FILES["image"]["name"]);
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_dir . $image_name);
        $image_sql = ", image = '$image_name'";
    }

    // Update DB
    $query = "UPDATE selling_properties SET
        property_name = '$property_name',
        sale_price = '$sale_price',
        price_per_month = '$price_per_month',
        location = '$location',
        bedrooms = '$bedrooms',
        bathrooms = '$bathrooms',
        land_area_size = '$land_area_size',
        building_area_size = '$building_area_size',
        certificate_type = '$certificate_type',
        electricity_power = '$electricity_power',
        floors = '$floors',
        garage = '$garage',
        property_condition = '$property_condition',
        description = '$description',
        facilities = '$facilities',
        updated_at = NOW()
        $image_sql
        WHERE selling_property_id = $selling_property_id AND user_id = $user_id";

    mysqli_query($link, $query) or die(mysqli_error($link));
    header("Location: ../../views/propertiku/kelolaproperti.php?success=edit");
    exit();
}
?>
