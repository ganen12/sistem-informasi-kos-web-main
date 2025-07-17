<?php
require_once "../../helpers/auth.php";
require_login();
?>

<?php

include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

?>

<?php
$editExpense = null;
if (isset($_GET['edit'])) {
    $editExpenseId = $_GET['edit'];
    $queryEdit = "
    SELECT e.*
    FROM expenses e
    INNER JOIN users u ON e.user_id = u.id
    WHERE e.expense_id = ? AND u.id = ?
    ORDER BY e.expense_id
    ";

    $stmt = mysqli_prepare($link, $queryEdit);
    mysqli_stmt_bind_param($stmt, "ii", $editExpenseId, $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $editExpense = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hunian.id - Tambah Properti</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #e4e4e4;
    }
    .card-icon {
      font-size: 1.8rem;
    }
    .card-stat {
      min-height: 80px;
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
        <?php $activeMenu = 'pengeluaran'; ?> 
      <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>

      <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto col-lg-10" style="padding: 0;">
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
        <div class="container mt-4">
          <div class="row mb-4 align-items-center">
              <div class="col-md-4">
                <label for="filterRange" class="form-label fw-semibold">Filter Waktu</label>
                <select class="form-select" id="filterRange">
                  <option value="bulan_ini">Bulan Ini</option>
                  <option value="bulan_lalu">Bulan Lalu</option>
                  <option value="3_bulan">3 Bulan Terakhir</option>
                  <option value="custom">Custom Range</option>
                </select>
              </div>
              <div class="col-md-4 d-none" id="customRange">
                <label class="form-label fw-semibold">Pilih Tanggal</label>
                <div class="d-flex gap-2">
                  <input type="date" id="startDate" class="form-control" />
                  <input type="date" id="endDate" class="form-control" />
                </div>
              </div>
          </div>              
          <div class="row g-3">
            <div class="col-md-4">
              <div class="card text-white bg-warning card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h4 id="totalPengeluaran">0</h4>
                    <p class="mb-0">Total Pengeluaran</p>
                  </div>
                  <i class="bi bi-cash-stack card-icon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card text-white bg-success card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h5 id="jumlahTerakhir">0</h5>
                    <p class="mb-0">Pengeluaran Terakhir</p>
                  </div>
                  <i class="bi bi-check-circle-fill card-icon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card text-white bg-danger card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h5 id="jumlahTerbanyak">0</h5>
                    <p class="mb-0">Terbesar</p>
                  </div>
                  <i class="bi bi-arrow-up-circle-fill card-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
          


        <div class="container-fluid mt-4">
          <div class="card p-4">
            <div class="d-flex justify-content-between mb-3">
              <h5>Data Pengeluaran</h5>
              <div class="d-flex gap-2">
                <input type="text" class="form-control" placeholder="Cari..." id="searchInput" />
                <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalPengeluaran">
                  <i class="bi bi-plus-circle me-2"></i>Tambah Pengeluaran
                </button>
              </div>
            </div>
            <table class="table table-striped">
            <?php
                // Ambil data kamar beserta nama properti sewa milik user
                $queryExpense = "SELECT expenses.* FROM expenses
                            INNER JOIN users ON expenses.user_id = users.id
                            WHERE expenses.user_id = $user_id
                            ORDER BY expenses.expense_id";
                $resultExpense = mysqli_query($link, $queryExpense);
            ?>
              <thead class="table-dark">
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Deskripsi</th>
                  <th>Jumlah Pengeluaran (Rp)</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tabelPengeluaran">
                <?php 
                $no = 1;
                while ($row = mysqli_fetch_assoc($resultExpense)) { 
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['expense_date']); ?></td>
                    <td><?= htmlspecialchars($row['description']); ?></td>
                    <td><?= htmlspecialchars($row['expense_total']); ?></td>
                    <td>                   
                        <a href="pengeluaran.php?edit=<?= $row['expense_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="../../controllers/pengeluaran/aksi_hapus_pengeluaran.php?expense_id=<?= $row['expense_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus expense ini?')">Hapus</a>
                    </td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

<!-- Modal Tambah Pengeluaran -->
<div class="modal fade" id="modalPengeluaran" tabindex="-1" aria-labelledby="modalPengeluaranLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPengeluaranLabel">Tambah Pengeluaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form action="../../controllers/pengeluaran/aksi_tambah_pengeluaran.php" method="POST" id="formPengeluaran">
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="expense_date" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text" class="form-control" id="deskripsi" name="description" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah (Rp)</label>
            <input type="number" class="form-control rupiah" id="jumlah" name="expense_total" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Pengeluaran -->
  <?php if ($editExpense): ?>
<div class="modal fade show d-block" id="modalPengeluaran" tabindex="-1" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalPengeluaranLabel">Edit Pengeluaran</h5>
        <a href="pengeluaran.php" class="btn-close"></a>
      </div>
      <div class="modal-body">
        <form action="../../controllers/pengeluaran/aksi_edit_pengeluaran.php" method="POST" id="formPengeluaran">
          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" value="<?= $editExpense['expense_date'] ?>" name="expense_date" required>
          </div>
            <input type="hidden" name="expense_id" value="<?= $editExpense['expense_id'] ?>">
          <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <input type="text" class="form-control" id="deskripsi" value="<?= $editExpense['description'] ?>" name="description" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jumlah (Rp)</label>
            <input type="number" class="form-control rupiah" id="jumlah" value="<?= $editExpense['expense_total'] ?>" name="expense_total" required>
          </div>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php endif; ?>

  
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
  
  <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999">
    <div id="toastNotifikasi" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body" id="pesanToast">Berhasil!</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
  </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/pengeluaran.js"></script>
<script src="js/navbar.js"></script>
  
</body>
</html>
