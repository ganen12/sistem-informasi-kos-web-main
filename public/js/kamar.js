// kamar.js

const propertiDummy = ['Kost 1', 'Kost 2', 'Kontrakan A'];
let selectedProperti = '';

function formatRupiah(angka) {
  const numberString = angka.replace(/[^,\d]/g, '').toString();
  const split = numberString.split(',');
  let sisa = split[0].length % 3;
  let rupiah = split[0].substr(0, sisa);
  const ribuan = split[0].substr(sisa).match(/\d{3}/g);
  if (ribuan) {
    const separator = sisa ? '.' : '';
    rupiah += separator + ribuan.join('.');
  }
  return rupiah;
}

function tampilkanToast(pesan, tipe = 'success') {
  const toastElement = document.getElementById('toastKamar');
  const toastMessage = document.getElementById('toastMessage');
  if (toastElement && toastMessage) {
    toastElement.classList.remove('text-bg-success', 'text-bg-danger');
    toastElement.classList.add(`text-bg-${tipe}`);
    toastMessage.textContent = pesan;
    const toast = new bootstrap.Toast(toastElement);
    toast.show();
  }
}

document.addEventListener('DOMContentLoaded', function () {
  const labelProperti = document.getElementById('judulPropertiAktif');
  const selectProperti = document.getElementById('selectProperti');
  const tabelKamar = document.getElementById('tabelKamar');
  const formKamar = document.getElementById('formKamar');

  // Populate dropdown dan default
  propertiDummy.forEach(nama => {
    const opt = document.createElement('option');
    opt.value = nama;
    opt.textContent = nama;
    selectProperti.appendChild(opt);
  });
  selectedProperti = selectProperti.value = propertiDummy[0];
  if (labelProperti) labelProperti.innerText = selectedProperti;

  selectProperti.addEventListener('change', () => {
    selectedProperti = selectProperti.value;
    if (labelProperti) labelProperti.innerText = selectedProperti;
  });

  document.querySelectorAll('.rupiah').forEach(input => {
    input.addEventListener('input', function () {
      const cursorPos = this.selectionStart;
      const formatted = formatRupiah(this.value);
      this.value = formatted;
      this.setSelectionRange(cursorPos, cursorPos);
    });
  });

  let barisSedangDiedit = null;

  if (formKamar && tabelKamar) {
    formKamar.addEventListener('submit', function (e) {
      e.preventDefault();

      const properti = selectProperti.value;
      const nomor = document.getElementById('nomor').value.trim();
      const status = document.getElementById('statusKamar').value;
      const hargaRaw = document.getElementById('hargaPerBulan').value.replace(/\./g, '').trim();
      if (!nomor || !hargaRaw || isNaN(hargaRaw)) return tampilkanToast('Isi semua data dengan benar', 'danger');

      const harga = parseInt(hargaRaw).toLocaleString('id-ID', { style: 'currency', currency: 'IDR' });

      if (barisSedangDiedit) {
        const kolom = barisSedangDiedit.querySelectorAll('td');
        kolom[0].innerText = properti;
        kolom[1].innerText = nomor;
        kolom[2].innerText = status;
        kolom[3].innerText = harga;
        barisSedangDiedit = null;
        tampilkanToast('Data kamar diperbarui!', 'success');
      } else {
        const baris = document.createElement('tr');
        baris.innerHTML = `
          <td>${properti}</td>
          <td>${nomor}</td>
          <td>${status}</td>
          <td>${harga}</td>
          <td>
            <button class="btn btn-sm btn-primary btn-edit">Edit</button>
            <button class="btn btn-sm btn-danger btn-delete">Hapus</button>
          </td>
        `;
        tabelKamar.appendChild(baris);

        baris.querySelector('.btn-delete').addEventListener('click', () => baris.remove());
        baris.querySelector('.btn-edit').addEventListener('click', () => {
          barisSedangDiedit = baris;
          const kolom = baris.querySelectorAll('td');
          selectProperti.value = kolom[0].innerText;
          document.getElementById('nomor').value = kolom[1].innerText;
          document.getElementById('statusKamar').value = kolom[2].innerText;
          document.getElementById('hargaPerBulan').value = kolom[3].innerText.replace(/[^\d]/g, '');
          const modal = new bootstrap.Modal(document.getElementById('tambahKamarModal'));
          modal.show();
        });

        tampilkanToast('Kamar ditambahkan!', 'success');
      }

      formKamar.reset();
      selectProperti.value = selectedProperti;
      const modal = bootstrap.Modal.getInstance(document.getElementById('tambahKamarModal'));
      modal.hide();
    });
  }
});
