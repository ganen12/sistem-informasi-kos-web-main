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
    body { font-family: 'Montserrat', sans-serif; }
    .property-header img { height: 400px; object-fit: cover; width: 100%; }
    .property-detail h1 { font-weight: 700; }
    .icon-info i { margin-right: 6px; }
  </style>
</head>
<body>

<?php
  include "../../config/database.php";
  $kategori = $_GET['kategori'] ?? '';
  $id = intval($_GET['id'] ?? 0);

  if ($kategori === 'jual') {
      $query = "SELECT * FROM selling_properties WHERE selling_property_id = $id";
  } elseif ($kategori === 'sewa') {
      $query = "SELECT * FROM rental_properties WHERE rental_property_id = $id";
  } else {
      die("<p class='text-center mt-5'>Kategori tidak valid.</p>");
  }

  $result = mysqli_query($link, $query);
  if (!$result || mysqli_num_rows($result) == 0) {
      die("<p class='text-center mt-5'>Properti tidak ditemukan.</p>");
  }
  $data = mysqli_fetch_assoc($result);
  if (!empty($data['image'])) {
      if ($kategori === 'jual') {
          $imgPath = "../uploads/jual/" . $data['image'];
      } else {
          $imgPath = "../uploads/sewa/" . $data['image'];
      }
  } else {
      $imgPath = "https://via.placeholder.com/800x400";
  }
?>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-warning" href="dashboardutama.php">Hunian.id</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboardutama.php">Beranda</a></li>
        <li class="nav-item"><a class="nav-link" href="Beli.php">Beli</a></li>
        <li class="nav-item"><a class="nav-link" href="Sewa.php">Sewa</a></li>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
      </ul>
    </div>
  </div>
</nav>

<!-- Header Gambar -->
<div class="property-header position-relative">
  <img src="<?= $imgPath ?>" alt="Properti">
  <button onclick="window.history.back()" class="btn btn-light position-absolute top-0 start-0 m-3">
    <i class="bi bi-arrow-left"></i> Kembali
  </button>
</div>

<!-- Detail -->
<section class="container py-5 property-detail">
  <h1 class="mb-3"><?= htmlspecialchars($data['property_name']) ?></h1>
  <h4 class="text-warning">
    <?php
      if ($kategori === 'jual') {
        echo "Rp " . number_format($data['sale_price'], 0, ',', '.');
      } else {
        echo "Rp " . number_format($data['rental_price'], 0, ',', '.') . " / " . strtolower($data['rental_duration']);
      }
    ?>
  </h4>

  <?php if ($kategori === 'jual'): ?>
    <div class="row mb-4 icon-info">
      <div class="col-md-3"><i class="bi bi-door-closed"></i> <?= $data['bedrooms'] ?> Kamar Tidur</div>
      <div class="col-md-3"><i class="bi bi-droplet"></i> <?= $data['bathrooms'] ?> Kamar Mandi</div>
      <div class="col-md-3"><i class="bi bi-lightning"></i> <?= $data['electricity_power'] ?> Watt</div>
      <div class="col-md-3"><i class="bi bi-building"></i> <?= $data['floors'] ?> Lantai</div>
    </div>
  <?php endif; ?>

  <h5 class="mt-4">Lokasi</h5>
  <p><?= htmlspecialchars($data['location'] ?? 'Lokasi tidak tersedia') ?></p>
  
  <?php if (!empty($data['location'])): ?>
    <h5 class="mt-4">Peta Lokasi</h5>
    <div class="ratio ratio-16x9 mb-4">
      <iframe
        src="https://www.google.com/maps?q=<?= urlencode($data['location']) ?>&output=embed"
        style="border:0;"
        allowfullscreen=""
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>
  <?php endif; ?>

  <h5 class="mt-4">Deskripsi Properti</h5>
  <p><?= nl2br(htmlspecialchars($data['description'] ?? 'Tidak ada deskripsi.')) ?></p>

  <?php if (!empty($data['facilities'])): ?>
    <h5 class="mt-4">Fasilitas</h5>
    <p><?= nl2br(htmlspecialchars($data['facilities'])) ?></p>
  <?php endif; ?>

  <div class="mt-4 d-flex gap-3">
    <a href="https://wa.me/6281234567890" class="btn btn-success"><i class="bi bi-whatsapp"></i> Chat Pemilik</a>
    <!-- <button class="btn btn-outline-secondary">Simpan Properti</button> -->
  </div>
</section>

<footer class="bg-dark text-white text-center py-3">
  <p class="mb-0">&copy; 2025 Hunian.id</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
