<?php
require_once 'test_db_connection.php';
session_destroy();
header('Content-Type: application/json');
echo json_encode(['success' => true, 'message' => 'Logout berhasil']);
