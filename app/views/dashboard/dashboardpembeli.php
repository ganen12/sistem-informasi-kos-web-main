        <!-- jika user belum login, redirect ke halaman login -->
    <?php
    require_once "../../helpers/auth.php";
    require_login();
    require_role('pembeli');
    ?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hunian.id - Sewa Kost, Rumah, dan Kontrakan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
    }

    .card {
        height: 100%; /* agar semua .col-* tinggi sama */
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

    .hero {
        background: url('../../uploads/default_template/Hero.png') center/cover no-repeat;
        color: white;
        padding: 8rem 2rem 6rem; /* Atas, samping, bawah */
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 60vh;
        text-align: center;
      }
    .hero h1 {
      font-weight: 700;
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
          <li class="nav-item">
            <a class="nav-link active" href="#">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../eksplor pembeli/pembeli_beli.php">Beli</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../eksplor pembeli/pembeli_sewa.php">Sewa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Iklankan</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Bantuan</a>
          </li>
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

  <!-- Hero Section -->
  <section class="hero text-center">
    <div class="container">
      <h1>Cari Kost, Rumah, atau Kontrakan Mudah!</h1>
      <p class="lead">Temukan properti idealmu dengan mudah.</p>
    </div>
  </section>

  <!-- Search Form -->
  <section class="bg-light py-4">
    <div class="container">
      <form class="row g-2">
        <div class="col-md-4">
          <input type="text" class="form-control" placeholder="Cari Lokasi (Contoh: Jakarta)">
        </div>
        <div class="col-md-3">
          <select class="form-select">
            <option value="">Tipe Properti</option>
            <option value="kost">Kost</option>
            <option value="rumah">Rumah</option>
            <option value="kontrakan">Kontrakan</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control" placeholder="Anggaran Maksimal (Rp)">
        </div>
        <div class="col-md-2">
          <button class="btn btn-dark w-100">Cari</button>
        </div>
      </form>
    </div>
  </section>

  <!-- Daftar Properti Terbaru -->
  <section class="py-5">
    <div class="container">
      <h3 class="mb-4">Properti Terbaru</h3>
      <div class="row g-3">
        <?php
          include "../../../config/database.php";

          // Ambil dari selling_properties
          $queryJual = "SELECT 'jual' as kategori, selling_property_id AS id, property_name, sale_price as harga, image, description FROM selling_properties ORDER BY created_at DESC LIMIT 3";
          $resultJual = mysqli_query($link, $queryJual);

          // Ambil dari rental_properties
          $querySewa = "SELECT 'sewa' as kategori, rental_property_id AS id, property_name, rental_price as harga, image, facilities FROM rental_properties ORDER BY created_at DESC LIMIT 3";
          $resultSewa = mysqli_query($link, $querySewa);

          // Gabungkan hasil
          $allProperties = [];
          while ($row = mysqli_fetch_assoc($resultJual)) $allProperties[] = $row;
          while ($row = mysqli_fetch_assoc($resultSewa)) $allProperties[] = $row;

          // Sort by newest
          usort($allProperties, fn($a, $b) => $b['id'] <=> $a['id']);

          // Tampilkan max 6
          $count = 0;
          foreach ($allProperties as $prop):
            if ($count++ >= 6) break;
            if (!empty($prop['image'])) {
                $imgPath = ($prop['kategori'] == 'jual')
                    ? "../../uploads/jual/" . $prop['image']
                    : "../../uploads/sewa/" . $prop['image'];
            } else {
                $imgPath = "https://via.placeholder.com/400x220";
            }
            $hargaFormatted = "Rp " . number_format($prop['harga'], 0, ',', '.');
            $labelHarga = $prop['kategori'] == 'jual' ? $hargaFormatted : $hargaFormatted . " / bulan";
            $detailLink = "../DetailProperti/detail_properti.php?kategori=" . urlencode($prop['kategori']) . "&id=" . urlencode($prop['id']);

            // Ambil ringkasan fasilitas/description
            $shortText = $prop['kategori'] == 'jual' ? $prop['description'] : $prop['facilities'];
            $shortText = strip_tags($shortText ?? '');
            if (strlen($shortText) > 60) $shortText = substr($shortText, 0, 57) . '...';
        ?>
          <div class="col-md-4">
            <div class="card h-100 position-relative">
              <a href="<?= $detailLink ?>" class="text-decoration-none text-dark">
                <!-- Badge kategori -->
                <span class="badge bg-<?= $prop['kategori'] == 'jual' ? 'primary' : 'success' ?> position-absolute m-2">
                  <?= strtoupper($prop['kategori']) ?>
                </span>
                <img src="<?= $imgPath ?>" class="card-img-top" alt="<?= htmlspecialchars($prop['property_name']) ?>">
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($prop['property_name']) ?></h5>
                  <p class="card-text mb-1"><?= $labelHarga ?></p>
                  <small class="text-muted"><?= htmlspecialchars($shortText) ?></small>
                </div>
              </a>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

  <!-- Fitur Section -->
  <section class="bg-light py-5">
    <div class="container">
      <div class="row text-center">
        <div class="col-md-4">
          <i class="bi bi-house-fill display-4 text-warning"></i>
          <h5 class="mt-3">Beragam Tipe Properti</h5>
          <p>Kost, rumah, atau kontrakan tersedia untuk semua kebutuhanmu.</p>
        </div>
        <div class="col-md-4">
          <i class="bi bi-coin display-4 text-success"></i>
          <h5 class="mt-3">Kelola Keuangan</h5>
          <p>Monitoring pemasukan dan pengeluaran langsung dari dashboard.</p>
        </div>
        <div class="col-md-4">
          <i class="bi bi-shield-check display-4 text-primary"></i>
          <h5 class="mt-3">Aman & Terpercaya</h5>
          <p>Platform terpercaya untuk transaksi properti online.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
<?php include '../partials/footer.php'; ?>


  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
