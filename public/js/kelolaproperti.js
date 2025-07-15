// kelolaproperti.js
const propertiList = [];

function openKategoriModal() {
  const modal = new bootstrap.Modal(document.getElementById('kategoriModal'));
  modal.show();
}

function openForm(kategori) {
  const formContainer = document.getElementById('formIsi');
  let formContent = '';

  if (kategori === 'jual') {
    formContent = `
      <input type="hidden" name="kategori" value="jual">
      <div class="mb-3"><label class="form-label">Nama Properti</label><input type="text" class="form-control" name="nama" required></div>
      <div class="mb-3"><label class="form-label">Harga Jual (Rp)</label><input type="number" class="form-control" name="harga" required></div>
      <div class="mb-3"><label class="form-label">Simulasi Cicilan per Bulan (Rp)</label><input type="number" class="form-control" name="harga_per_bulan"></div>
      <div class="mb-3"><label class="form-label">Upload Gambar</label><input type="file" class="form-control" name="gambar" accept="image/*"></div>
      <div class="mb-3"><label class="form-label">Kamar Tidur</label><input type="number" class="form-control" name="kamar_tidur"></div>
      <div class="mb-3"><label class="form-label">Kamar Mandi</label><input type="number" class="form-control" name="kamar_mandi"></div>
      <div class="mb-3"><label class="form-label">Luas Tanah (m²)</label><input type="number" class="form-control" name="luas_tanah"></div>
      <div class="mb-3"><label class="form-label">Luas Bangunan (m²)</label><input type="number" class="form-control" name="luas_bangunan"></div>
      <div class="mb-3"><label class="form-label">Sertifikat</label><input type="text" class="form-control" name="sertifikat"></div>
      <div class="mb-3"><label class="form-label">Daya Listrik (Watt)</label><input type="number" class="form-control" name="daya_listrik"></div>
      <div class="mb-3"><label class="form-label">Jumlah Lantai</label><input type="number" class="form-control" name="jumlah_lantai"></div>
      <div class="mb-3"><label class="form-label">Garasi</label><input type="text" class="form-control" name="garasi"></div>
      <div class="mb-3"><label class="form-label">Kondisi Properti</label><input type="text" class="form-control" name="kondisi"></div>
      <div class="mb-3"><label class="form-label">Deskripsi</label><textarea class="form-control" name="deskripsi" rows="3"></textarea></div>
    `;
  } else if (kategori === 'sewa' || kategori === 'kontrak') {
    const label = kategori === 'sewa' ? 'Nama Properti' : 'Nama Kontrakan';
    const labelHarga = kategori === 'sewa' ? 'Harga per Bulan (Rp)' : 'Harga Kontrak per Tahun (Rp)';

    formContent = `
      <input type="hidden" name="kategori" value="${kategori}">
      <div class="mb-3">
        <label class="form-label">${label}</label>
        <input type="text" name="nama" class="form-control" required>
      </div>
      <div id="durasiHargaContainer">
        <div class="mb-3">
          <label class="form-label">${labelHarga}</label>
          <div class="input-group">
            <span class="input-group-text">Per Bulan</span>
            <input type="number" name="harga_per_bulan" class="form-control" required>
          </div>
        </div>
      </div>
      <div class="mb-3">
        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="tambahDurasiHarga()">+ Tambah Durasi</button>
      </div>
      <div class="mb-3">
        <label class="form-label">Fasilitas</label>
        <textarea name="deskripsi" class="form-control"></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Upload Gambar</label>
        <input type="file" class="form-control" name="gambar" accept="image/*">
      </div>
    `;
  }

  formContainer.innerHTML = formContent;
  new bootstrap.Modal(document.getElementById('formPropertiModal')).show();

  setTimeout(() => {
    const form = document.getElementById('formProperti');
    form.addEventListener('submit', handleFormSubmit, { once: true });
  }, 0);
}

