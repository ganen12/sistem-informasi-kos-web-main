<?php
require_once "../../helpers/auth.php";
require_login();
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hunian.id - Beli Properti</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }
    .card {
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    .card-img-top {
      height: 220px;
      object-fit: cover;
    }
    .card-body {
      flex-grow: 1;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
<?php include '../partials/navbar_pembeli.php'; ?>

  <!-- <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-warning" href="#">Hunian.id</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="../dashboard/dashboardpembeli.html">Beranda</a></li>
          <li class="nav-item"><a class="nav-link active" href="#">Beli</a></li>
          <li class="nav-item"><a class="nav-link" href="Pembeli_Sewa.html">Sewa</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Iklankan</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Bantuan</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-2"></i> Buyer
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="bi bi-bookmark-heart me-2"></i> Tersimpan</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i> Terakhir Dilihat</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-chat-dots me-2"></i> Forum Pembeli</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav> -->

  <!-- Filter + Sort Section -->
  <div class="container py-4">
    <form class="row g-2">
      <div class="col-md-5">
        <input type="text" class="form-control" placeholder="Lokasi, keyword, area, project, developer">
      </div>
      <div class="col-md-2">
        <select class="form-select">
          <option>Properti Baru</option>
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-select">
          <option>Harga</option>
        </select>
      </div>
      <div class="col-md-2">
        <select class="form-select">
          <option>Luas Tanah</option>
        </select>
      </div>
      <div class="col-md-1">
        <button class="btn btn-primary w-100">Cari</button>
      </div>
    </form>
  </div>

  <?php
  include "../../../config/database.php";

  $query = "SELECT s.*, u.nama_lengkap FROM selling_properties s JOIN users u ON s.user_id = u.id ORDER BY created_at DESC";
  $result = mysqli_query($link, $query);
  ?>

  <section class="container pb-5">
    <h5 class="mb-3">Menampilkan <?= mysqli_num_rows($result) ?> properti</h5>
    <div class="row g-4">
      <?php while($row = mysqli_fetch_assoc($result)): 
        $harga = number_format($row['sale_price'], 0, ',', '.');
        $imgPath = !empty($row['image']) ? "../../uploads/jual/{$row['image']}" : "https://via.placeholder.com/400x220";
        $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $row['phone_number'] ?? '628123456789');
        $inisial = strtoupper(substr($row['nama_lengkap'], 0, 1));
      ?>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <div class="position-relative">
            <a href="../DetailProperti/detail_properti.php?kategori=jual&id=<?= $row['selling_property_id'] ?>" class="text-decoration-none text-dark">
              <img src="<?= $imgPath ?>" class="card-img-top" alt="<?= htmlspecialchars($row['property_name']) ?>">
              <span class="badge bg-light text-dark position-absolute top-0 start-0 m-2">Rumah</span>
          </div>
          <div class="card-body">
            <h5 class="fw-bold text-warning">Rp <?= $harga ?></h5>
            <p class="fw-semibold small text-dark mb-1"><?= htmlspecialchars($row['property_name']) ?></p>
            <p class="text-muted small mb-2">-</p> <!-- Lokasi belum tersedia di tabel -->
            <div class="d-flex text-muted small mb-3">
              <div class="me-3"><i class="bi bi-house-door"></i> <?= $row['bedrooms'] ?> KT</div>
              <div class="me-3"><i class="bi bi-droplet-half"></i> <?= $row['bathrooms'] ?> KM</div>
              <div class="me-3"><i class="bi bi-bounding-box"></i> <?= $row['land_area_size'] ?> m²</div>
              <div><i class="bi bi-aspect-ratio"></i> <?= $row['building_area_size'] ?> m²</div>
            </div>
            <div class="d-flex justify-content-between align-items-center border-top pt-2">
              <div class="d-flex align-items-center">
                <div class="bg-secondary text-white rounded-circle me-2 px-2 py-1 small"><?= $inisial ?></div>
                <small class="text-muted"><?= htmlspecialchars($row['nama_lengkap']) ?></small>
              </div>
              <a href="<?= $waUrl ?>" class="btn btn-success btn-sm">
                <i class="bi bi-whatsapp"></i> Chat
              </a>
            </div>
          </div>
          </a>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </section>

        <!-- Pagination -->
        <div class="mt-4 d-flex justify-content-center">
            <nav>
            <ul class="pagination mb-0">
                <li class="page-item disabled">
                <a class="page-link">Sebelumnya</a>
                </li>
                <li class="page-item active">
                <a class="page-link" href="#">1</a>
                </li>
                <li class="page-item">
                <a class="page-link" href="#">2</a>
                </li>
                <li class="page-item">
                <a class="page-link" href="#">3</a>
                </li>
                <li class="page-item">
                <a class="page-link" href="#">Berikutnya</a>
                </li>
            </ul>
            </nav>
        </div>
    </section>

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
          <button type="button" class="btn btn-danger">Logout</button>
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
<?php include '../partials/footer.php'; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
