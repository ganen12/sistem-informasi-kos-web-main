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
    .sidebar {
      height: 100vh;
      background-color: #252321;
      color: white;
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
      font-size: 1rem;
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
      <nav class="col-md-2 sidebar d-flex flex-column p-3">
        <ul class="nav flex-column mb-auto">
            <li class="nav-item">
              <a href="propertiku.html" class="nav-link"><i class="bi bi-box-arrow-right me-2 menu-list"></i>Dashboard</a>
            </li>
            <li class="mt-4 section-label">Data</li>
            <li><a href="kelolaproperti.html" class="nav-link active"><i class="bi bi-house-door me-2 menu-list"></i>Properti</a></li>
            <li><a href="kamar.html" class="nav-link"><i class="bi bi-door-closed me-2 menu-list"></i>Kamar</a></li>
            <li><a href="penyewa.html" class="nav-link"><i class="bi bi-people-fill me-2 menu-list"></i>Penyewa</a></li>
            <li class="mt-4 section-label">Transaksi</li>
            <li><a href="pemesanan.html" class="nav-link"><i class="bi bi-book me-2 menu-list"></i>Pemesanan</a></li>
            <li><a href="pembayaran.html" class="nav-link"><i class="bi bi-receipt me-2 menu-list"></i>Pembayaran</a></li>
            <li><a href="pengeluaran.html" class="nav-link"><i class="bi bi-stack me-2 menu-list"></i>Pengeluaran</a></li>
            <li class="mt-4 section-label">Lainnya</li>
            <li><a href="keluhan.html" class="nav-link"><i class="bi bi-exclamation-triangle me-2 menu-list"></i>Keluhan</a></li>
        </ul>
      </nav>

      <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto col-lg-10 p-4">
        <div class="card p-4">
          <h5 class="mb-4">Kelola Properti</h5>
          <div class="d-flex gap-3">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#formPropertiModal">+ Tambah Properti</button>
          </div>

          <div class="mt-4 d-none" id="detailProperti">
            <h6>Informasi Properti</h6>
            <table class="table table-bordered">
              <tbody id="detailIsi"></tbody>
            </table>
          </div>
        </div>

        <!-- Properti Cards -->
        <section class="mt-4">
          <div class="row" id="daftarPropertiKartu"></div>
        </section>

        <!-- Halaman Detail -->
        <div id="halamanProperti" class="d-none container mt-5">
          ...
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Form Tambah Properti -->
  <div class="modal fade" id="formPropertiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Form Tambah Properti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Tabs -->
          <ul class="nav nav-tabs mb-3" id="propertiTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="jual-tab" data-bs-toggle="tab" data-bs-target="#jual" type="button" role="tab">Jual</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="sewa-tab" data-bs-toggle="tab" data-bs-target="#sewa" type="button" role="tab">Sewa/Kontrakan</button>
            </li>
          </ul>

          <div class="tab-content">
            <!-- Form Jual -->
            <div class="tab-pane fade show active" id="jual" role="tabpanel">
              <form action="../../controllers/properti/aksi_tambah_jual.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                  <label class="form-label">Nama Properti</label>
                  <input type="text" name="property_name" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga Jual</label>
                  <input type="number" name="sale_price" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga per Bulan (Opsional)</label>
                  <input type="number" name="price_per_month" class="form-control">
                </div>
                <div class="mb-3">
                  <label class="form-label">Gambar Properti</label>
                  <input type="file" name="image" class="form-control" required>
                </div>
                <div class="row g-2">
                  <div class="col">
                    <label class="form-label">Kamar Tidur</label>
                    <input type="number" name="bedrooms" class="form-control">
                  </div>
                  <div class="col">
                    <label class="form-label">Kamar Mandi</label>
                    <input type="number" name="bathrooms" class="form-control">
                  </div>
                </div>
                <div class="row g-2 mt-2">
                  <div class="col">
                    <label class="form-label">Luas Tanah (m²)</label>
                    <input type="number" name="land_area_size" class="form-control">
                  </div>
                  <div class="col">
                    <label class="form-label">Luas Bangunan (m²)</label>
                    <input type="number" name="building_area_size" class="form-control">
                  </div>
                </div>
                <div class="mb-3 mt-2">
                  <label class="form-label">Tipe Sertifikat</label>
                  <input type="text" name="certificate_type" class="form-control">
                </div>
                <div class="row g-2">
                  <div class="col">
                    <label class="form-label">Daya Listrik</label>
                    <input type="number" name="electricity_power" class="form-control">
                  </div>
                  <div class="col">
                    <label class="form-label">Jumlah Lantai</label>
                    <input type="number" name="floors" class="form-control">
                  </div>
                  <div class="col">
                    <label class="form-label">Garasi</label>
                    <input type="number" name="garage" class="form-control">
                  </div>
                </div>
                <div class="mb-3 mt-2">
                  <label class="form-label">Kondisi Properti</label>
                  <input type="text" name="property_condition" class="form-control">
                </div>
                <div class="mb-3">
                  <label class="form-label">Deskripsi</label>
                  <textarea name="description" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Fasilitas</label>
                  <textarea name="facilities" class="form-control"></textarea>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>
            </div>

            <!-- Form Sewa/Kontrakan -->
            <div class="tab-pane fade" id="sewa" role="tabpanel">
              <form action="../../controllers/properti/aksi_tambah_sewa.php" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                <div class="mb-3">
                  <label class="form-label">Nama Properti</label>
                  <input type="text" name="property_name" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Jenis Properti</label>
                  <select name="property_type" class="form-select" required>
                    <option value="">Pilih</option>
                    <option value="Sewa">Sewa</option>
                    <option value="Kontrakan">Kontrakan</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Durasi Sewa</label>
                  <select name="rental_duration" class="form-select" required>
                    <option value="">Pilih</option>
                    <option value="Bulanan">Bulanan</option>
                    <option value="Tahunan">Tahunan</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga Sewa</label>
                  <input type="number" name="rental_price" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Fasilitas</label>
                  <textarea name="facilities" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Gambar Properti</label>
                  <input type="file" name="image" class="form-control" required>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>
            </div>
          </div>
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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/kelolaproperti.js"></script>
<script src="js/navbar.js"></script>
  
</body>
</html>
