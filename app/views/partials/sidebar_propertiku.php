<nav class="col-md-2 sidebar d-flex flex-column p-3">
  <ul class="nav flex-column mb-auto">
      <li class="nav-item">
        <a href="propertiku.php" class="nav-link<?= ($activeMenu ?? '') === 'dashboard' ? ' active' : '' ?>"><i class="bi bi-box-arrow-right me-2 menu-list"></i>Dashboard</a>
      </li>
      <li class="mt-4 section-label">Data</li>
      <li><a href="kelolaproperti.php" class="nav-link<?= ($activeMenu ?? '') === 'properti' ? ' active' : '' ?>"><i class="bi bi-house-door me-2 menu-list"></i>Properti</a></li>
      <li><a href="kamar.php" class="nav-link<?= ($activeMenu ?? '') === 'kamar' ? ' active' : '' ?>"><i class="bi bi-door-closed me-2 menu-list"></i>Kamar</a></li>
      <li><a href="penyewa.php" class="nav-link<?= ($activeMenu ?? '') === 'penyewa' ? ' active' : '' ?>"><i class="bi bi-people-fill me-2 menu-list"></i>Penyewa</a></li>
      <li class="mt-4 section-label">Transaksi</li>
      <li><a href="pemesanan.php" class="nav-link<?= ($activeMenu ?? '') === 'pemesanan' ? ' active' : '' ?>"><i class="bi bi-book me-2 menu-list"></i>Pemesanan</a></li>
      <li><a href="pembayaran.php" class="nav-link<?= ($activeMenu ?? '') === 'pembayaran' ? ' active' : '' ?>"><i class="bi bi-receipt me-2 menu-list"></i>Pembayaran</a></li>
      <li><a href="pengeluaran.php" class="nav-link<?= ($activeMenu ?? '') === 'pengeluaran' ? ' active' : '' ?>"><i class="bi bi-stack me-2 menu-list"></i>Pengeluaran</a></li>
      <li class="mt-4 section-label">Lainnya</li>
      <li><a href="keluhan.php" class="nav-link<?= ($activeMenu ?? '') === 'keluhan' ? ' active' : '' ?>"><i class="bi bi-exclamation-triangle me-2 menu-list"></i>Keluhan</a></li>
  </ul>
</nav>



<!-- TODO: SIDEBAR DENGAN LOGOUT -->

<!-- <nav class="col-md-2 sidebar d-flex flex-column p-3">
      <div class="mb-4">
        <h5><i class="bi bi-house-door-fill me-2"></i>Kos Husni</h5>
      </div>
      <ul class="nav flex-column mb-auto">
        <li><a href="index.html" class="nav-link"><i class="bi bi-house-door me-2 menu-list"></i>Dashboard</a></li>
        <li class="mt-4 text-uppercase small section-label">Data</li>
        <li><a href="kamar.html" class="nav-link"><i class="bi bi-door-closed me-2 menu-list"></i>Kamar</a></li>
        <li><a href="penyewa.html" class="nav-link"><i class="bi bi-people-fill me-2 menu-list"></i>Penyewa</a></li>
        <li class="mt-4 text-uppercase small section-label">Transaksi</li>
        <li><a href="pembayaran.html" class="nav-link"><i class="bi bi-receipt me-2 menu-list"></i>Pembayaran</a></li>
        <li><a href="pengeluaran.html" class="nav-link"><i class="bi bi-stack me-2 menu-list"></i>Pengeluaran</a></li>
        <li class="mt-4 text-uppercase small section-label">Lainnya</li>
        <li><a href="keluhan.html" class="nav-link active"><i class="bi bi-exclamation-triangle me-2 menu-list"></i>Keluhan</a></li>
      </ul>
      <div class="mt-auto">
        <ul class="nav flex-column mb-auto">
            <li class="">
                <a href="#" class="nav-link text-danger" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="bi bi-box-arrow-right me-2 menu-list"></i>Logout
                </a>
            </li>
        </ul>
      </div>
    </nav> -->