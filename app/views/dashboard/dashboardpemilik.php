<?php
require_once "../../helpers/auth.php";
require_login();
require_role('pemilik');
?>

<?php
// session_start();
$isLoggedIn = isset($_SESSION['user_id']);
$namaLengkap = $_SESSION['nama_lengkap'] ?? 'User';
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
  <?php include '../partials/navbar.php'; ?>



  <!-- Hero Section -->
  <section class="hero text-center">
    <div class="container">
      <h1>Tawarkan Kost, Rumah, atau Kontrakan Mudah!</h1>
      <p class="lead">Tawarkan properti idealmu atau pasarkan propertimu dengan mudah.</p>
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
            $detailLink = "../detail_properti.php?kategori=" . urlencode($prop['kategori']) . "&id=" . urlencode($prop['id']);

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

<!-- Footer -->
<?php include 'partials/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/navbar.js"></script>

</body>
</html>
