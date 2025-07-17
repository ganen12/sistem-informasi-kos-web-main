<?php
function require_login() {
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['status'] != 'Login') {
        header("Location: ../login/login.php?login_error=" . urlencode("Anda harus login terlebih dahulu."));
        exit();
    }
}

function require_role($required_role) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] != $required_role) {
        header("Location: ../login/login.php?login_error=" . urlencode("Akses ditolak. Peran tidak sesuai."));
        exit();
    }
}
?>