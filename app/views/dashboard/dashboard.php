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
        background: url('/Asset/Hero.png') center/cover no-repeat;
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
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand fw-bold text-warning" href="#">Hunian.id</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="dashboardutama.php">Beranda</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Beli.php">Beli</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="Sewa.php">Sewa</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Bantuan</a>
          </li>
          <li class="nav-item ms-2">
            <a class="btn btn-outline-secondary" href="login.php">Login</a>
            <a class="btn btn-warning ms-2" href="register.php">Daftar</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero text-center">
    <div class="container">
      <h1>Cari Kost, Rumah, atau Kontrakan Mudah!</h1>
      <p class="lead">Temukan properti idealmu atau pasarkan propertimu dengan mudah.</p>
      <a href="login.php" class="btn btn-warning btn-lg mt-3">Gabung Sekarang</a>
    </div>
  </section>

  <!-- Search Form -->
  <section class="bg-light py-4">
    <div class="container">
      <form class="row g-2" method="GET" action="search.php">
        <div class="col-md-4">
          <input type="text" class="form-control" name="lokasi" placeholder="Cari Lokasi (Contoh: Jakarta)">
        </div>
        <div class="col-md-3">
          <select class="form-select" name="tipe">
            <option value="">Tipe Properti</option>
            <option value="kost">Kost</option>
            <option value="rumah">Rumah</option>
            <option value="kontrakan">Kontrakan</option>
          </select>
        </div>
        <div class="col-md-3">
          <input type="text" class="form-control" name="harga" placeholder="Anggaran Maksimal (Rp)">
        </div>
        <div class="col-md-2">
          <button class="btn btn-dark w-100" type="submit">Cari</button>
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
          $queryJual = "SELECT 'jual' as kategori, selling_property_id AS id, property_name, sale_price as harga, image FROM selling_properties ORDER BY created_at DESC LIMIT 3";
          $resultJual = mysqli_query($link, $queryJual);

          // Ambil dari rental_properties
          $querySewa = "SELECT 'sewa' as kategori, rental_property_id AS id, property_name, rental_price as harga, image FROM rental_properties ORDER BY created_at DESC LIMIT 3";
          $resultSewa = mysqli_query($link, $querySewa);

          // Gabungkan hasil
          $allProperties = [];
          while ($row = mysqli_fetch_assoc($resultJual)) $allProperties[] = $row;
          while ($row = mysqli_fetch_assoc($resultSewa)) $allProperties[] = $row;

          // Sort by newest (optional)
          usort($allProperties, fn($a, $b) => $b['id'] <=> $a['id']);

          // Tampilkan max 6
          $count = 0;
          foreach ($allProperties as $prop):
            if ($count++ >= 6) break;
            if (!empty($prop['image'])) {
                if ($prop['kategori'] == 'jual') {
                    $imgPath = "../../uploads/jual/" . $prop['image'];
                } else {
                    $imgPath = "../../uploads/sewa/" . $prop['image'];
                }
            } else {
                $imgPath = "https://via.placeholder.com/400x220";
            }
            $hargaFormatted = "Rp " . number_format($prop['harga'], 0, ',', '.');
            $labelHarga = $prop['kategori'] == 'jual' ? $hargaFormatted : $hargaFormatted . " / bulan";
        ?>
          <div class="col-md-4">
            <div class="card h-100">
              <a href="<?= $detailLink ?>" class="text-decoration-none text-dark">
                <img src="<?= $imgPath ?>" class="card-img-top" alt="<?= htmlspecialchars($prop['property_name']) ?>">
                <div class="card-body">
                  <h5 class="card-title"><?= htmlspecialchars($prop['property_name']) ?></h5>
                  <p class="card-text"><?= $labelHarga ?></p>
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
  <footer class="bg-dark text-white text-center py-3">
    <p class="mb-0">&copy; 2025 Hunian.id. All Rights Reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
