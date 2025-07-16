<?php
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
        $user = mysqli_fetch_assoc($result); 

        $_SESSION['status'] = 'Login';
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama_lengkap'] = $user['nama_lengkap']; 
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

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

