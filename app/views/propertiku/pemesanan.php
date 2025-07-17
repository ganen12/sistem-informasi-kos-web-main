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
  <title>Hunian.id - Kelola Pemesanan</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
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
    .badge-pending {
      background-color: #ffc107;
      color: #212529;
    }
    .badge-paid {
      background-color: #198754;
    }
    .badge-overdue {
      background-color: #dc3545;
    }
    .card-header {
      background-color: #f8f9fa;
      border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .table-hover tbody tr:hover {
      background-color: rgba(0, 123, 255, 0.05);
    }
    .action-buttons .btn {
      padding: 0.25rem 0.5rem;
      font-size: 0.75rem;
    }
    .rental-info {
      background-color: #e9f7fe;
      border-left: 4px solid #0d6efd;
      padding: 15px;
      border-radius: 4px;
      margin-bottom: 20px;
    }
    .rental-info h6 {
      color: #0a58ca;
      font-weight: 600;
    }
    .highlight-row {
      background-color: rgba(25, 135, 84, 0.1) !important;
    }
    .extension-form {
      border: 1px solid #dee2e6;
      border-radius: 5px;
      padding: 20px;
      margin-top: 20px;
      background-color: #fff;
    }
    .summary-card {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.05);
    }
    .summary-value {
      font-weight: 600;
      font-size: 1.1rem;
    }
    .btn-extension {
      background-color: #0d6efd;
      color: white;
      border: none;
      transition: all 0.3s;
    }
    .btn-extension:hover {
      background-color: #0b5ed7;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
  </style>
</head>
<body>

      <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
    <a class="navbar-brand fw-bold text-warning ms-4" href="#">Hunian.id</a>
    <div class="container">
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
          <li class="nav-item"><a class="nav-link" href="../dashboard/dashboardpemilik.php">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="../eksplor/Beli.php">Beli</a></li>
          <li class="nav-item"><a class="nav-link" href="../eksplor/Sewa.php">Sewa</a></li>
          <li class="nav-item"><a class="nav-link active" href="#">Propertiku</a></li>
          <li class="nav-item"><a class="nav-link" href="#">Bantuan</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle me-2"></i> Seller
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item" href="#"><i class="bi bi-bookmark-heart me-2"></i> Tersimpan</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-clock-history me-2"></i> Terakhir Dilihat</a></li>
              <li><a class="dropdown-item" href="#"><i class="bi bi-chat-dots me-2"></i> Forum Pemilik</a></li>
              <li><hr class="dropdown-divider"></li>
              <li><a class="dropdown-item text-danger" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Main Layout -->
  <div class="container-fluid">
    <div class="row">
      <!-- Sidebar -->
        <?php $activeMenu = 'pemesanan'; ?> 
        <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>
      <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto col-lg-10 p-4">
        <div class="card">
          <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Kelola Pemesanan</h5>
            <div class="d-flex gap-2">
              <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#tambahPemesananModal">
                <i class="bi bi-plus-circle me-1"></i> Tambah Pemesanan
              </button>
              <div class="input-group" style="width: 250px;">
                <input type="text" class="form-control" placeholder="Cari pemesanan..." id="searchInput">
                <button class="btn btn-outline-secondary" type="button">
                  <i class="bi bi-search"></i>
                </button>
              </div>
            </div>
          </div>
          
          <div class="card-body">
            <!-- Ganti bagian tabel dengan ini -->
            <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                <tr>
                    <th>ID Transaksi</th>
                    <th>Tanggal Pendaftaran</th>
                    <th>Durasi Sewa</th>
                    <th>Penyewa</th>
                    <th>Nomor Kamar</th>
                    <th>Harga/Bulan</th>
                    <th>Total Pembayaran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Query untuk mendapatkan data pemesanan
                $query = "SELECT 
                            rt.room_transaction_id,
                            rt.registration_date,
                            rt.rental_duration,
                            rt.status,
                            t.name AS tenant_name,
                            r.room_no,
                            r.price_per_month,
                            p.status AS payment_status
                            FROM room_transactions rt
                            JOIN tenants t ON rt.tenant_id = t.tenant_id
                            JOIN rooms r ON rt.room_no = r.room_no
                            JOIN rental_properties rp ON r.rental_property_id = rp.rental_property_id
                            LEFT JOIN payments p ON rt.payment_id = p.payment_id
                            WHERE rp.user_id = ?
                            ORDER BY rt.registration_date DESC";
                
                $stmt = mysqli_prepare($link, $query);
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                
                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        // Hitung total pembayaran
                        $duration = (int) filter_var($row['rental_duration'], FILTER_SANITIZE_NUMBER_INT);
                        $total_payment = $row['price_per_month'] * $duration;
                        
                        // Tentukan kelas badge berdasarkan status
                        $status_class = 'bg-warning text-dark';
                        $status_text = 'Belum Lunas'; // Default
                        if (isset($row['status'])) {
                            $status_text = $row['status'];
                            switch ($row['status']) {
                                case 'Lunas':
                                    $status_class = 'bg-success';
                                    break;
                                case 'Belum Lunas':
                                    $status_class = 'bg-warning text-dark';
                                    break;
                                case 'Belum Bayar':
                                    $status_class = 'bg-danger';
                                    break;
                            }
                        }
                        ?>
                        <tr>
                            <td>TRX-<?= str_pad($row['room_transaction_id'], 4, '0', STR_PAD_LEFT) ?></td>
                            <td><?= date('d M Y', strtotime($row['registration_date'])) ?></td>
                            <td><?= htmlspecialchars($row['rental_duration']) ?></td>
                            <td><?= htmlspecialchars($row['tenant_name']) ?></td>
                            <td><?= htmlspecialchars($row['room_no']) ?></td>
                            <td>Rp <?= number_format($row['price_per_month'], 0, ',', '.') ?></td>
                            <td>Rp <?= number_format($total_payment, 0, ',', '.') ?></td>
                            <td><span class="badge <?= $status_class ?>"><?= $status_text ?></span></td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#detailPemesananModal<?php echo $row['room_transaction_id']; ?>" 
                                    onclick="showDetailModal(
                                        'TRX-<?= str_pad($row['room_transaction_id'], 4, '0', STR_PAD_LEFT) ?>',
                                        '<?= date('d M Y', strtotime($row['registration_date'])) ?>',
                                        '<?= htmlspecialchars($row['rental_duration']) ?>',
                                        '<?= htmlspecialchars($row['tenant_name']) ?>',
                                        '<?= htmlspecialchars($row['room_no']) ?>',
                                        'Rp <?= number_format($row['price_per_month'], 0, ',', '.') ?>',
                                        'Rp <?= number_format($total_payment, 0, ',', '.') ?>',
                                        '<?= $status_text ?>',
                                        '<?= $status_class ?>'
                                    )">
                                    <i class="bi bi-eye"></i>
                                </button>
                                <a href="pemesanan.php?edit=<?= $row['room_transaction_id'] ?>" class="btn btn-sm btn-outline-warning me-1">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <button class="btn btn-sm btn-outline-info me-1" data-bs-toggle="modal" data-bs-target="#perpanjangModal"
                                    onclick="prepareExtension(
                                        <?= $row['room_transaction_id'] ?>,
                                        '<?= htmlspecialchars($row['tenant_name']) ?>',
                                        '<?= htmlspecialchars($row['room_no']) ?>',
                                        '<?= htmlspecialchars($row['rental_duration']) ?>',
                                        <?= $row['price_per_month'] ?>,
                                        '<?= date('Y-m-d', strtotime($row['registration_date'] . ' + ' . $duration . ' months')) ?>'
                                    )">
                                    <i class="bi bi-calendar-plus"></i>
                                </button>
                                <a href="../../controllers/pemesanan/aksi_hapus_pemesanan.php?id=<?= $row['room_transaction_id'] ?>" 
                                    class="btn btn-sm btn-outline-danger"
                                    onclick="return confirm('Yakin hapus pemesanan ini?');">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="9" class="text-center text-muted">Belum ada data pemesanan.</td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            </div>
            
            <!-- Pagination -->
            <nav>
              <ul class="pagination justify-content-end">
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Sebelumnya</a>
                </li>
                <li class="page-item active"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">Selanjutnya</a>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </main>
    </div>
  </div>

