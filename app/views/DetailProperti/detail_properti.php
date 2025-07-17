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
    body { font-family: 'Montserrat', sans-serif; background-color: #f8f9fa; }
    .property-header img { height: 500px; object-fit: cover; width: 100%; border-radius: 8px; }
    .card { border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border: none; margin-bottom: 20px; }
    .card-header { background-color: #fff; border-bottom: 1px solid #eee; font-weight: 600; padding: 15px 20px; }
    .card-body { padding: 20px; }
    .badge-premier { background: linear-gradient(135deg, #ffc107, #fd7e14); color: white; font-weight: 500; padding: 5px 12px; border-radius: 20px; font-size: 0.85rem; }
    .btn-whatsapp { background-color: #25D366; color: white; font-weight: 500; }
    .btn-whatsapp:hover { background-color: #128C7E; color: white; }
    .section-title { position: relative; padding-bottom: 10px; margin-bottom: 20px; font-weight: 600; }
    .section-title::after { content: ''; position: absolute; bottom: 0; left: 0; width: 60px; height: 3px; background: linear-gradient(to right, #0d6efd, #20c997); border-radius: 3px; }
    .ratio iframe { border-radius: 8px; }
    @media (max-width: 768px) { .property-header img { height: 300px; } }
  </style>
</head>
<body>
<?php
include "../../../config/database.php";
session_start();

$id = intval($_GET['id'] ?? 0);
$query = "SELECT * FROM selling_properties WHERE selling_property_id = $id";
$result = mysqli_query($link, $query);
if (!$result || mysqli_num_rows($result) == 0) {
  die("<p class='text-center mt-5'>Properti tidak ditemukan.</p>");
}
$data = mysqli_fetch_assoc($result);
$imgPath = !empty($data['image']) ? "../../uploads/jual/" . $data['image'] : "https://via.placeholder.com/800x400";
$agentQuery = "SELECT nama_lengkap, phone_number FROM users WHERE id = {$data['user_id']}";
$agentResult = mysqli_query($link, $agentQuery);
$agent = mysqli_fetch_assoc($agentResult);
$no_hp = preg_replace('/\D/', '', $agent['phone_number'] ?? '');
if (substr($no_hp, 0, 1) === '0') {
  $no_hp = '62' . substr($no_hp, 1);
}
$pesan = urlencode("Halo, saya tertarik dengan properti \"{$data['property_name']}\" yang dijual di Hunian.id.");
?>


<!-- Jika role pemilik, maka gunakan navbar pemilik -->
<?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'pemilik'): ?>
    <?php include '../partials/navbar.php'; ?>
<?php else: ?>
    <?php include '../partials/navbar_pembeli.php'; ?>
<?php endif; ?>
<!-- <?php // include '../partials/navbar.php'; ?> -->

<div class="container my-5">
  <div class="row">
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
          <div>
            <h1 class="h4 mb-0"><?= $data['property_name'] ?></h1>
            <span class="badge-premier mt-2">Premier</span>
          </div>
          <div>
            <button class="btn btn-sm btn-outline-secondary"><i class="bi bi-share"></i> Bagikan</button>
            <button class="btn btn-sm btn-outline-danger"><i class="bi bi-heart"></i> Simpan</button>
          </div>
        </div>
        <div class="card-body">
          <div class="property-header mb-4">
            <img src="<?= $imgPath ?>" alt="Properti">
          </div>
          <div class="price-tag mb-3">Rp <?= number_format($data['sale_price'], 0, ',', '.') ?></div>
          <div class="installment mb-4">Cicilan mulai Rp <?= number_format($data['price_per_month'] ?? 0, 0, ',', '.') ?>/bulan</div>
          <div class="row mb-4">
            <div class="col-md-3"><div class="text-muted small">Kamar Tidur</div><div class="fw-bold"><?= $data['bedrooms'] ?></div></div>
            <div class="col-md-3"><div class="text-muted small">Kamar Mandi</div><div class="fw-bold"><?= $data['bathrooms'] ?></div></div>
            <div class="col-md-3"><div class="text-muted small">Luas Tanah</div><div class="fw-bold"><?= $data['land_area_size'] ?> m²</div></div>
            <div class="col-md-3"><div class="text-muted small">Luas Bangunan</div><div class="fw-bold"><?= $data['building_area_size'] ?> m²</div></div>
          </div>
          <h5 class="section-title">Lokasi</h5>
          <p class="mb-2"><i class="bi bi-geo-alt-fill text-danger"></i> <?= $data['location'] ?></p>
          <div class="ratio ratio-16x9 mb-4">
            <iframe src="https://www.google.com/maps?q=<?= urlencode($data['location']) ?>&output=embed" allowfullscreen loading="lazy"></iframe>
          </div>
          <h5 class="section-title">Deskripsi</h5>
          <p><?= nl2br(htmlspecialchars($data['description'] ?? '-')) ?></p>
          <?php if (!empty($data['facilities'])): ?>
            <h5 class="section-title">Fasilitas</h5>
            <p><?= nl2br(htmlspecialchars($data['facilities'])) ?></p>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">Informasi Agen</div>
        <div class="card-body">
          <h5 class="mt-3 mb-1"><?= htmlspecialchars($agent['nama_lengkap'] ?? 'Agen Properti') ?></h5>
          <p class="text-muted mb-3">Pemilik / Agen</p>

          <?php if (isset($_SESSION['user_id'])): ?>
            <a href="https://wa.me/<?= $no_hp ?>?text=<?= $pesan ?>" target="_blank" class="btn btn-whatsapp w-100">
              <i class="bi bi-whatsapp"></i> Hubungi Agen
            </a>
          <?php else: ?>
            <button class="btn btn-whatsapp w-100" onclick="alert('Silakan login terlebih dahulu untuk menghubungi agen.')">
              <i class="bi bi-whatsapp"></i> Hubungi Agen
            </button>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</div>
<?php include '../partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
