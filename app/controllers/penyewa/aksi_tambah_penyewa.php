<?php
session_start();
include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ────── 1. Ambil & rapikan data dari form ────── */
    $name         = trim($_POST['nama']         ?? '');
    $email        = trim($_POST['email']        ?? '');
    $phone_number = trim($_POST['phone_number'] ?? '');
    $address      = trim($_POST['address']      ?? '');

    /* ────── 2. Validasi sederhana ────── */
    if ($user_id == 0 || $name === '' || $email === '' ||
        $phone_number === '' || $address === '') {

        header("Location: ../../views/propertiku/penyewa.php?error=" .
               urlencode("Data tidak lengkap atau Anda belum login."));
        exit();
    }

    /* ────── 3. Cek duplikasi e‑mail ────── */
    $cek = mysqli_prepare($link,
        "SELECT 1 FROM tenants WHERE email = ? LIMIT 1"
    );
    mysqli_stmt_bind_param($cek, "s", $email);
    mysqli_stmt_execute($cek);
    mysqli_stmt_store_result($cek);

    if (mysqli_stmt_num_rows($cek) > 0) {
        mysqli_stmt_close($cek);
        header("Location: ../../views/propertiku/penyewa.php?error=" .
               urlencode("Email sudah terdaftar sebagai penyewa."));
        exit();
    }
    mysqli_stmt_close($cek);

    /* ────── 4. Simpan ke tabel tenants ────── */
    $sql = "INSERT INTO tenants (name, email, phone_number, address, user_id)
        VALUES (?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($link, $sql);

    mysqli_stmt_bind_param($stmt, "ssssi",  // 4 string, 1 integer
        $name, $email, $phone_number, $address, $user_id
    );
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    /* ────── 5. Beri notifikasi sukses ────── */
    header("Location: ../../views/propertiku/penyewa.php?success=" .
           urlencode("Penyewa berhasil ditambah"));
    exit();
}

/* Jika bukan POST */
header("Location: ../../views/propertiku/penyewa.php");
exit();
?>
