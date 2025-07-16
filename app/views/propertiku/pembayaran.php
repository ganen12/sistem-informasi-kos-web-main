<?php
require_once "../../helpers/auth.php";
require_login();
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hunian.id - Tambah Properti</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #e4e4e4;
    }
    .card-icon {
      font-size: 1.8rem;
    }
    .card-stat {
      min-height: 80px;
    }
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background-color: #252321;
        color: white;
        z-index: 1030; /* agar di atas konten lain */
        overflow-y: auto;
        margin-top: 56px;
    }
    .sidebar .nav-link {
      color: #ccc;
    }
    .sidebar .nav-link.active,
    .sidebar .nav-link:hover {
      background-color: #32302D;
      color: white;
    }
    .section-label {
      color: rgba(238, 237, 235, 0.3);
      font-size: 12px;
      text-transform: uppercase;
    }
    .menu-list {
      padding: 0.75rem 0;
      font-size: 20px;
    }
  </style>
</head>
<body>

      <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <a class="navbar-brand fw-bold text-warning ms-4" href="#">Hunian.id</a>
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="dashboardpemilik.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="Beli.html">Beli</a></li>
          <li class="nav-item"><a class="nav-link" href="Sewa.html">Sewa</a></li>
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

  <!-- Main Layout -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
        <?php $activeMenu = 'pembayaran'; ?> 
        <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>

      <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto col-lg-10 p-4">
        <div class="container mt-4">
          <div class="row mb-4 align-items-center">
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
                  <input type="date" id="startDate" class="form-control" />
                  <input type="date" id="endDate" class="form-control" />
                </div>
              </div>
          </div> 
          <div class="row g-3">
            <div class="col-md-4">
              <div class="card text-white bg-warning card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h4 id="totalSewaCount">0</h4>
                    <p class="mb-0">Total Pembayaran</p>
                  </div>
                  <i class="bi bi-calendar-event-fill card-icon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card text-white bg-success card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h5 id="totalDibayar">0</h5>
                    <p class="mb-0">Dibayar</p>
                  </div>
                  <i class="bi bi-arrow-up-right-circle-fill card-icon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card text-white bg-danger card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h5 id="totalBelumDibayar">0</h5>
                    <p class="mb-0">Belum Dibayar</p>
                  </div>
                  <i class="bi bi-arrow-down-left-circle-fill card-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
              

        <!-- CRUD Pembayaran -->
        <div class="container-fluid mt-4">
          <div class="card p-4">
            <div class="d-flex justify-content-between mb-3">
              <h5>Data Pembayaran</h5>
              <div class=" d-flex gap-2">
                <input type="text" class="form-control" placeholder="Cari..." id="searchInput" />
                  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahPembayaranModal">
                      <i class="bi bi-plus-circle me-2"></i>Tambah Pembayaran
                  </button>
              </div>
            </div>

            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Tanggal Pembayaran</th>
                  <th>Nama Penyewa</th>
                  <th>Total Tagihan</th>
                  <th>Jumlah Dibayar</th>
                  <th>Jatuh Tempo</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tabelPembayaran">
                <!-- Data ditambahkan otomatis -->
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

<!-- Modal Tambah Pembayaran -->
<div class="modal fade" id="tambahPembayaranModal" tabindex="-1" aria-labelledby="tambahPembayaranLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tambahPembayaranLabel">Tambah Pembayaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formPembayaran">
          <div class="mb-3">
            <label class="form-label">Tanggal Pembayaran</label>
            <input type="date" class="form-control" id="tanggalPembayaran" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Nama Penyewa</label>
            <input type="text" class="form-control" id="nama" required>
          </div>
          <div class="mb-3">
            <label class="form-label rupiah">Total Tagihan (Rp)</label>
            <input type="number" class="form-control" id="totalSewa" required>
          </div>
          <div class="mb-3">
            <label class="form-label rupiah">Jumlah Dibayar (Rp)</label>
            <input type="number" class="form-control" id="jumlahDibayar" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jatuh Tempo</label>
            <input type="date" class="form-control" id="jatuhTempo" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status Pembayaran</label>
            <select class="form-select" id="statusPembayaran" required>
              <option value="Lunas">Lunas</option>
              <option value="Belum Lunas">Belum Lunas</option>
              <option value="Tertunda">Tertunda</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
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
  
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toastNotifikasi" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body" id="pesanToast">Berhasil!</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/pembayaran.js"></script>
<script src="js/navbar.js"></script>
  
</body>
</html>