<!-- Modal Tambah Pemesanan -->
<div class="modal fade" id="tambahPemesananModal" tabindex="-1" aria-labelledby="tambahPemesananModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <form action="../../controllers/pemesanan/aksi_tambah_pemesanan.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="tambahPemesananModalLabel">Tambah Pemesanan Baru</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>

        <div class="modal-body">

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="tanggalPendaftaran" class="form-label">Tanggal Pendaftaran</label>
              <input type="date" class="form-control" id="tanggalPendaftaran" name="registration_date" required>
            </div>
            <div class="col-md-6">
              <label for="durasiSewa" class="form-label">Durasi Sewa</label>
              <select class="form-select" id="durasiSewa" name="rental_duration" required>
                <option value="1 Bulan">1 Bulan</option>
                <option value="3 Bulan">3 Bulan</option>
                <option value="6 Bulan" selected>6 Bulan</option>
                <option value="12 Bulan">12 Bulan</option>
              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label for="tenant" class="form-label">Pilih Penyewa</label>
              <select class="form-select" id="tenant" name="tenant_id" required>
                <?php
                $queryTenants = "SELECT tenant_id, name FROM tenants WHERE user_id = $user_id";
                $tenants = mysqli_query($link, $queryTenants); ?>
                <?php while ($row = mysqli_fetch_assoc($tenants)): ?>
                    <option value='<?= $row['tenant_id'] ?>'><?= htmlspecialchars($row['name']) ?></option>
                <?php endwhile; ?>
                
              </select>
            </div>
          </div>

            <div class="col-md-6">
              <label for="kamar" class="form-label">Pilih Kamar Tersedia</label>
              <select class="form-select" id="kamar" name="room_no" required>
                <?php
                $queryRooms = "SELECT room_no, price_per_month FROM rooms 
                            INNER JOIN rental_properties ON rooms.rental_property_id = rental_properties.rental_property_id
                            WHERE rental_properties.user_id = 1 AND status = 'Tersedia'";
                $rooms = mysqli_query($link, $queryRooms); ?>
                <?php while ($row = mysqli_fetch_assoc($rooms)): 
                    $harga = number_format($r['price_per_month'], 0, ',', '.'); ?>
                    <option value="<?= htmlspecialchars($row['room_no']) ?>" data-harga="<?= $row['price_per_month'] ?>"><?= htmlspecialchars($row['room_no']) ?> (Rp <?= htmlspecialchars($row['price_per_month']) ?>/bulan)</option>
                <?php endwhile; ?>

              </select>
            </div>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label">Harga per Bulan</label>
              <div class="input-group">
                <span class="input-group-text"></span>
                <input value="<?= htmlspecialchars($row['price_per_month']) ?>" type="text" class="form-control" id="hargaPerBulan" disabled readonly>
              </div>
            </div>
            <div class="col-md-6">
              <label class="form-label">Total Estimasi Pembayaran</label>
              <div class="input-group">
                <span class="input-group-text">Rp</span>
                <input type="text" class="form-control" id="totalPembayaran" name="totalPembayaran" readonly>
              </div>
            </div>
          </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Pemesanan</button>
            </div>
        </div> <!-- end modal-body -->


      </form>
    </div>
  </div>
