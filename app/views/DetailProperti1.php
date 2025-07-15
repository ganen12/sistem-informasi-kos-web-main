<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Detail Properti - Hunian.id</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
    .property-header img {
      height: 400px;
      object-fit: cover;
      width: 100%;
    }
    .property-detail h1 {
      font-weight: 700;
    }
    .property-info small {
      color: #777;
    }
    .icon-info i {
      margin-right: 6px;
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
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="dashboardpemilik.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link active" href="Beli.html">Beli</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Sewa</a></li>
          <li class="nav-item"><a class="nav-link" href="propertiku.html">Propertiku</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Header Gambar -->
  <div class="property-header position-relative">
    <img src="/Asset/rumah1.jpg" alt="Properti">
    <!-- Tombol Kembali -->
    <button onclick="window.history.back()" class="btn btn-light position-absolute top-0 start-0 m-3">
      <i class="bi bi-arrow-left"></i> Kembali
    </button>
  </div>
  

  <!-- Detail -->
  <section class="container py-5 property-detail">
    <h1 class="mb-3">Rumah Minimalis Daerah Bandung</h1>
    <h4 class="text-warning">Rp 850.000.000</h4>
    <p class="text-muted">Jl. Merpati No 21, Bandung, Jawa Barat</p>

    <div class="row mb-4 icon-info">
      <div class="col-md-3"><i class="bi bi-house-door"></i> 120 m2</div>
      <div class="col-md-3"><i class="bi bi-door-closed"></i> 3 Kamar Tidur</div>
      <div class="col-md-3"><i class="bi bi-droplet"></i> 2 Kamar Mandi</div>
      <div class="col-md-3"><i class="bi bi-lightning"></i> 2200 Watt</div>
    </div>

    <h5 class="mt-4">Deskripsi Properti</h5>
    <p>Rumah minimalis 1 lantai siap huni di lokasi strategis Bandung. Dekat dengan sekolah, pasar, dan tol. Sertifikat SHM, akses jalan 2 mobil.</p>

    <div class="mt-4 d-flex gap-3">
      <a href="https://wa.me/6281234567890" class="btn btn-success"><i class="bi bi-whatsapp"></i> Chat Pemilik</a>
      <button class="btn btn-outline-secondary">Simpan Properti</button>
    </div>
  </section>

  <footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">&copy; 2025 Hunian.id</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>