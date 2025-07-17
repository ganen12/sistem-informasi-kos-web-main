<?php
require_once "../../helpers/auth.php";
require_login();
?>

<?php

include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

?>

<?php
$editKeluhan = null;
if (isset($_GET['edit'])) {
    $editKeluhanId = $_GET['edit'];
    $queryEdit = "
    SELECT c.*, t.name
    FROM complaints c
    INNER JOIN tenants t ON c.tenant_id = t.tenant_id
    INNER JOIN users u ON t.user_id = u.id
    WHERE t.user_id = ? AND c.complaint_id = ?
    ORDER BY c.complaint_id
    ";

    $stmt = mysqli_prepare($link, $queryEdit);
    mysqli_stmt_bind_param($stmt, "ii", $user_id, $editKeluhanId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $editKeluhan = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Keluhan Penyewa</title>
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
    .brand {
      background-color: #cfc6b4;
    }
    .menu-list {
      padding: 0.75rem 0;
      font-size: 20px;
    }
    .input-fields {
        width: 700px;
    }
  </style>
</head>
<body>
      <!-- Navbar -->
  <?php include '../partials/navbar.php'; ?>

<div class="container-fluid">
  <div class="row">
    <!-- Sidebar -->
    <?php $activeMenu = 'keluhan'; ?> 
    <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>

    <!-- Main -->
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
      <!-- Top Navbar -->
      <!-- <div class="d-flex justify-content-between align-items-center py-3 brand px-4">
        <div class="h5 mb-0">Keluhan</div>
        <div class="d-flex align-items-center gap-3">
          <div class="dropdown-center">
            <i class="bi bi-bell-fill fs-5 dropdown-toggle" id="notifDropdown" data-bs-toggle="dropdown" aria-expanded="false" role="button"></i>
            <ul class="dropdown-menu dropdown-menu-dark">
              <li><a class="dropdown-item" href="#">test announce</a></li>
              <li><a class="dropdown-item" href="#">Another action</a></li>
              <li><a class="dropdown-item" href="#">Something else here</a></li>
            </ul>
          </div>
          <div><i class="bi bi-person-circle me-1"></i> sadmin</div>
        </div>
      </div> -->

      <div class="container-fluid mt-4">
        <div class="card p-4">
          <div class="d-flex justify-content-between mb-3">
            <h5>Daftar Keluhan</h5>
            <div class=" d-flex gap-2">
              <input type="text" class="form-control" placeholder="Cari..." id="searchInput" />
              <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalKeluhan">
                <i class="bi bi-plus-circle me-2"></i>Tambah Keluhan
              </button>
            </div>
          </div>
        
          <table class="table table-striped">
            <?php
                // Ambil data kamar beserta nama properti sewa milik user
                $queryKamar = "SELECT c.*, t.name, r.room_no FROM complaints c
                            INNER JOIN tenants t ON c.tenant_id = t.tenant_id
                            INNER JOIN users u ON t.user_id = u.id
                            INNER JOIN room_transactions rt ON t.tenant_id = rt.tenant_id
                            INNER JOIN rooms r ON rt.room_no = r.room_no 
                            WHERE t.user_id = $user_id
                            ORDER BY c.complaint_id";
                $resultKamar = mysqli_query($link, $queryKamar);
            ?>
            <thead class="table-dark">
              <tr>
                <th>No</th>
                <th>Nama Penyewa</th>
                <th>No Kamar</th>
                <th>Tanggal Keluhan</th>
                <th>Isi Keluhan</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody id="tabelKeluhan">
                <?php 
                $room_no = '';
                $no = 1;
                while ($row = mysqli_fetch_assoc($resultKamar)) { 
                    // Jika nomor kamar tidak ada ATAU bernilai null, tampilkan "-"

                    if (empty($row['room_no']) || is_null($row['room_no'])) {
                        $room_no = '-';
                    } else {
                        $room_no = htmlspecialchars($row['room_no']);
                    }
                ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= htmlspecialchars($row['name']); ?></td>
                    <td><?= $room_no ?></td>
                    <td><?= htmlspecialchars($row['complaint_date']); ?></td>
                    <td><?= htmlspecialchars($row['complaint_description']); ?></td>
                    <td><?= htmlspecialchars($row['status']); ?></td>
                    <td>                   
                        <a href="keluhan.php?edit=<?= $row['complaint_id'] ?>" class="btn btn-sm btn-primary">Edit</a>
                        <a href="../../controllers/keluhan/aksi_hapus_keluhan.php?complaint_id=<?= $row['complaint_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus keluhan ini?')">Hapus</a>
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

<!-- Modal Keluhan -->
<div class="modal fade" id="modalKeluhan" tabindex="-1" aria-labelledby="modalKeluhanLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form action="../../controllers/keluhan/aksi_tambah_keluhan.php" method="POST" id="formKeluhan" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Input Keluhan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nama Penyewa</label>
              <select class="form-select" id="tenant" name="tenant_id" required>
                <?php
                $queryTenants = "SELECT tenants.tenant_id, tenants.name 
                                 FROM tenants 
                                 INNER JOIN users ON tenants.user_id = users.id
                                 INNER JOIN room_transactions ON tenants.tenant_id = room_transactions.tenant_id
                                 WHERE tenants.user_id = $user_id AND users.id = $user_id";
                $tenants = mysqli_query($link, $queryTenants); ?>
                <?php while ($row = mysqli_fetch_assoc($tenants)): ?>
                    <option value='<?= $row['tenant_id'] ?>'><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
                
              </select>        
        </div>
        <!-- <div class="mb-3"><label class="form-label">Nomor Kamar</label><input type="text" class="form-control" id="nomorKamar" required></div> -->
        <div class="mb-3"><label class="form-label">Tanggal Keluhan</label><input name="complaint_date" type="date" class="form-control" id="tanggalKeluhan" required></div>
        <div class="mb-3"><label class="form-label">Isi Keluhan</label><textarea name="complaint_description" class="form-control" id="isiKeluhan" rows="3" required></textarea></div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select class="form-select" id="statusKeluhan" name="status" required>
            <option value="Belum Ditangani">Belum Ditangani</option>
            <option value="Diproses">Diproses</option>
            <option value="Selesai">Selesai</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>

<!-- Modal edit keluhan -->
 <?php if ($editKeluhan): ?>
<div class="modal fade show d-block" id="modalKeluhan" tabindex="-1" style="background:rgba(0,0,0,0.5);">
  <div class="modal-dialog">
    <form action="../../controllers/keluhan/aksi_edit_keluhan.php" method="POST" id="formKeluhan" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Keluhan</h5>
        <a href="keluhan.php" class="btn-close"></a>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label class="form-label">Nama Penyewa</label>
              <select class="form-select" id="tenant" name="tenant_id" required>
                <?php
                $queryTenants = "SELECT tenants.tenant_id, tenants.name 
                                 FROM tenants 
                                 INNER JOIN users ON tenants.user_id = users.id
                                 INNER JOIN room_transactions ON tenants.tenant_id = room_transactions.tenant_id
                                 WHERE tenants.user_id = $user_id AND users.id = $user_id";
                $tenants = mysqli_query($link, $queryTenants); ?>
                <?php while ($row = mysqli_fetch_assoc($tenants)): ?>
                    <option value='<?= $row['tenant_id'] ?>' <?= $row['tenant_id'] == $editKeluhan['tenant_id'] ? 'selected' : '' ?>><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
                
              </select>        
        </div>
        <input type="hidden" name="complaint_id" value="<?= $editKeluhan['complaint_id'] ?>">
        <!-- <div class="mb-3"><label class="form-label">Nomor Kamar</label><input type="text" class="form-control" id="nomorKamar" required></div> -->
        <div class="mb-3"><label class="form-label">Tanggal Keluhan</label>
            <input name="complaint_date" type="date" class="form-control" id="tanggalKeluhan" value="<?= htmlspecialchars($editKeluhan['complaint_date']) ?>" required>
        </div>
        <div class="mb-3"><label class="form-label">Isi Keluhan</label>
            <textarea name="complaint_description" class="form-control" id="isiKeluhan" rows="3" required><?= htmlspecialchars($editKeluhan['complaint_description']) ?></textarea>
        </div>
        <div class="mb-3"><label class="form-label">Status</label>
          <select class="form-select" id="statusKeluhan" name="status" required>
            <option value="Belum Ditangani" <?= $editKeluhan['status'] == 'Belum Ditangani' ? 'selected' : '' ?>>Belum Ditangani</option>
            <option value="Diproses" <?= $editKeluhan['status'] == 'Diproses' ? 'selected' : '' ?>>Diproses</option>
            <option value="Selesai" <?= $editKeluhan['status'] == 'Selesai' ? 'selected' : '' ?>>Selesai</option>
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
<?php endif; ?>

<!-- logout confirmation modal -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content bg-dark text-light">
      <div class="modal-header border-secondary">
        <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <p>Apakah Anda yakin ingin keluar dari sistem?</p>
      </div>
      <div class="modal-footer border-0 justify-content-start">
        <button type="button" class="btn btn-danger" id="confirmLogout">Logout</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/keluhan.js"></script>

<script src="js/global.js"></script>

</body>
</html>
