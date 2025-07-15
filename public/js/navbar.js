document.getElementById('confirmLogout').addEventListener('click', () => {
    localStorage.removeItem('role'); // Hapus session
    window.location.href = 'login.html'; // Redirect ke halaman login
  });