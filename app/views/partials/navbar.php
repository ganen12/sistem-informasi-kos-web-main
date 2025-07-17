<?php
if (session_status() === PHP_SESSION_NONE) session_start();

$isLoggedIn = isset($_SESSION['user_id']);
$namaLengkap = $_SESSION['nama_lengkap'] ?? 'User';

$currentFile = basename($_SERVER['SCRIPT_NAME']);
function isActive($filename) {
    return basename($_SERVER['SCRIPT_NAME']) === $filename ? 'active' : '';
}

// Link dashboard sesuai status login
$dashboardLink = $isLoggedIn
    ? '../dashboard/dashboardpemilik.php'
    : '../LandingPage/dashboard.php';
?>
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-warning" href="<?= $dashboardLink ?>">Hunian.id</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link <?= isActive('../dashboard/dashboardpemilik.php') || isActive('../LandingPage/dashboard.php') ? 'active' : '' ?>" href="<?= $dashboardLink ?>">Beranda</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= isActive('Beli.php') ?>" href="../eksplor/Beli.php">Beli</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= isActive('Sewa.php') ?>" href="../eksplor/Sewa.php">Sewa</a>
        </li>

        <?php if ($isLoggedIn): ?>
        <li class="nav-item">
          <a class="nav-link <?= isActive('propertiku.php') ?>" href="../propertiku/propertiku.php">Propertiku</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
            <i class="bi bi-person-circle me-2"></i> <?= htmlspecialchars($namaLengkap) ?>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li><a class="dropdown-item" href="#"><i class="bi bi-bookmark-heart me-2"></i> Tersimpan</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i> Terakhir Dilihat</a></li>
            <li><a class="dropdown-item" href="#"><i class="bi bi-chat-dots me-2"></i> Forum Pemilik</a></li>
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
    <div class="modal-content border-0">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">Apakah Anda yakin ingin keluar dari akun Anda?</div>
      <div class="modal-footer justify-content-start">
        <a href="../../helpers/logout.php" class="btn btn-danger">Logout</a>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>
