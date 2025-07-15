<?php
$host = 'localhost';
$user = 'root';         // Change 
$password = '';         // Change
$dbname = 'huniandb';

// Create connection
$conn = new mysqli($host, $user, $password, $dbname);

$link = mysqli_connect($host, $user, $password, $dbname);
if (!$link) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Check connection


// Optional: set charset
$conn->set_charset('utf8mb4');
?>