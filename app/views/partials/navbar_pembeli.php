<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$isLoggedIn = isset($_SESSION['user_id']);
$namaLengkap = $_SESSION['nama_lengkap'] ?? 'User';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=
    , initial-scale=1.0">
    <title>Navbar</title>
</head>
<body>
    
<!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-warning" href="../dashboard/dashboardpembeli.php">Hunian.id</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'dashboardpembeli.php' ? 'active' : '' ?>" href="../dashboard/dashboardpembeli.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pembeli_beli.php' ? 'active' : '' ?>" href="../eksplor pembeli/pembeli_beli.php">Beli</a>
          </li>
          <li class="nav-item">
            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'pembeli_sewa.php' ? 'active' : '' ?>" href="../eksplor pembeli/pembeli_sewa.php">Sewa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Iklankan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Bantuan</a>
          </li>

          <?php if ($isLoggedIn): ?>
            <!-- <li class="nav-item">
              <a class="nav-link nav-link <?= basename($_SERVER['PHP_SELF']) == 'propertiku.php' ? 'active' : '' ?>" href="../propertiku/propertiku.php">Propertiku</a>
            </li> -->
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle me-2"></i> <?= htmlspecialchars($namaLengkap) ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="#"><i class="bi bi-bookmark-heart me-2"></i> Tersimpan</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i> Terakhir Dilihat</a></li>
                <li><a class="dropdown-item" href="#"><i class="bi bi-chat-dots me-2"></i> Forum Pembeli</a></li>
                <li><hr class="dropdown-divider"></li>
                <li>
                <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right me-2"></i> Logout
                </a>
                </li>
              </ul>
            </li>
            <?php else: ?>
            <li class="nav-item">
                <a class="nav-link" href="../login/login.php">
                <i class="bi bi-box-arrow-in-right me-2"></i> Login
                </a>
            </li>
            <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
        <div class="modal-header border-0">
            <h5 class="modal-title" id="logoutModalLabel"><i class="bi bi-exclamation-triangle text-warning me-2"></i>Konfirmasi Logout</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
            <p>Apakah Anda yakin ingin logout?</p>
        </div>
        <div class="modal-footer border-0 justify-content-center">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            <a href="../../helpers/logout.php" class="btn btn-danger">Logout</a>
        </div>
        </div>
    </div>
    </div>
    

</body>
</html>

