d<?php
$errors = array();

if (empty($_POST['loginEmail'])) {
    $errors['loginEmail'] = 'Email harus diisi.';
}

if (empty($_POST['loginPassword'])) {
    $errors['loginPassword'] = 'Password harus diisi.';
}


// proses login jika tidak ada error
if (count($errors) == 0) {
    include "../../config/database.php";

    // Ambil data dari form login
    $username = $_POST['loginEmail'];
    $password = $_POST['loginPassword'];
    $role = $_POST['role'];

    // output debug
    echo "Username: $username, Password: $password, Role: $role";

    // Query untuk memeriksa data pengguna
    $query = "SELECT * FROM users WHERE email = '$username' AND password = '$password' AND role = '$role'";
    $result = mysqli_query($link, $query);


    // Cek hasil query
    if (mysqli_num_rows($result) == 1) {
        // Login berhasil
        session_start(); // mulai session (cookie)
        $_SESSION['status'] = 'Login'; // key value simpel: status=login
        $_SESSION['user_id'] = mysqli_fetch_assoc($result)['id']; // ambil id user dari hasil query
        $_SESSION['username'] = $username; // simpan username ke session
        $_SESSION['role'] = $role; // simpan role ke session

                                    // contoh lain seperti durasi session ...

        if ($role == 'pemilik') {
            header("Location: ../views/dashboard/dashboardpemilik.php");
        } else if ($role == 'pembeli') {
            header("Location: ../views/dashboard/dashboardpembeli.php");
        }
    } else {
        // Login gagal, redirect kembali ke halaman login
        $errors['login'] = 'Email, password, atau role salah.';
        header("Location: ../views/login/login.php?login_error=" . urlencode($errors['login']));

        // header("Location: ../views/LOGINN1.php");
        // echo "Username: $username, Password: $password, Role: $role";
    }
} else {
    // Jika terdapat error, kembali ke halaman form dengan menampilkan error
    $errors['login'] = 'Email, password, atau role salah.';
    header("Location: ../views/login/login.php?login_error=" . urlencode($errors['login']));
    exit;
}
?>

