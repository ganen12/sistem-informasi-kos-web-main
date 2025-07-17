<?php
require_once "../../helpers/auth.php";
require_login();
?>

<?php
    include "../../../config/database.php";

    $user_id = $_SESSION['user_id'] ?? 0;

?>

<?php
// Ambil penyewa yang pernah/masih menyewa kamar milik user saat ini
$queryTenant = "
    SELECT tenant_id, name, email, phone_number, address
    FROM tenants
    WHERE user_id = '$user_id'
    ORDER BY name
";

$resultTenant = mysqli_query($link, $queryTenant);
    // cek apakah query berhasil
    // cek apakah ada data
if (mysqli_num_rows($resultTenant) == 0) {
    echo "Tidak ada penyewa yang ditemukan.";
}
?>

<?php
$editTenant = null;
if (isset($_GET['edit'])) {
    $editTenantId = $_GET['edit'];
    $queryEdit = "SELECT * FROM tenants WHERE tenant_id = ? AND user_id = ?";
    $stmt = mysqli_prepare($link, $queryEdit);
    mysqli_stmt_bind_param($stmt, "ii", $editTenantId, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $editTenant = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
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
      <?php $activeMenu = 'penyewa'; ?> 
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

        <div class="container-fluid mt-4">
          <div class="card p-4">
            <div class="d-flex justify-content-between mb-3">
              <h5>Daftar Penyewa</h5>
              <div class=" d-flex gap-2">
                <input type="text" class="form-control" placeholder="Cari..." id="searchInput" />
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahPenyewaModal">
                      <i class="bi bi-plus-circle me-2"></i>Tambah Penyewa
                </button>
              </div>
            </div>

            <table class="table table-striped">
            <?php
                // Ambil data penyewa 

            ?>                
              <thead class="table-dark">
                <tr>
                  <th>Nomor</th>
                  <th>Nama Penyewa</th>
                  <th>Email</th>
                  <th>No HP</th>
                  <th>Alamat Asal</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tabelPenyewa">
                <?php
                if (mysqli_num_rows($resultTenant) > 0):
                    $no = 1;
                    while ($t = mysqli_fetch_assoc($resultTenant)):
                ?>
                    <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($t['name']) ?></td>
                    <td><?= htmlspecialchars($t['email']) ?></td>
                    <td><?= htmlspecialchars($t['phone_number']) ?></td>
                    <td><?= htmlspecialchars($t['address']) ?></td>
                    <td>
                    <a href="penyewa.php?edit=<?= $t['tenant_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                    <a href="../../controllers/penyewa/aksi_hapus_penyewa.php?tenant_id=<?= $t['tenant_id'] ?>"
                        class="btn btn-sm btn-danger"
                        onclick="return confirm('Yakin ingin menghapus penyewa <?= addslashes($t['name']) ?>?');">
                        Hapus
                    </a>
                    </td>                    
                    </tr>
                <?php
                    endwhile;
                else:
                ?>
                    <tr>
                    <td colspan="5" class="text-center text-muted">Belum ada penyewa.</td>
                    </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Tambah Penyewa -->
  <div class="modal fade" id="tambahPenyewaModal" tabindex="-1" aria-labelledby="tambahPenyewaLabel" aria-hidden="true">
    <div class="modal-dialog">
      <form action="../../controllers/penyewa/aksi_tambah_penyewa.php" method="POST" id="formPenyewa" class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="tambahPenyewaLabel">Tambah Penyewa</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nama Penyewa</label>
            <input type="text" class="form-control" id="namaPenyewa" name="nama" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
          </div>
          <div class="mb-3">
            <label class="form-label">No HP</label>
            <input type="text" class="form-control" id="noHP" name="phone_number" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Asal</label>
            <input type="text" class="form-control" id="alamat" name="address" required>
          </div>
          <!-- <div class="mb-3">
            <label class="form-label">Tanggal Masuk</label>
            <input type="date" class="form-control" id="tanggalMasuk">
          </div>
          <div class="mb-3">
            <label class="form-label">Durasi Sewa</label>
            <input type="text" class="form-control" id="durasiSewa" placeholder="Contoh: 6 bulan">
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Kamar</label>
            <input type="text" class="form-control" id="nomorKamar">
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select class="form-select" id="status">
              <option value="Aktif">Aktif</option>
              <option value="Sudah Keluar">Sudah Keluar</option>
              <option value="Diblokir">Diblokir</option>
            </select>
          </div> -->
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>

<!-- Modal Edit Penyewa (muncul otomatis jika ada $_GET['edit']) -->
<?php if ($editTenant): ?>
<div class="modal fade show d-block" id="editPenyewaModal" tabindex="-1" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog">
    <form action="../../controllers/penyewa/aksi_edit_penyewa.php" method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Penyewa</h5>
        <a href="penyewa.php" class="btn-close"></a>
      </div>
      <div class="modal-body">
        <input type="hidden" name="tenant_id" value="<?= $editTenant['tenant_id'] ?>">
        <div class="mb-3">
          <label class="form-label">Nama Penyewa</label>
          <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($editTenant['name']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($editTenant['email']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">No HP</label>
          <input type="text" name="phone_number" class="form-control" value="<?= htmlspecialchars($editTenant['phone_number']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Alamat</label>
          <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($editTenant['address']) ?>" required>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>


<!-- Toast -->
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toastPenyewa" class="toast align-items-center text-bg-success border-0" role="alert">
      <div class="d-flex">
        <div class="toast-body" id="toastMessage">Berhasil!</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
  <!-- <script src="js/penyewa.js"></script>
  <script src="js/navbar.js"></script> -->

</body>
</html>
