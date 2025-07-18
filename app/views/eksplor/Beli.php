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


<?php  include '../partials/navbar.php'; ?>


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

<?php include '../partials/footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