</div>

<?php
$query2 = mysqli_query($conn, "
    SELECT rt.*, t.*, r.price_per_month, r.room_no, rp.property_name, rp.location
    FROM room_transactions rt
    JOIN tenants t ON rt.tenant_id = t.tenant_id
    JOIN rooms r ON rt.room_no = r.room_no
    JOIN rental_properties rp ON r.rental_property_id = rp.rental_property_id
");

while ($row = mysqli_fetch_assoc($query2)) {
  $id = $row['room_transaction_id'];
  
  // Ekstrak angka dari rental_duration
  preg_match('/\d+/', $row['rental_duration'], $matches);
  $durasi_bulan = isset($matches[0]) ? (int)$matches[0] : 0;

  // Hitung total harga
  $total = $row['price_per_month'] * $durasi_bulan;

    // Tentukan kelas badge berdasarkan status
    $status_class_detail = 'bg-warning text-dark';
    $status_text_detail = 'Belum Lunas'; // Default
    if (isset($row['status'])) {
        $status_text_detail = $row['status'];
        switch ($row['status']) {
            case 'Lunas':
                $status_class_detail = 'bg-success';
                break;
            case 'Belum Lunas':
                $status_class_detail = 'bg-warning text-dark';
                break;
            case 'Belum Bayar':
                $status_class_detail = 'bg-danger';
                break;
        }
    }
?>
<!-- Modal Detail Pemesanan -->
<div class="modal fade" id="detailPemesananModal<?php echo $id; ?>" tabindex="-1" aria-labelledby="detailPemesananModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Detail Pemesanan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-4">
          <div class="col-md-8">
            <h6>Informasi Pemesanan</h6>
            <div class="row mb-2"><div class="col-md-4 fw-medium">ID Transaksi:</div><div class="col-md-8">TRX-<?php echo $id; ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-medium">Tanggal Pendaftaran:</div><div class="col-md-8"><?php echo $row['registration_date']; ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-medium">Durasi Sewa:</div><div class="col-md-8"><?php echo $row['rental_duration']; ?></div></div>
            <div class="row mb-2"><div class="col-md-4 fw-medium">Status:</div>
              <div class="col-md-8"><span class="badge <?= $status_class_detail ?>"><?php echo $status_text_detail ?></span></div>
            </div>
          </div>

          <div class="col-md-4">
            <h6>Pembayaran</h6>
            <div class="row mb-2"><div class="col-md-6 fw-medium">Harga/Bulan:</div><div class="col-md-6">Rp <?php echo number_format($row['price_per_month'], 0, ',', '.'); ?></div></div>
            <div class="row mb-2"><div class="col-md-6 fw-medium">Total:</div><div class="col-md-6">Rp <?php echo number_format($total, 0, ',', '.'); ?></div></div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6">
            <h6>Data Penyewa</h6>
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-light rounded-circle p-3 me-3"><i class="bi bi-person fs-4"></i></div>
                  <div>
                    <h5 class="mb-0"><?php echo $row['name']; ?></h5>
                    <p class="text-muted mb-0">ID: TN-<?php echo $row['tenant_id']; ?></p>
                  </div>
                </div>
                <div class="mb-2"><i class="bi bi-envelope me-2"></i> <?php echo $row['email']; ?></div>
                <div class="mb-2"><i class="bi bi-telephone me-2"></i> <?php echo $row['phone_number']; ?></div>
                <div><i class="bi bi-geo-alt me-2"></i> <?php echo $row['address']; ?></div>
              </div>
            </div>
          </div>

          <div class="col-md-6">
            <h6>Detail Kamar</h6>
            <div class="card">
              <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                  <div class="bg-light rounded-circle p-3 me-3"><i class="bi bi-door-closed fs-4"></i></div>
                  <div>
                    <h5 class="mb-0"><?php echo $row['room_no']; ?></h5>
                    <p class="text-muted mb-0">Tipe: Standar</p>
                  </div>
                </div>
                <div class="mb-2"><i class="bi bi-house me-2"></i> <?php echo $row['property_name']; ?></div>
                <div class="mb-2"><i class="bi bi-geo-alt me-2"></i> <?php echo $row['location']; ?></div>
                <div><i class="bi bi-tag me-2"></i> Rp <?php echo number_format($row['price_per_month'], 0, ',', '.'); ?>/bulan</div>
              </div>
            </div>
          </div>
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-outline-primary">Cetak Bukti</button>
      </div>
    </div>
  </div>
</div>
<?php } ?>


  <!-- Modal Detail Pemesanan
  <div class="modal fade" id="detailPemesananModal" tabindex="-1" aria-labelledby="detailPemesananModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="detailPemesananModalLabel">Detail Pemesanan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-4">
            <div class="col-md-8">
              <h6>Informasi Pemesanan</h6>
              <div class="row mb-2">
                <div class="col-md-4 fw-medium">ID Transaksi:</div>
                <div class="col-md-8">TRX-001</div>
              </div>
              <div class="row mb-2">
                <div class="col-md-4 fw-medium">Tanggal Pendaftaran:</div>
                <div class="col-md-8">10 Juni 2025</div>
              </div>
              <div class="row mb-2">
                <div class="col-md-4 fw-medium">Durasi Sewa:</div>
                <div class="col-md-8">12 Bulan</div>
              </div>
              <div class="row mb-2">
                <div class="col-md-4 fw-medium">Tanggal Berakhir:</div>
                <div class="col-md-8">9 Juni 2026</div>
              </div>
              <div class="row mb-2">
                <div class="col-md-4 fw-medium">Status:</div>
                <div class="col-md-8"><span class="badge bg-success">Lunas</span></div>
              </div>
            </div>
            <div class="col-md-4">
              <h6>Pembayaran</h6>
              <div class="row mb-2">
                <div class="col-md-6 fw-medium">Harga/Bulan:</div>
                <div class="col-md-6">Rp 2.500.000</div>
              </div>
              <div class="row mb-2">
                <div class="col-md-6 fw-medium">Total:</div>
                <div class="col-md-6">Rp 30.000.000</div>
              </div>
              <div class="row mb-2">
                <div class="col-md-6 fw-medium">Jatuh Tempo:</div>
                <div class="col-md-6">10 Juli 2025</div>
              </div>
            </div>
          </div>
          
          <div class="row">
            <div class="col-md-6">
              <h6>Data Penyewa</h6>
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle p-3 me-3">
                      <i class="bi bi-person fs-4"></i>
                    </div>
                    <div>
                      <h5 class="mb-0">Budi Santoso</h5>
                      <p class="text-muted mb-0">ID: TN-001</p>
                    </div>
                  </div>
                  <div class="mb-2">
                    <i class="bi bi-envelope me-2"></i> budi.santoso@email.com
                  </div>
                  <div class="mb-2">
                    <i class="bi bi-telephone me-2"></i> 081234567890
                  </div>
                  <div>
                    <i class="bi bi-geo-alt me-2"></i> Jl. Merdeka No. 123, Jakarta
                  </div>
                </div>
              </div>
            </div>
            
            <div class="col-md-6">
              <h6>Detail Kamar</h6>
              <div class="card">
                <div class="card-body">
                  <div class="d-flex align-items-center mb-3">
                    <div class="bg-light rounded-circle p-3 me-3">
                      <i class="bi bi-door-closed fs-4"></i>
                    </div>
                    <div>
                      <h5 class="mb-0">A-101</h5>
                      <p class="text-muted mb-0">Tipe: Standar</p>
                    </div>
                  </div>
                  <div class="mb-2">
                    <i class="bi bi-house me-2"></i> Apartemen Green Garden
                  </div>
                  <div class="mb-2">
                    <i class="bi bi-geo-alt me-2"></i> Tower A, Lantai 1
                  </div>
                  <div>
                    <i class="bi bi-tag me-2"></i> Rp 2.500.000/bulan
                  </div>
                </div>
              </div>
            </div>
          </div>
          
          </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="button" class="btn btn-outline-primary">Cetak Bukti</button>
        </div>
        </div>

      </div>
    </div>
  </div> -->

  <!-- Modal Perpanjang Sewa -->
  <div class="modal fade" id="perpanjangModal" tabindex="-1" aria-labelledby="perpanjangModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="perpanjangModalLabel">Perpanjang Sewa</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i> Anda akan memperpanjang sewa untuk penyewa <strong>Budi Santoso</strong> di kamar <strong>A-101</strong>.
          </div>
          
          <div class="mb-3">
            <label class="form-label">Durasi Saat Ini</label>
            <input type="text" class="form-control" value="12 Bulan (Berakhir: 9 Juni 2026)" readonly>
          </div>
          
          <div class="mb-3">
            <label for="durasiPerpanjangan" class="form-label">Tambahan Durasi</label>
            <select class="form-select" id="durasiPerpanjangan">
              <option value="1">1 Bulan</option>
              <option value="3">3 Bulan</option>
              <option value="6" selected>6 Bulan</option>
              <option value="12">12 Bulan</option>
            </select>
          </div>
          
          <div class="mb-3">
            <label for="hargaPerpanjangan" class="form-label">Harga per Bulan</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" class="form-control" id="hargaPerpanjangan" value="2,500,000" readonly>
            </div>
          </div>
          
          <div class="mb-4">
            <label for="totalPerpanjangan" class="form-label">Total Pembayaran</label>
            <div class="input-group">
              <span class="input-group-text">Rp</span>
              <input type="text" class="form-control fw-bold" id="totalPerpanjangan" value="15,000,000" readonly>
            </div>
          </div>
          
          <div class="d-grid">
            <button class="btn btn-primary btn-lg">
              <i class="bi bi-calendar-check me-2"></i> Proses Perpanjangan
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>

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

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script>
  // Logout confirmation
  document.getElementById('confirmLogout').addEventListener('click', function() {
    alert('Anda telah logout!');
    window.location.href = 'login.html';
  });
  
  // Update total payment when duration changes
  document.getElementById('durasiSewa').addEventListener('change', function() {
    const pricePerMonth = 2500000;
    const duration = parseInt(this.value);
    const totalPayment = pricePerMonth * duration;
    
    document.getElementById('totalPembayaran').value = 
      totalPayment.toLocaleString('id-ID');
  });
  
  // Initialize date fields to today
  const today = new Date().toISOString().split('T')[0];
  document.getElementById('tanggalPendaftaran').value = today;
  
  // Set due date to 30 days from now
  const dueDate = new Date();
  dueDate.setDate(dueDate.getDate() + 30);
  document.getElementById('tanggalJatuhTempo').value = dueDate.toISOString().split('T')[0];
  
  // Perpanjangan sewa calculations
  const durasiPerpanjangan = document.getElementById('durasiPerpanjangan');
  const hargaPerpanjangan = document.getElementById('hargaPerpanjangan');
  const totalPerpanjangan = document.getElementById('totalPerpanjangan');
  
  function updatePerpanjangan() {
    const durasi = parseInt(durasiPerpanjangan.value);
    const harga = 2500000; // Harga per bulan
    const total = durasi * harga;
    
    totalPerpanjangan.value = total.toLocaleString('id-ID');
  }
  
  durasiPerpanjangan.addEventListener('change', updatePerpanjangan);
  updatePerpanjangan(); // Initialize
  
  // Highlight active row
  const rows = document.querySelectorAll('tbody tr');
  rows.forEach(row => {
    row.addEventListener('click', function() {
      rows.forEach(r => r.classList.remove('highlight-row'));
      this.classList.add('highlight-row');
    });
  });
  
  // Search functionality
  const searchInput = document.getElementById('searchInput');
  searchInput.addEventListener('keyup', function() {
    const filter = this.value.toLowerCase();
    const table = document.querySelector('table');
    const rows = table.querySelectorAll('tbody tr');
    
    rows.forEach(row => {
      const text = row.textContent.toLowerCase();
      row.style.display = text.includes(filter) ? '' : 'none';
    });
  });
</script> -->
  

<script>
document.addEventListener('DOMContentLoaded', function() {
  const selectKamar = document.getElementById('kamar');
  const hargaPerBulan = document.getElementById('hargaPerBulan');
  const durasiSewa = document.getElementById('durasiSewa');
  const totalPembayaran = document.getElementById('totalPembayaran');

  function updateHargaDanTotal() {
    const selected = selectKamar.options[selectKamar.selectedIndex];
    const harga = selected ? parseInt(selected.getAttribute('data-harga')) : 0;
    hargaPerBulan.value = harga.toLocaleString('id-ID');
    // Hitung total pembayaran
    const durasi = parseInt(durasiSewa.value) || 1;
    totalPembayaran.value = (harga * durasi).toLocaleString('id-ID');
  }

  selectKamar.addEventListener('change', updateHargaDanTotal);
  durasiSewa.addEventListener('change', updateHargaDanTotal);

  // Set harga awal saat modal dibuka
  updateHargaDanTotal();
  });

  
</script>


</body>
</html>