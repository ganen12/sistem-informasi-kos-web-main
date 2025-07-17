document.addEventListener('DOMContentLoaded', function () {
const trxSelect = document.getElementById('room_transaction_select');
const namaField = document.getElementById('nama_penyewa');
const totalField = document.querySelector('input[name="payment_total"]');

trxSelect?.addEventListener('change', function () {
    const selected = this.options[this.selectedIndex];
    namaField.value = selected.getAttribute('data-name') || '';
    totalField.value = selected.getAttribute('data-total') || '';
});
});

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-edit-pembayaran').forEach(button => {
      button.addEventListener('click', function () {
        document.getElementById('edit_payment_id').value = this.dataset.id;
        document.getElementById('edit_payment_date').value = this.dataset.date;
        document.getElementById('edit_payment_total').value = this.dataset.total;
        document.getElementById('edit_amount_paid').value = this.dataset.paid;
        document.getElementById('edit_payment_due_date').value = this.dataset.due;
        document.getElementById('edit_status').value = this.dataset.status;
      });
    });
  });

document.querySelectorAll('.btn-edit-pembayaran').forEach(btn => {
btn.addEventListener('click', function () {
document.getElementById('edit_payment_id').value = this.dataset.id;
document.getElementById('edit_payment_date').value = this.dataset.date;
document.getElementById('edit_nama_penyewa').value = this.dataset.name;
document.getElementById('edit_payment_total').value = this.dataset.total;
document.getElementById('edit_amount_paid').value = this.dataset.paid;
document.getElementById('edit_payment_due_date').value = this.dataset.due;
document.getElementById('edit_status').value = this.dataset.status;
});
});
