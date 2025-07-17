<?php
require_once "../../helpers/auth.php";
require_login();
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


  <?php
  include "../../../config/database.php";
  // session_start();
  $user_id = $_SESSION['user_id'] ?? 0;

  $query = "SELECT 
      p.payment_id,
      p.payment_date,
      p.payment_total,
      p.amount_paid,
      p.payment_due_date,
      p.status,
      t.name AS tenant_name
    FROM payments p
    JOIN room_transactions rt ON p.payment_id = rt.payment_id
    JOIN tenants t ON rt.tenant_id = t.tenant_id
    JOIN rooms r ON rt.room_no = r.room_no
    JOIN rental_properties rp ON r.rental_property_id = rp.rental_property_id
    WHERE rp.user_id = ?
    ORDER BY p.payment_date DESC";

  $stmt = mysqli_prepare($link, $query);
  mysqli_stmt_bind_param($stmt, "i", $user_id);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);

  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  $total_pembayaran = 0;
  $total_dibayar = 0;
  $total_belum_dibayar = 0;

  foreach ($rows as $row) {
    $total_pembayaran += $row['payment_total'];
    $total_dibayar    += $row['amount_paid'];

    if ($row['status'] !== 'Lunas') {
      $total_belum_dibayar += $row['payment_total'] - $row['amount_paid'];
    }
  }

  ?>

  <!-- Main Layout -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
        <?php $activeMenu = 'pembayaran'; ?> 
        <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>

      <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto col-lg-10 p-4">
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
                  <h4>Rp <?= number_format($total_pembayaran, 0, ',', '.') ?></h4>
                  <p class="mb-0">Total Pembayaran</p>
                  </div>
                  <i class="bi bi-calendar-event-fill card-icon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card text-white bg-success card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h5>Rp <?= number_format($total_dibayar, 0, ',', '.') ?></h5>
                    <p class="mb-0">Dibayar</p>
                  </div>
                  <i class="bi bi-arrow-up-right-circle-fill card-icon"></i>
                </div>
              </div>
            </div>
            <div class="col-md-4">
              <div class="card text-white bg-danger card-stat p-3">
                <div class="d-flex justify-content-between align-items-center">
                  <div>
                    <h5>Rp <?= number_format($total_belum_dibayar, 0, ',', '.') ?></h5>
                    <p class="mb-0">Belum Dibayar</p>
                  </div>
                  <i class="bi bi-arrow-down-left-circle-fill card-icon"></i>
                </div>
              </div>
            </div>
          </div>
        </div>
              

        <!-- CRUD Pembayaran -->
        <div class="container-fluid mt-4">
          <div class="card p-4">
            <div class="d-flex justify-content-between mb-3">
              <h5>Data Pembayaran</h5>
              <div class=" d-flex gap-2">
                <input type="text" class="form-control" placeholder="Cari..." id="searchInput" />
                  <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#tambahPembayaranModal">
                      <i class="bi bi-plus-circle me-2"></i>Tambah Pembayaran
                  </button>
              </div>
            </div>

            <table class="table table-striped">
              <thead class="table-dark">
                <tr>
                  <th>Tanggal Pembayaran</th>
                  <th>Nama Penyewa</th>
                  <th>Total Tagihan</th>
                  <th>Jumlah Dibayar</th>
                  <th>Jatuh Tempo</th>
                  <th>Status</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody id="tabelPembayaran">
                <?php if (count($rows) > 0): ?>
                <?php foreach ($rows as $row): ?>
                  <?php
                    $badge = 'bg-danger';
                    if ($row['status'] === 'Lunas') {
                      $badge = 'bg-success';
                    } elseif ($row['status'] === 'Belum Lunas') {
                      $badge = 'bg-warning text-dark';
                    }
                  ?>
                  <tr>
                    <td><?= date('d M Y', strtotime($row['payment_date'])) ?></td>
                    <td><?= htmlspecialchars($row['tenant_name']) ?></td>
                    <td>Rp <?= number_format($row['payment_total'], 0, ',', '.') ?></td>
                    <td>Rp <?= number_format($row['amount_paid'], 0, ',', '.') ?></td>
                    <td><?= date('d M Y', strtotime($row['payment_due_date'])) ?></td>
                    <td><span class="badge <?= $badge ?>"><?= $row['status'] ?></span></td>
                    <td>
                    <button 
                      class="btn btn-sm btn-outline-primary btn-edit-pembayaran"
                      data-bs-toggle="modal"
                      data-bs-target="#editPembayaranModal"
                      data-id="<?= $row['payment_id'] ?>"
                      data-date="<?= $row['payment_date'] ?>"
                      data-name="<?= htmlspecialchars($row['tenant_name']) ?>"
                      data-total="<?= $row['payment_total'] ?>"
                      data-paid="<?= $row['amount_paid'] ?>"
                      data-due="<?= $row['payment_due_date'] ?>"
                      data-status="<?= $row['status'] ?>"
                    >
                      <i class="bi bi-pencil"></i>
                    </button>
                    <form method="POST" action="../../controllers/pembayaran/aksi_hapus_pembayaran.php" class="d-inline"
                          onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                      <input type="hidden" name="payment_id" value="<?= $row['payment_id'] ?>">
                      <button type="submit" class="btn btn-sm btn-outline-danger">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </td>
                  </tr>
                <?php endforeach; ?>
              <?php else: ?>
                <tr><td colspan="7" class="text-center text-muted">Belum ada data pembayaran.</td></tr>
              <?php endif; ?>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Tambah Pembayaran -->
  <div class="modal fade" id="tambahPembayaranModal" tabindex="-1" aria-labelledby="tambahPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="../../controllers/pembayaran/aksi_tambah_pembayaran.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="tambahPembayaranLabel">Tambah Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <div class="mb-3">
              <label class="form-label">Tanggal Pembayaran</label>
              <input type="date" class="form-control" name="payment_date" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Nama Penyewa</label>
              <input type="text" class="form-control" id="nama_penyewa" readonly>

              <select class="form-select" name="room_transaction_id" id="room_transaction_select" required>
                <option value="" disabled selected>Pilih Penyewa</option>
                <?php
                $queryTrx = "SELECT 
                              rt.room_transaction_id,
                              t.name AS tenant_name,
                              r.price_per_month,
                              rt.rental_duration
                            FROM room_transactions rt
                            JOIN tenants t ON rt.tenant_id = t.tenant_id
                            JOIN rooms r ON rt.room_no = r.room_no
                            JOIN rental_properties rp ON r.rental_property_id = rp.rental_property_id
                            WHERE rp.user_id = $user_id";

                $resultTrx = mysqli_query($link, $queryTrx);
                while ($row = mysqli_fetch_assoc($resultTrx)) {
                    $duration = (int) filter_var($row['rental_duration'], FILTER_SANITIZE_NUMBER_INT);
                    $total_payment = $row['price_per_month'] * $duration;
                    echo "<option value='{$row['room_transaction_id']}' data-total='{$total_payment}' data-name='".htmlspecialchars($row['tenant_name'])."'>
                            {$row['tenant_name']} (Rp " . number_format($total_payment, 0, ',', '.') . ")
                          </option>";
                }
                ?>
              </select>
            </div>

            <div class="mb-3">
              <label class="form-label">Total Tagihan (Rp)</label>
              <input type="number" class="form-control" name="payment_total" id="payment_total" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jumlah Dibayar (Rp)</label>
              <input type="number" class="form-control" name="amount_paid" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jatuh Tempo</label>
              <input type="date" class="form-control" name="payment_due_date" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Status Pembayaran</label>
              <select class="form-select" name="status" required>
                <option value="Lunas">Lunas</option>
                <option value="Belum Lunas">Belum Lunas</option>
                <option value="Tertunda">Tertunda</option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Modal Edit Pembayaran -->
  <div class="modal fade" id="editPembayaranModal" tabindex="-1" aria-labelledby="editPembayaranLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form action="../../controllers/pembayaran/aksi_edit_pembayaran.php" method="POST">
          <div class="modal-header">
            <h5 class="modal-title" id="editPembayaranLabel">Edit Pembayaran</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>

          <div class="modal-body">
            <input type="hidden" name="payment_id" id="edit_payment_id">

            <div class="mb-3">
              <label class="form-label">Tanggal Pembayaran</label>
              <input type="date" class="form-control" name="payment_date" id="edit_payment_date" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Total Tagihan (Rp)</label>
              <input type="number" class="form-control" name="payment_total" id="edit_payment_total" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jumlah Dibayar (Rp)</label>
              <input type="number" class="form-control" name="amount_paid" id="edit_amount_paid" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Jatuh Tempo</label>
              <input type="date" class="form-control" name="payment_due_date" id="edit_payment_due_date" required>
            </div>

            <div class="mb-3">
              <label class="form-label">Status Pembayaran</label>
              <select class="form-select" name="status" id="edit_status" required>
                <option value="Lunas">Lunas</option>
                <option value="Belum Lunas">Belum Lunas</option>
                <option value="Tertunda">Tertunda</option>
              </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../../public/js//pembayaran.js"></script>

</body>
</html>
