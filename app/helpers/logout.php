<?php
session_start();
session_unset();
session_destroy();

// Redirect ke halaman login
header("Location: ../views/login/login.php?logout=1");
exit();
?>