function tambahDurasiHarga() {
  const durasiContainer = document.getElementById('durasiHargaContainer');
  const wrapper = document.createElement('div');
  wrapper.classList.add('mb-3');

  const durasiSelect = document.createElement('select');
  durasiSelect.name = 'durasi[]';
  durasiSelect.className = 'form-select mb-2';
  durasiSelect.innerHTML = `
    <option value="3_bulan">3 Bulan</option>
    <option value="6_bulan">6 Bulan</option>
    <option value="1_tahun">1 Tahun</option>
    <option value="custom">Custom</option>
  `;

  const labelInput = document.createElement('input');
  labelInput.name = 'durasi_custom_label[]';
  labelInput.className = 'form-control mb-2 d-none';
  labelInput.placeholder = 'Tulis Label Durasi (misal: 2 Minggu)';

  const hargaInput = document.createElement('input');
  hargaInput.type = 'number';
  hargaInput.name = 'harga_durasi[]';
  hargaInput.placeholder = 'Harga';
  hargaInput.className = 'form-control';

  durasiSelect.addEventListener('change', () => {
    if (durasiSelect.value === 'custom') {
      labelInput.classList.remove('d-none');
      labelInput.required = true;
    } else {
      labelInput.classList.add('d-none');
      labelInput.required = false;
    }
  });

  wrapper.appendChild(durasiSelect);
  wrapper.appendChild(labelInput);
  wrapper.appendChild(hargaInput);
  durasiContainer.appendChild(wrapper);
}

function handleFormSubmit(e) {
  e.preventDefault();
  const form = e.target;

  if (!form.checkValidity()) {
    form.classList.add('was-validated');
    return;
  }

  const formData = new FormData(form);
  const file = formData.get('gambar');
  const data = Object.fromEntries(formData.entries());

  const reader = new FileReader();

  reader.onload = function (event) {
    data.gambarPreview = event.target.result || '';
    simpanDataProperti(data, form);
  };

  if (file && file.size > 0) {
    reader.readAsDataURL(file);
  } else {
    data.gambarPreview = '';
    simpanDataProperti(data, form);
  }
}

function simpanDataProperti(data, form) {
  propertiList.push(data);
  updatePropertiKartu();
  bootstrap.Modal.getInstance(document.getElementById('formPropertiModal')).hide();
  form.reset();
  form.classList.remove('was-validated');
}

function updatePropertiKartu() {
  const wrapper = document.getElementById('daftarPropertiKartu');
  wrapper.innerHTML = '';
  propertiList.forEach((item, index) => {
    const card = document.createElement('div');
    card.className = 'col-md-4';
    const isJual = item.kategori === 'jual';
    const hargaUtama = isJual ? item.harga : item.harga_per_bulan;
    const teksPerBulan = isJual && item.harga_per_bulan ? `<div class="text-muted small">Rp ${Number(item.harga_per_bulan).toLocaleString('id-ID')}/bulan</div>` : '';
    card.innerHTML = `
      <div class="card mb-4 shadow-sm">
        <img src="${item.gambarPreview || 'https://via.placeholder.com/400x250'}" class="card-img-top" alt="Properti">
        <div class="card-body">
          <h5 class="card-title">${item.nama}</h5>
          <p class="card-text text-muted">${item.kategori}</p>
          <h6 class="text-warning">Rp ${Number(hargaUtama).toLocaleString('id-ID')}</h6>
          ${teksPerBulan}
          <p class="card-text small">${item.deskripsi?.substring(0, 60)}...</p>
          <p class="card-text small">
            🛏️ ${item.kamar_tidur || 0} &nbsp;
            🚿 ${item.kamar_mandi || 0} &nbsp;
            🚗 ${item.garasi || 0}
          </p>
          <div class="d-flex justify-content-between">
            <button class="btn btn-outline-primary btn-sm" onclick="showDetailHalaman(${index})">Lihat Detail</button>
            <a href="https://wa.me/628777xxxxxxx" class="btn btn-success btn-sm"><i class="bi bi-whatsapp"></i></a>
          </div>
        </div>
      </div>
    `;
    wrapper.appendChild(card);
  });
}

