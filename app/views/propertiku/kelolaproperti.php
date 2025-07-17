<?php
require_once "../../helpers/auth.php";
require_login();
?>

<?php

include "../../../config/database.php";

$user_id = $_SESSION['user_id'] ?? 0;

// Ambil properti jual
$queryJual = "SELECT * FROM selling_properties WHERE user_id = '$user_id' ORDER BY created_at DESC";
$resultJual = mysqli_query($link, $queryJual);

// Ambil properti sewa
$querySewa = "SELECT * FROM rental_properties WHERE user_id = '$user_id' ORDER BY created_at DESC";
$resultSewa = mysqli_query($link, $querySewa);
?>

<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Hunian.id - Kelola Properti</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600;700&display=swap" rel="stylesheet">
  <style>
    /* TODO: sidebar berada di atas navbar atau sebaliknya?  */
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
    .card-img-top {
        height: 280px;         /* tinggi gambar tetap */
        object-fit: cover;     /* gambar akan crop agar proporsional */
    }
    .card-body {
        min-height: 180px;
        max-height: 220px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .badge-jual {
        background-color: #ffc107;
        color: #212529;
        font-size: 0.85rem;
        padding: 0.4em 0.8em;
        border-radius: 0.5em;
    }
    .badge-sewa {
    background-color: #0d6efd;
    color: #fff;
    font-size: 0.85rem;
    padding: 0.4em 0.8em;
    border-radius: 0.5em;
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
      <?php $activeMenu = 'properti'; ?> 
      <?php include __DIR__ . '/../partials/sidebar_propertiku.php'; ?>

      <!-- Main Content -->
      <main class="col-md-10 ms-sm-auto col-lg-10 p-4">
        <div class="card p-4">
          <h5 class="mb-4">Kelola Properti</h5>
          <div class="d-flex gap-3">
            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#formPropertiModal">+ Tambah Properti</button>
          </div>

          <div class="mt-4 d-none" id="detailProperti">
            <h6>Informasi Properti</h6>
            <table class="table table-bordered">
              <tbody id="detailIsi"></tbody>
            </table>
          </div>
        </div>


          <!-- Properti Cards -->
          <section class="mt-4">
            <div class="row" id="daftarPropertiKartu">
              <?php
              // Properti Jual
              while ($prop = mysqli_fetch_assoc($resultJual)) {
                  $imgPath = !empty($prop['image']) ? "../../uploads/jual/" . $prop['image'] : "https://via.placeholder.com/400x250";
                  echo '<div class="col-md-4">
                  <div class="card mb-4 shadow-sm">
                      <img src="' . htmlspecialchars($imgPath) . '" class="card-img-top" alt="Properti">
                      <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                          <h6 class="text-warning mb-0"><strong>Rp ' . number_format($prop['sale_price'], 0, ',', '.') . '</strong></h6>
                          <span class="badge badge-jual">Jual</span>
                      </div>';
                  if (!empty($prop['price_per_month'])) {
                      echo '<div class="text-muted small mb-1">Rp ' . number_format($prop['price_per_month'], 0, ',', '.') . '/bulan</div>';
                  }
                  echo '<h5 class="card-title mb-1">' . htmlspecialchars($prop['property_name']) . '</h5>
                      <p class="card-text small mb-1">' . htmlspecialchars(substr($prop['description'], 0, 60)) . '...</p>
                      <p class="card-text small mb-1">📍 ' . htmlspecialchars($prop['location']) . '</p>
                      <p class="card-text small mb-2">
                          🛏️ ' . ($prop['bedrooms'] ?? 0) . ' &nbsp;
                          🚿 ' . ($prop['bathrooms'] ?? 0) . ' &nbsp;
                          🚗 ' . ($prop['garage'] ?? 0) . '
                      </p>
                      <div class="d-flex justify-content-between">
                        <div class="d-flex gap-2 mb-1">
                          <button 
                            class="btn btn-outline-secondary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#modalEditJual"
                            data-id="' . $prop["selling_property_id"] . '"
                            data-nama="' . htmlspecialchars($prop["property_name"]) . '"
                            data-harga-jual="' . $prop["sale_price"] . '"
                            data-harga-bulan="' . ($prop["price_per_month"] ?? 0) . '"
                            data-lokasi="' . htmlspecialchars($prop["location"]) . '"
                            data-kamar="' . $prop["bedrooms"] . '"
                            data-mandi="' . $prop["bathrooms"] . '"
                            data-luastanah="' . $prop["land_area_size"] . '"
                            data-luasbangunan="' . $prop["building_area_size"] . '"
                            data-sertifikat="' . htmlspecialchars($prop["certificate_type"]) . '"
                            data-listrik="' . $prop["electricity_power"] . '"
                            data-lantai="' . $prop["floors"] . '"
                            data-garasi="' . $prop["garage"] . '"
                            data-kondisi="' . htmlspecialchars($prop["property_condition"]) . '"
                            data-deskripsi="' . htmlspecialchars($prop["description"]) . '"
                            data-fasilitas="' . htmlspecialchars($prop["facilities"]) . '"
                          >
                            <i class="bi bi-pencil"></i> Edit
                          </button>

                          <button 
                            type="button" 
                            class="btn btn-outline-danger btn-sm" 
                            onclick="modalHapusProperti(\'' . $prop["selling_property_id"] . '\', \'jual\')"
                          >
                            <i class="bi bi-trash"></i> Hapus
                          </button>
                        </div>
                        <div class="d-flex gap-2">
                          <a href="../detail_properti.php?kategori=jual&id=' . urlencode($prop["selling_property_id"]) . '" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Lihat Detail
                          </a>
                          <a href="https://wa.me/628777xxxxxxx" target="_blank" class="btn btn-success btn-sm">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  </div>';
              }

              // Properti Sewa
              while ($prop = mysqli_fetch_assoc($resultSewa)) {
                  $imgPath = !empty($prop['image']) ? "../../uploads/sewa/" . $prop['image'] : "https://via.placeholder.com/400x250";
                  echo '<div class="col-md-4">
                  <div class="card mb-4 shadow-sm">
                      <img src="' . htmlspecialchars($imgPath) . '" class="card-img-top" alt="Properti">
                      <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-2">
                          <h6 class="text-warning mb-0"><strong>Rp ' . number_format($prop['rental_price'], 0, ',', '.') . '</strong></h6>
                          <span class="badge badge-sewa">Sewa</span>
                      </div>
                      <h5 class="card-title mb-1">' . htmlspecialchars($prop['property_name']) . '</h5>
                      <p class="card-text small mb-1">' . htmlspecialchars(substr($prop['facilities'], 0, 60)) . '...</p>
                      <p class="card-text small mb-2">
                          🛏️ ' . ($prop['bedrooms'] ?? 0) . ' &nbsp;
                          🚿 ' . ($prop['bathrooms'] ?? 0) . ' &nbsp;
                          🚗 ' . ($prop['garage'] ?? 0) . '
                      </p>
                      <div class="d-flex justify-content-between">
                        <div class="d-flex gap-2 mb-1">
                          <button 
                            class="btn btn-outline-secondary btn-sm" 
                            data-bs-toggle="modal" 
                            data-bs-target="#editSewaModal"
                            data-id="' . $prop["rental_property_id"] . '"
                            data-name="' . htmlspecialchars($prop["property_name"]) . '"
                            data-lokasi="' . htmlspecialchars($prop["location"]) . '"
                            data-type="' . htmlspecialchars($prop["property_type"]) . '"
                            data-duration="' . htmlspecialchars($prop["rental_duration"]) . '"
                            data-price="' . $prop["rental_price"] . '"
                            data-facilities="' . htmlspecialchars($prop["facilities"]) . '"
                          >
                            <i class="bi bi-pencil"></i> Edit
                          </button>
                          <button 
                            type="button" 
                            class="btn btn-outline-danger btn-sm" 
                            onclick="modalHapusProperti(\'' . $prop["rental_property_id"] . '\', \'sewa\')"
                          >
                            <i class="bi bi-trash"></i> Hapus
                          </button>
                        </div>
                        <div class="d-flex gap-2">
                          <a href="../detail_properti_sewa.php?kategori=sewa&id=' . urlencode($prop["rental_property_id"]) . '" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i> Lihat Detail
                          </a>
                          <a href="https://wa.me/628777xxxxxxx" target="_blank" class="btn btn-success btn-sm">
                            <i class="bi bi-whatsapp"></i> WhatsApp
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                  </div>';
              }
              ?>
            </div>
          </section>

        <!-- Halaman Detail -->
        <div id="halamanProperti" class="d-none container mt-5">
          ...
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Form Tambah Properti -->
  <div class="modal fade" id="formPropertiModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Form Tambah Properti</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <!-- Tabs -->
          <ul class="nav nav-tabs mb-3" id="propertiTabs" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="jual-tab" data-bs-toggle="tab" data-bs-target="#jual" type="button" role="tab">Jual</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="sewa-tab" data-bs-toggle="tab" data-bs-target="#sewa" type="button" role="tab">Sewa/Kontrakan</button>
            </li>
          </ul>

          <div class="tab-content">
            <!-- Form Jual -->
            <div class="tab-pane fade show active" id="jual" role="tabpanel">
              <form action="../../controllers/properti/aksi_tambah_jual.php" method="POST" enctype="multipart/form-data" class="needs-validation">
                <div class="mb-3">
                  <label class="form-label">Nama Properti</label>
                  <input type="text" name="property_name" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga Jual</label>
                  <input type="number" name="sale_price" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga per Bulan (Opsional)</label>
                  <input type="number" name="price_per_month" class="form-control">
                </div>
                <div class="mb-3">
                  <label class="form-label">Gambar Properti</label>
                  <input type="file" name="image" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Lokasi Properti</label>
                  <input type="text" name="location" class="form-control" required>
                </div>
                <div class="row g-2">
                  <div class="col">
                    <label class="form-label">Kamar Tidur</label>
                    <input type="number" name="bedrooms" class="form-control" required>
                  </div>
                  <div class="col">
                    <label class="form-label">Kamar Mandi</label>
                    <input type="number" name="bathrooms" class="form-control" required>
                  </div>
                </div>
                <div class="row g-2 mt-2">
                  <div class="col">
                    <label class="form-label">Luas Tanah (m²)</label>
                    <input type="number" name="land_area_size" class="form-control" required>
                  </div>
                  <div class="col">
                    <label class="form-label">Luas Bangunan (m²)</label>
                    <input type="number" name="building_area_size" class="form-control" required>
                  </div>
                </div>
                <div class="mb-3 mt-2">
                  <label class="form-label">Tipe Sertifikat</label>
                  <input type="text" name="certificate_type" class="form-control" required>
                </div>
                <div class="row g-2">
                  <div class="col">
                    <label class="form-label">Daya Listrik</label>
                    <input type="number" name="electricity_power" class="form-control" required>>
                  </div>
                  <div class="col">
                    <label class="form-label">Jumlah Lantai</label>
                    <input type="number" name="floors" class="form-control" required>
                  </div>
                  <div class="col">
                    <label class="form-label">Garasi</label>
                    <input type="number" name="garage" class="form-control" required>
                  </div>
                </div>
                <div class="mb-3 mt-2">
                  <label class="form-label">Kondisi Properti</label>
                  <input type="text" name="property_condition" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Deskripsi</label>
                  <textarea name="description" class="form-control" ></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Fasilitas</label>
                  <textarea name="facilities" class="form-control" required></textarea>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>
            </div>

            <!-- Form Sewa/Kontrakan -->
            <div class="tab-pane fade" id="sewa" role="tabpanel">
              <form action="../../controllers/properti/aksi_tambah_sewa.php" method="POST" enctype="multipart/form-data" class="needs-validation">
                <div class="mb-3">
                  <label class="form-label">Nama Properti</label>
                  <input type="text" name="property_name" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Lokasi Properti</label>
                  <input type="text" name="location" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Jenis Properti</label>
                  <select name="property_type" class="form-select" required>
                    <option value="">Pilih</option>
                    <option value="Sewa">Sewa</option>
                    <option value="Kontrakan">Kontrakan</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Durasi Sewa</label>
                  <select name="rental_duration" class="form-select" required>
                    <option value="">Pilih</option>
                    <option value="Bulanan">Bulanan</option>
                    <option value="Tahunan">Tahunan</option>
                  </select>
                </div>
                <div class="mb-3">
                  <label class="form-label">Harga Sewa</label>
                  <input type="number" name="rental_price" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Fasilitas</label>
                  <textarea name="facilities" class="form-control"></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Gambar Properti</label>
                  <input type="file" name="image" class="form-control" required>
                </div>
                <div class="text-end">
                  <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  
  <!-- Modal Edit Properti Jual -->
  <div class="modal fade" id="modalEditJual" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Properti (Jual)</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <form action="../../controllers/properti/aksi_edit_jual.php" method="POST" enctype="multipart/form-data" id="formEditJual">
            <input type="hidden" name="selling_property_id" id="editSellingId" required>

            <div class="mb-3">
              <label class="form-label">Nama Properti</label>
              <input type="text" name="property_name" id="editNama" class="form-control" required>
            </div>

            <div class="row g-2">
              <div class="col">
                <label class="form-label">Harga Jual</label>
                <input type="number" name="sale_price" id="editHargaJual" class="form-control" required>
              </div>
              <div class="col">
                <label class="form-label">Harga Per Bulan</label>
                <input type="number" name="price_per_month" id="editHargaBulan" class="form-control">
              </div>
            </div>

            <div class="mb-3">
              <label class="form-label">Upload Gambar (opsional)</label>
              <input type="file" name="image" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Lokasi Properti</label>
              <input type="text" name="location" id="editLokasi" class="form-control" required>
            </div>

            <div class="row g-2">
              <div class="col">
                <label class="form-label">Kamar Tidur</label>
                <input type="number" name="bedrooms" id="editKamar" class="form-control">
              </div>
              <div class="col">
                <label class="form-label">Kamar Mandi</label>
                <input type="number" name="bathrooms" id="editMandi" class="form-control">
              </div>
            </div>

            <div class="row g-2 mt-2">
              <div class="col">
                <label class="form-label">Luas Tanah (m²)</label>
                <input type="number" name="land_area_size" id="editLuasTanah" class="form-control">
              </div>
              <div class="col">
                <label class="form-label">Luas Bangunan (m²)</label>
                <input type="number" name="building_area_size" id="editLuasBangunan" class="form-control">
              </div>
            </div>

            <div class="mb-3 mt-2">
              <label class="form-label">Tipe Sertifikat</label>
              <input type="text" name="certificate_type" id="editSertifikat" class="form-control">
            </div>

            <div class="row g-2">
              <div class="col">
                <label class="form-label">Daya Listrik</label>
                <input type="number" name="electricity_power" id="editListrik" class="form-control">
              </div>
              <div class="col">
                <label class="form-label">Jumlah Lantai</label>
                <input type="number" name="floors" id="editLantai" class="form-control">
              </div>
              <div class="col">
                <label class="form-label">Garasi</label>
                <input type="number" name="garage" id="editGarasi" class="form-control">
              </div>
            </div>

            <div class="mb-3 mt-2">
              <label class="form-label">Kondisi Properti</label>
              <input type="text" name="property_condition" id="editKondisi" class="form-control">
            </div>

            <div class="mb-3">
              <label class="form-label">Deskripsi</label>
              <textarea name="description" id="editDeskripsi" class="form-control"></textarea>
            </div>

            <div class="mb-3">
              <label class="form-label">Fasilitas</label>
              <textarea name="facilities" id="editFasilitas" class="form-control"></textarea>
            </div>

            <div class="text-end">
              <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Edit Properti Sewa -->
  <div class="modal fade" id="editSewaModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
      <form action="../../controllers/properti/aksi_edit_sewa.php" method="POST" enctype="multipart/form-data" class="modal-content needs-validation">
        <div class="modal-header">
          <h5 class="modal-title">Edit Properti Sewa/Kontrakan</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="rental_property_id" id="editSewaId" req>
          <div class="mb-3">
            <label class="form-label">Nama Properti</label>
            <input type="text" name="property_name" id="editSewaName" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Lokasi Properti</label>
            <input type="text" name="location" id="editSewaLokasi" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Jenis Properti</label>
            <select name="property_type" id="editSewaType" class="form-select" required>
              <option value="Sewa">Sewa</option>
              <option value="Kontrakan">Kontrakan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Durasi Sewa</label>
            <select name="rental_duration" id="editSewaDuration" class="form-select" required>
              <option value="Bulanan">Bulanan</option>
              <option value="Tahunan">Tahunan</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="form-label">Harga Sewa</label>
            <input type="number" name="rental_price" id="editSewaPrice" class="form-control" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Fasilitas</label>
            <textarea name="facilities" id="editSewaFacilities" class="form-control" required></textarea>
          </div>
          <div class="mb-3">
            <label class="form-label">Ganti Gambar (Opsional)</label>
            <input type="file" name="image" class="form-control" accept="image/*">
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>

<!-- Modal Konfirmasi Hapus Properti -->
  <div class="modal fade" id="modalHapusProperti" tabindex="-1">
    <div class="modal-dialog">
      <form id="formHapusProperti" class="modal-content" method="POST">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title">Konfirmasi Hapus Properti</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <p>Apakah Anda yakin ingin menghapus properti ini?</p>
          <input type="hidden" name="id" id="hapusId">
          <input type="hidden" name="kategori" id="hapusKategori">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-danger">Hapus</button>
        </div>
      </form>
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
<script src="../../../public/js/kelolaproperti.js"></script>
  
</body>
</html>
