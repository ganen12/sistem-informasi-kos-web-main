<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hunian.id - Sewa Kost, Rumah, dan Kontrakan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
    .hero {
        background: url('https://source.unsplash.com/1600x600/?house,building') center/cover no-repeat;
        color: white;
        padding: 5rem 2rem;
    }
    .hero h1 {
        font-weight: 700;
    }
    .sidebar {
        height: 100vh;
        background-color: #252321;
        color: white;
    }
    .sidebar .nav-link {
        color: #ccc;
    }
    .sidebar .nav-link:hover,
    .sidebar .nav-link.active {
        background-color: #32302D;
        color: white;
    }
    .section-label {
        color: rgba(238, 237, 235, 0.3);
        font-size: 12px;
        text-transform: uppercase;
    }
    .menu-list {
        font-size: 1rem;
    }
    .card-icon {
        font-size: 1.8rem;
    }
    .card-stat {
        min-height: 80px;
    }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-warning" href="#">Hunian.id</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="dashboardpemilik.php">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="Beli.php">Beli</a></li>
          <li class="nav-item"><a class="nav-link" href="Sewa.php">Sewa</a></li>
          <li class="nav-item"><a class="nav-link active" href="#">Propertiku</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Bantuan</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                    <i class="bi bi-person-circle me-2"></i> Seller
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-bookmark-heart me-2"></i> Tersimpan</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i> Terakhir Dilihat</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-chat-dots me-2"></i> Forum Pemilik</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-2 sidebar d-flex flex-column p-3">
                <ul class="nav flex-column mb-auto">
                    <li class="nav-item">
                        <a href="propertiku.html" class="nav-link active"><i class="bi bi-box-arrow-right me-2 menu-list"></i>Dashboard</a>
                    </li>
                    <li class="mt-4 section-label">Data</li>
                    <li><a href="kelolaproperti.php" class="nav-link"><i class="bi bi-house-door me-2 menu-list"></i>Properti</a></li>
                    <li><a href="kamar.php" class="nav-link"><i class="bi bi-door-closed me-2 menu-list"></i>Kamar</a></li>
                    <li><a href="penyewa.php" class="nav-link"><i class="bi bi-people-fill me-2 menu-list"></i>Penyewa</a></li>
                    <li class="mt-4 section-label">Transaksi</li>
                    <li><a href="pemesanan.php" class="nav-link"><i class="bi bi-book me-2 menu-list"></i>Pemesanan</a></li>
                    <li><a href="pembayaran.php" class="nav-link"><i class="bi bi-receipt me-2 menu-list"></i>Pembayaran</a></li>
                    <li><a href="pengeluaran.php" class="nav-link"><i class="bi bi-stack me-2 menu-list"></i>Pengeluaran</a></li>
                    <li class="mt-4 section-label">Lainnya</li>
                    <li><a href="keluhan.php" class="nav-link"><i class="bi bi-exclamation-triangle me-2 menu-list"></i>Keluhan</a></li>
                </ul>
            </nav>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-4 py-4">
            <div class="row mb-4">
                <div class="col-md-4">
                    <label for="filterRange" class="form-label fw-semibold">Filter Waktu</label>
                    <select class="form-select" id="filterRange">
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="bulan_lalu">Bulan Lalu</option>
                    <option value="3_bulan">3 Bulan Terakhir</option>
                    <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div class="col-md-4 d-none" id="customRange">
                    <label class="form-label fw-semibold">Pilih Tanggal</label>
                    <div class="d-flex gap-2">
                        <input type="date" id="startDate" class="form-control">
                        <input type="date" id="endDate" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card text-white bg-success card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 id="cardKamarTerisi">0</h4>
                                <p class="mb-0">Kamar Terisi</p>
                            </div>
                                <i class="bi bi-house-door-fill card-icon"></i>
                            </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 id="cardKamarKosong">0</h4>
                                <p class="mb-0">Kamar Kosong</p>
                            </div>
                                <i class="bi bi-house-door card-icon"></i>
                            </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 id="cardPemasukan">Rp. 0</h5>
                                <p class="mb-0">Pemasukan</p>
                            </div>
                            <i class="bi bi-stack card-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 id="cardPengeluaran">Rp. 0</h5>
                                <p class="mb-0">Pengeluaran</p>
                            </div>
                            <i class="bi bi-stack card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h6>Distribusi Status Sewa</h6>
                        <canvas id="statusChart" style="max-height: 220px;"></canvas>
                        <small class="text-muted mt-2 d-block">x penyewa sudah melunaskan pembayaran.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3">
                        <h6>Pembayaran Sewa Bulan Ini</h6>
                        <canvas id="pembayaranChart" style="max-height: 249px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card shadow-sm p-3 bg-light">
                        <h6 class="mb-3">🔔 Pengingat Jatuh Tempo</h6>
                        <div class="list-group" id="pengingatList"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm p-3 bg-light">
                        <h6 class="mb-3">💸 Pengeluaran Terbaru</h6>
                        <div class="list-group" id="pengeluaranTerbaruList"></div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin keluar dari sistem?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="confirmLogout">Logout</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Notifikasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="notificationMessage">Isi pesan notifikasi akan muncul di sini.</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Logout -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                Apakah Anda yakin ingin keluar dari akun Anda?
                </div>
                <div class="modal-footer justify-content-start">
                <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>    

<footer class="bg-dark text-white text-center py-3">
<p class="mb-0">&copy; 2025 Hunian.id. All Rights Reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/propertiku.js"></script>
<script src="js/logout.js"></script>

</body>
</html>
