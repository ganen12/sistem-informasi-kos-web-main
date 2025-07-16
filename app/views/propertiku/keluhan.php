<?php
require_once "../../helpers/auth.php";
require_login();
?>

<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Keluhan Penyewa</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #e4e4e4;
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
    .brand {
      background-color: #cfc6b4;
    }
    .menu-list {
      padding: 0.75rem 0;
      font-size: 20px;
    }
    .input-fields {
        width: 700px;
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

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <?php $activeMenu = 'keluhan'; ?> 
    <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>

    <!-- Main -->
    <main class="col-md-10 ms-sm-auto col-lg-10" style="padding: 0;">
      <!-- Top Navbar -->
      <!-- <div class="d-flex justify-content-between align-items-center py-3 brand px-4">
        <div class="h5 mb-0">Keluhan</div>
        <div class="d-flex align-items-center gap-3">
          <div class="dropdown-center">
            <i class="bi bi-bell-fill fs-5 dropdown-toggle" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false" role="button"></i>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li><a class="dropdown-item" href="#">test announce</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </div>
          <div><i class="bi bi-person-circle me-1"></i> sadmin</div>
        </div>
      </div> -->

      <div class="container-fluid mt-4">
        <div class="card p-4">
          <div class="d-flex justify-content-between mb-3">
            <h5>Daftar Keluhan</h5>
            <div class=" d-flex gap-2">
              <input type="text" class="form-control" placeholder="Cari..." id="searchInput" />
              <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalKeluhan">
                <i class="bi bi-plus-circle me-2"></i>Tambah Keluhan
              </button>
            </div>
          </div>
        
          <table class="table table-striped">
            <thead class="table-dark">
              <tr>
                <th>No</th>
                <th>Nama Penyewa</th>
                <th>Nomor Kamar</th>
                <th>Tanggal Keluhan</th>
                <th>Isi Keluhan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tabelKeluhan">
              <!-- Data keluhan ditambahkan di sini -->
            </tbody>
          </table>
        </div>
      </div>
    </main>
  </div>
</div>

<!-- Modal Keluhan -->
<div class="modal fade" id="modalKeluhan" tabindex="-1" aria-labelledby="modalKeluhanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form id="formKeluhan" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Input Keluhan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Nama Penyewa</label><input type="text" class="form-control" id="namaPenyewa" required></div>
        <div class="mb-3"><label class="form-label">Nomor Kamar</label><input type="text" class="form-control" id="nomorKamar" required></div>
        <div class="mb-3"><label class="form-label">Tanggal Keluhan</label><input type="date" class="form-control" id="tanggalKeluhan" required></div>
        <div class="mb-3"><label class="form-label">Isi Keluhan</label><textarea class="form-control" id="isiKeluhan" rows="3" required></textarea></div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select class="form-select" id="statusKeluhan" required>
            <option value="Belum Ditangani">Belum Ditangani</option>
            <option value="Diproses">Diproses</option>
            <option value="Selesai">Selesai</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- logout confirmation modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin keluar dari sistem?</p>
      </div>
      <div class="modal-footer border-0 justify-content-start">
        <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/keluhan.js"></script>

<script src="js/global.js"></script>

</body>
</html>
