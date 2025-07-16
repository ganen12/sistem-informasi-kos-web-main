// Modal Edit Jual
document.getElementById('modalEditJual').addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  if (!button) return;

  document.getElementById('editSellingId').value = button.getAttribute('data-id') ?? '';
  document.getElementById('editNama').value = button.getAttribute('data-nama') ?? '';
  document.getElementById('editHargaJual').value = button.getAttribute('data-harga-jual') ?? '';
  document.getElementById('editHargaBulan').value = button.getAttribute('data-harga-bulan') ?? '';
  document.getElementById('editLokasi').value = button.getAttribute('data-lokasi');
  document.getElementById('editKamar').value = button.getAttribute('data-kamar') ?? '';
  document.getElementById('editMandi').value = button.getAttribute('data-mandi') ?? '';
  document.getElementById('editLuasTanah').value = button.getAttribute('data-luastanah') ?? '';
  document.getElementById('editLuasBangunan').value = button.getAttribute('data-luasbangunan') ?? '';
  document.getElementById('editSertifikat').value = button.getAttribute('data-sertifikat') ?? '';
  document.getElementById('editListrik').value = button.getAttribute('data-listrik') ?? '';
  document.getElementById('editLantai').value = button.getAttribute('data-lantai') ?? '';
  document.getElementById('editGarasi').value = button.getAttribute('data-garasi') ?? '';
  document.getElementById('editKondisi').value = button.getAttribute('data-kondisi') ?? '';
  document.getElementById('editDeskripsi').value = button.getAttribute('data-deskripsi') ?? '';
  document.getElementById('editFasilitas').value = button.getAttribute('data-fasilitas') ?? '';

});

// Modal Edit Sewa
document.getElementById('editSewaModal').addEventListener('show.bs.modal', event => {
  const button = event.relatedTarget;
  if (!button) return;

  document.getElementById('editSewaId').value = button.getAttribute('data-id');
  document.getElementById('editSewaName').value = button.getAttribute('data-name');
  document.getElementById('editSewaLokasi').value = button.getAttribute('data-lokasi');
  document.getElementById('editSewaType').value = button.getAttribute('data-type');
  document.getElementById('editSewaDuration').value = button.getAttribute('data-duration');
  document.getElementById('editSewaPrice').value = button.getAttribute('data-price');
  document.getElementById('editSewaFacilities').value = button.getAttribute('data-facilities');
});

// Untuk Modal Hapus
function modalHapusProperti(id, kategori) {
  document.getElementById('hapusId').value = id;
  document.getElementById('hapusKategori').value = kategori;

  let form = document.getElementById('formHapusProperti');
  if (kategori === 'jual') {
    form.action = '../../controllers/properti/aksi_hapus_jual.php';
  } else if (kategori === 'sewa') {
    form.action = '../../controllers/properti/aksi_hapus_sewa.php';
  }

  new bootstrap.Modal(document.getElementById('modalHapusProperti')).show();
}

