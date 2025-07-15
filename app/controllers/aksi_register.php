<?php
$errors = array();

// Validasi wajib
if (empty($_POST['registerRole'])) {
    $errors['registerRole'] = 'Role harus dipilih.';
}

if (empty($_POST['registerNama'])) {
    $errors['registerNama'] = 'Nama lengkap harus diisi.';
}

if (empty($_POST['registerEmail'])) {
    $errors['registerEmail'] = 'Email harus diisi.';
}

if (empty($_POST['registerPassword'])) {
    $errors['registerPassword'] = 'Password harus diisi.';
}

// Cek jika role adalah "pemilik" -> phone wajib
$role = $_POST['registerRole'] ?? '';
$phone_number = $_POST['registerPhone'] ?? '';

if ($role === 'pemilik' && empty($phone_number)) {
    $errors['registerPhone'] = 'Nomor HP wajib diisi untuk pemilik.';
}

// Jika validasi gagal
if (count($errors) > 0) {
    // Redirect kembali ke halaman register
    header("Location: ../../views/login/login.php");
    exit();
}

// Jika lolos validasi, simpan ke database
include "../../config/database.php";

// Ambil data
$nama_lengkap = $_POST['registerNama'];
$email = $_POST['registerEmail'];
$password = $_POST['registerPassword'];

// Simpan ke tabel users
$query = "INSERT INTO users (nama_lengkap, email, password, role, phone_number) 
          VALUES ('$nama_lengkap', '$email', '$password', '$role', '$phone_number')";

$result = mysqli_query($link, $query);

// TODO: kode error handling untuk duplikasi data email

if ($result) {
    // Berhasil register
    $message = 'Registrasi berhasil. Silakan login.';
    header("Location: ../views/login/login.php?login_message=" . urlencode($message)); // ke halaman login sukses
} else {

    exit;
    // Cek error duplikasi email
    if (mysqli_errno($link) == 1062) { // 1062 = Duplicate entry
        $errors['register'] = 'Email sudah terdaftar. Silakan gunakan email lain.';
        header("Location: ../views/login/login.php?register_error=" . urlencode($errors['register']));
        exit;
    } else {
        // Gagal query lain
        $errors['register'] = 'Gagal menyimpan data ke database.';
        header("Location: ../views/login/login.php?register_error=" . urlencode($errors['register']));
        exit;
    }
}
?>
