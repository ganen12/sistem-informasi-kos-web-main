<?php
require_once "../../helpers/auth.php";
require_login();
?>

<?php
    include "../../../config/database.php";

    $user_id = $_SESSION['user_id'] ?? 0;
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
    body {
        font-family: 'Montserrat', sans-serif;
    }
    .hero {
        background: url('https://source.unsplash.com/1600x600/?house,building') center/cover no-repeat;
        color: white;
        padding: 5rem 2rem;
    }
    .hero h1 {
        font-weight: 700;
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
    .card-icon {
        font-size: 1.8rem;
    }
    .card-stat {
        min-height: 80px;
    }
    </style>
</head>
<body>

<?php
// Total kamar terisi & kosong
$query_rooms = "SELECT status FROM rooms r
JOIN rental_properties rp ON r.rental_property_id = rp.rental_property_id
WHERE rp.user_id = ?";
$stmt_rooms = mysqli_prepare($link, $query_rooms);
mysqli_stmt_bind_param($stmt_rooms, "i", $user_id);
mysqli_stmt_execute($stmt_rooms);
$result_rooms = mysqli_stmt_get_result($stmt_rooms);

$kamar_terisi = $kamar_kosong = 0;
while ($r = mysqli_fetch_assoc($result_rooms)) {
  if ($r['status'] === 'Disewa') $kamar_terisi++;
  if ($r['status'] === 'Tersedia') $kamar_kosong++;
}

// Total pemasukan
$query_income = "SELECT p.amount_paid FROM payments p
JOIN room_transactions rt ON p.payment_id = rt.payment_id
JOIN rooms r ON rt.room_no = r.room_no
JOIN rental_properties rp ON r.rental_property_id = rp.rental_property_id
WHERE rp.user_id = ?";
$stmt_income = mysqli_prepare($link, $query_income);
mysqli_stmt_bind_param($stmt_income, "i", $user_id);
mysqli_stmt_execute($stmt_income);
$res_income = mysqli_stmt_get_result($stmt_income);
$total_income = 0;
while ($r = mysqli_fetch_assoc($res_income)) {
  $total_income += (int)$r['amount_paid'];
}

// Total pengeluaran
$query_exp = "SELECT expense_total FROM expenses WHERE user_id = ?";
$stmt_exp = mysqli_prepare($link, $query_exp);
mysqli_stmt_bind_param($stmt_exp, "i", $user_id);
mysqli_stmt_execute($stmt_exp);
$res_exp = mysqli_stmt_get_result($stmt_exp);
$total_expense = 0;
while ($r = mysqli_fetch_assoc($res_exp)) {
  $total_expense += (int)$r['expense_total'];
}

// Pengingat Jatuh Tempo
$today = date('Y-m-d');
$query_due = "SELECT p.payment_due_date, t.name FROM payments p
JOIN room_transactions rt ON rt.payment_id = p.payment_id
JOIN tenants t ON t.tenant_id = rt.tenant_id
JOIN rooms r ON rt.room_no = r.room_no
JOIN rental_properties rp ON r.rental_property_id = rp.rental_property_id
WHERE rp.user_id = ? AND p.payment_due_date >= ? AND p.status != 'Lunas'
ORDER BY p.payment_due_date ASC LIMIT 5";
$stmt_due = mysqli_prepare($link, $query_due);
mysqli_stmt_bind_param($stmt_due, "is", $user_id, $today);
mysqli_stmt_execute($stmt_due);
$res_due = mysqli_stmt_get_result($stmt_due);
$reminders = mysqli_fetch_all($res_due, MYSQLI_ASSOC);

// Pengeluaran terbaru
$query_latest_exp = "SELECT description, expense_date FROM expenses WHERE user_id = ? ORDER BY expense_date DESC LIMIT 5";
$stmt_latest_exp = mysqli_prepare($link, $query_latest_exp);
mysqli_stmt_bind_param($stmt_latest_exp, "i", $user_id);
mysqli_stmt_execute($stmt_latest_exp);
$res_latest_exp = mysqli_stmt_get_result($stmt_latest_exp);
$latest_expenses = mysqli_fetch_all($res_latest_exp, MYSQLI_ASSOC);
?>

<?php include '../partials/navbar.php'; ?>
    

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
        <?php $activeMenu = 'dashboard'; ?> 
        <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>

        <!-- Main Content -->
        <main class="col-md-10 ms-sm-auto col-lg-10 px-4 py-4">
            <div class="row mb-4">
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
                        <input type="date" id="startDate" class="form-control">
                        <input type="date" id="endDate" class="form-control">
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-3">
                    <div class="card text-white bg-success card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 id="cardKamarTerisi"><?= $kamar_terisi ?></h4>
                                <p class="mb-0">Kamar Terisi</p>
                            </div>
                                <i class="bi bi-house-door-fill card-icon"></i>
                            </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-danger card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h4 id="cardKamarKosong"><?= $kamar_kosong ?></h4>
                                <p class="mb-0">Kamar Kosong</p>
                            </div>
                                <i class="bi bi-house-door card-icon"></i>
                            </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-warning card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 id="cardPemasukan">Rp <?= number_format($total_income, 0, ',', '.') ?></h5>
                                <p class="mb-0">Pemasukan</p>
                            </div>
                            <i class="bi bi-stack card-icon"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white bg-primary card-stat p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 id="cardPengeluaran">Rp <?= number_format($total_expense, 0, ',', '.') ?></h5>
                                <p class="mb-0">Pengeluaran</p>
                            </div>
                            <i class="bi bi-stack card-icon"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card p-3">
                        <h6>Distribusi Status Sewa</h6>
                        <canvas id="statusChart" style="max-height: 220px;"></canvas>
                        <small class="text-muted mt-2 d-block">x penyewa sudah melunaskan pembayaran.</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card p-3">
                        <h6>Pembayaran Sewa Bulan Ini</h6>
                        <canvas id="pembayaranChart" style="max-height: 249px;"></canvas>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <div class="card shadow-sm p-3 bg-light">
                        <h6 class="mb-3">🔔 Pengingat Jatuh Tempo</h6>
                        <div class="list-group" id="pengingatList"></div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card shadow-sm p-3 bg-light">
                        <h6 class="mb-3">💸 Pengeluaran Terbaru</h6>
                        <div class="list-group" id="pengeluaranTerbaruList"></div>
                    </div>
                </div>
            </div>
            </main>
        </div>
    </div>

    <!-- Modals -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Konfirmasi Logout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin keluar dari sistem?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" id="confirmLogout">Logout</button>
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content bg-dark text-light">
                <div class="modal-header border-secondary">
                    <h5 class="modal-title">Notifikasi</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="notificationMessage">Isi pesan notifikasi akan muncul di sini.</p>
                </div>
                <div class="modal-footer justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('cardKamarTerisi').textContent = "<?= $kamar_terisi ?>";
  document.getElementById('cardKamarKosong').textContent = "<?= $kamar_kosong ?>";
  document.getElementById('cardPemasukan').textContent = "Rp <?= number_format($total_income, 0, ',', '.') ?>";
  document.getElementById('cardPengeluaran').textContent = "Rp <?= number_format($total_expense, 0, ',', '.') ?>";

  const list = document.getElementById('pengingatList');
  <?php foreach ($reminders as $r): ?>
    li = document.createElement('div');
    li.className = 'list-group-item';
    li.textContent = "<?= htmlspecialchars($r['name']) ?> - Jatuh Tempo: <?= date('d M Y', strtotime($r['payment_due_date'])) ?>";
    list.appendChild(li);
  <?php endforeach; ?>

  const expList = document.getElementById('pengeluaranTerbaruList');
  <?php foreach ($latest_expenses as $e): ?>
    li = document.createElement('div');
    li.className = 'list-group-item';
    li.textContent = "<?= htmlspecialchars($e['description']) ?> - <?= date('d M Y', strtotime($e['expense_date'])) ?>";
    expList.appendChild(li);
  <?php endforeach; ?>
});
</script>

<!-- <script src="js/propertiku.js"></script>
<script src="js/logout.js"></script> -->

</body>
</html>
