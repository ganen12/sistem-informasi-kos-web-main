<?php
require_once 'test_db_connection.php';

header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if (!$email || !$password) {
  http_response_code(400);
  echo json_encode(['error' => 'Email dan password wajib diisi']);
  exit;
}

$pdo = connectDB();

$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user || !password_verify($password, $user['password'])) {
  http_response_code(401);
  echo json_encode(['error' => 'Email atau password salah']);
  exit;
}

// Simpan session
$_SESSION['user'] = [
  'id' => $user['id'],
  'nama' => $user['nama_lengkap'],
  'email' => $user['email'],
  'role' => $user['role']
];

echo json_encode(['success' => true, 'message' => 'Login berhasil']);
