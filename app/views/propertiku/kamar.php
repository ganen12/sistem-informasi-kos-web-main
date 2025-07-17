<?php
require_once "../../helpers/auth.php";
require_login();
?>

<?php
    include "../../../config/database.php";

    $user_id = $_SESSION['user_id'] ?? 0;
?>

<?php
$editData = null;
if (isset($_GET['edit'])) {
    $editRoomNo = $_GET['edit'];
    $queryEdit = "SELECT r.*, p.property_name FROM rooms r JOIN rental_properties p ON r.rental_property_id = p.rental_property_id WHERE r.room_no = '$editRoomNo' LIMIT 1";
    $resultEdit = mysqli_query($link, $queryEdit);
    $editData = mysqli_fetch_assoc($resultEdit);
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kelola Kamar</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #e4e4e4;
    }
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        background-color: #252321;
        color: white;
        z-index: 1030; /* agar di atas konten lain */
        overflow-y: auto;
        margin-top: 56px;
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
      padding: 0.75rem 0;
      font-size: 20px;
    }
  </style>
</head>
<body>
  <!-- Navbar -->
  <?php include '../partials/navbar.php'; ?>

  <!-- Main Layout -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
      <?php $activeMenu = 'kamar'; ?> 
      <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>
        
      <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto col-lg-10 p-4">
            <!-- Alert Messages -->
        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['error']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>

        <?php if (isset($_GET['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_GET['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        <div class="card p-4">
          <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
              <h5 class="mb-1">Kelola Kamar</h5>
                <p class="mb-0">Daftar kamar yang tersedia di properti sewa Anda.</p>
              <div class="text-muted small">Properti: <strong id="judulPropertiAktif">...</strong></div>
            </div>
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambahKamarModal">+ Tambah Kamar</button>
          </div>

          <!-- Tabel Kamar -->
          <div class="table-responsive">
            <?php
                // Ambil data kamar beserta nama properti sewa milik user
                $queryKamar = "SELECT r.room_no, r.status, r.price_per_month, p.property_name
                            FROM rooms r
                            JOIN rental_properties p ON r.rental_property_id = p.rental_property_id
                            WHERE p.user_id = '$user_id'
                            ORDER BY p.property_name, r.room_no";
                $resultKamar = mysqli_query($link, $queryKamar);
            ?>
            <table class="table table-striped">
                <thead class="table-dark">
                    <tr>
                    <th>Properti</th> 
                    <th>No Kamar</th>
                    <th>Status</th>
                    <th>Harga / Bulan</th>
                    <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelKamar">
                <?php if (mysqli_num_rows($resultKamar) > 0): ?>
                    <?php while($row = mysqli_fetch_assoc($resultKamar)): ?>
                    <?php
                    // tentukan kelas warna berdasarkan status
                    $statusClass = '';
                    if ($row['status'] === 'Disewa') {
                        $statusClass = 'text-danger';   // merah
                    } elseif ($row['status'] === 'Tersedia') {
                        $statusClass = 'text-success';  // hijau
                    }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($row['property_name']) ?></td>
                        <td><?= htmlspecialchars($row['room_no']) ?></td>
                        <td class="<?= $statusClass ?>"><strong>
                            <?= htmlspecialchars($row['status']) ?></strong>
                        </td>
                        <td>Rp <?= number_format($row['price_per_month'], 0, ',', '.') ?></td>
                        <td>
                        <a href="kamar.php?edit=<?= urlencode($row['room_no']) ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="../../controllers/kamar/aksi_hapus_kamar.php?room_no=<?= urlencode($row['room_no']) ?>"
                            class="btn btn-sm btn-danger"
                            onclick="return confirm('Yakin hapus kamar <?= addslashes($row['room_no']) ?>?');">
                            Hapus
                        </a>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada kamar.</td>
                    </tr>
                <?php endif; ?>
                </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Tambah Kamar -->
  <div class="modal fade" id="tambahKamarModal" tabindex="-1">
    <div class="modal-dialog">
      <form action="../../controllers/kamar/aksi_tambah_kamar.php" method="POST" id="formKamar" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Tambah Kamar</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Pilih Properti</label>
            <?php
                // Ambil properti sewa milik user
                $queryProperti = "SELECT rental_property_id, property_name FROM rental_properties WHERE user_id = '$user_id'";
                $resultProperti = mysqli_query($link, $queryProperti);
            ?>
                <select class="form-select" name="rental_property_id" id="selectProperti" required>
                <?php while($row = mysqli_fetch_assoc($resultProperti)): ?>
                    <option value="<?= $row['rental_property_id'] ?>"><?= htmlspecialchars($row['property_name']) ?></option>
                <?php endwhile; ?>
                </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Kamar</label>
            <input type="text" class="form-control" id="nomor" name="room_no" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" id="statusKamar" required>
              <option value="Tersedia">Tersedia</option>
              <option value="Disewa">Disewa</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga / Bulan</label>
            <input type="number" class="form-control rupiah" name="price_per_month" id="hargaPerBulan" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

    <!-- Modal Edit Kamar (muncul otomatis jika ada $_GET['edit']) -->
  <?php if ($editData): ?>
  <div class="modal fade show d-block" id="editKamarModal" tabindex="-1" style="background:rgba(0,0,0,0.5);">
    <div class="modal-dialog">
      <form action="../../controllers/kamar/aksi_edit_kamar.php" method="POST" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Kamar</h5>
          <a href="kamar.php" class="btn-close"></a>
        </div>
        <div class="modal-body">
          <input type="hidden" name="room_no" value="<?= htmlspecialchars($editData['room_no']) ?>">
          <input type="hidden" name="rental_property_id" value="<?= htmlspecialchars($editData['rental_property_id']) ?>">
          <div class="mb-3">
            <label class="form-label">Pilih Properti</label>
            <?php
                // Ambil properti sewa milik user
                $queryProperti = "SELECT rental_property_id, property_name FROM rental_properties WHERE user_id = '$user_id'";
                $resultProperti = mysqli_query($link, $queryProperti);
            ?>
                <select class="form-select" name="rental_property_id" id="selectProperti" required>
                <?php while($row = mysqli_fetch_assoc($resultProperti)): ?>
                    <option value="<?= $row['rental_property_id'] ?>" <?= $row['rental_property_id'] == $editData['rental_property_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($row['property_name']) ?>
                    </option>
                <?php endwhile; ?>
                </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Kamar</label>
            <input type="text" class="form-control" disabled value="<?= htmlspecialchars($editData['room_no']) ?>" readonly>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="Tersedia" <?= $editData['status'] == 'Tersedia' ? 'selected' : '' ?>>Tersedia</option>
              <option value="Disewa" <?= $editData['status'] == 'Disewa' ? 'selected' : '' ?>>Disewa</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga / Bulan</label>
            <input type="number" class="form-control" name="price_per_month" value="<?= htmlspecialchars($editData['price_per_month']) ?>" required>
          </div>
            <!-- Hidden: nomor kamar lama -->

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Update</button>
        </div>
      </form>
    </div>
  </div>
  <?php endif; ?>


  <!-- Toast -->
  <div class="position-fixed bottom-0 end-0 p-3">
    <div id="toastKamar" class="toast align-items-center text-bg-success border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body" id="toastMessage">Kamar berhasil ditambahkan!</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>
  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="../../../public/js/kamar.js"></script>
  <script src="../../../public/js/navbar.js"></script> -->
  <!-- <script src="js/navbar.js"></script> -->

</body>
</html>
