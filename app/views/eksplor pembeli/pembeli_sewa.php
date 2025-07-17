<?php
include "../../../config/database.php";

require_once "../../helpers/auth.php";
require_login();

$keyword = trim($_GET['keyword'] ?? '');

$where = "WHERE 1=1";
if ($keyword !== '') {
  $safeKeyword = mysqli_real_escape_string($link, $keyword);
  $where .= " AND (r.property_name LIKE '%$safeKeyword%' OR r.location LIKE '%$safeKeyword%')";
}

$query = "SELECT r.*, u.nama_lengkap, u.phone_number 
          FROM rental_properties r 
          JOIN users u ON r.user_id = u.id 
          $where 
          ORDER BY r.created_at DESC";
$result = mysqli_query($link, $query);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hunian.id - Sewa Properti</title>
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
    .rent-badge {
      background-color: #17a2b8;
      color: white;
    }
    .kontrakan-badge {
      background-color: #6c757d;
      color: white;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
    <?php include '../partials/navbar_pembeli.php'; ?>

    
<div class="container py-4">
  <!-- Search Form -->
  <form class="row g-2 mb-4" method="GET" action="">
    <div class="col-md-10">
      <input type="text" name="keyword" class="form-control" placeholder="Cari lokasi atau nama properti..." value="<?= htmlspecialchars($keyword) ?>">
    </div>
    <div class="col-md-2">
      <button class="btn btn-primary w-100" type="submit">Cari</button>
    </div>
  </form>

  <h5 class="mb-3">Menampilkan <?= mysqli_num_rows($result) ?> properti</h5>
  <div class="row g-4">
    <?php while($row = mysqli_fetch_assoc($result)): 
      $harga = number_format($row['rental_price'], 0, ',', '.');
      $imgPath = !empty($row['image']) ? "../../uploads/sewa/{$row['image']}" : "https://via.placeholder.com/400x220";
      $waUrl = "https://wa.me/" . preg_replace('/[^0-9]/', '', $row['phone_number'] ?? '628123456789');
      $inisial = strtoupper(substr($row['nama_lengkap'], 0, 1));
    ?>
    <div class="col-md-4">
      <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="position-relative">
          <a href="../DetailProperti/detail_properti_sewa.php?id=<?= $row['rental_property_id'] ?>" class="text-decoration-none text-dark">
            <img src="<?= $imgPath ?>" class="card-img-top" alt="<?= htmlspecialchars($row['property_name']) ?>">
            <span class="badge bg-light text-dark position-absolute top-0 start-0 m-2">Sewa</span>
        </div>
        <div class="card-body">
          <h5 class="fw-bold text-warning">Rp <?= $harga ?>/<?= htmlspecialchars($row['rental_duration']) ?></h5>
          <p class="fw-semibold small text-dark mb-1"><?= htmlspecialchars($row['property_name']) ?></p>
          <p class="text-muted small mb-2"><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($row['location']) ?></p>
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
</div>

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

  <!-- Footer -->
<?php include '../partials/footer.php'; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>