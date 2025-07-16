<?php
function require_login() {
    session_start();
    if (!isset($_SESSION['status']) || $_SESSION['status'] != 'Login') {
        header("Location: ../login/login.php?login_error=" . urlencode("Anda harus login terlebih dahulu."));
        exit();
    }
}
?>