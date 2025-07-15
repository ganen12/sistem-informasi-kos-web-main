function validateLogin() {
    const email = document.getElementById('loginEmail').value;
    const password = document.getElementById('loginPassword').value;
    const role = document.getElementById('role').value;

    if (!email || !password) {
      alert("Mohon lengkapi semua data masuk.");
      return false;
    }

    if (email === 'admin@gmail.com' && password === 'admin123') {
      localStorage.setItem('role', role);
      if (role === 'pemilik') {
        window.location.href = 'dashboardpemilik.html';
      } else {
        window.location.href = 'dashboardutama.html';
      }
    } 
    else if (email === 'user@gmail.com' && password === 'user123') {
        localStorage.setItem('role', role);
        if (role === 'pembeli') {
          window.location.href = 'dashboardpembeli.html';
        } else {
          window.location.href = 'dashboardutama.html';
        }
      }
    else {
      alert('Email atau password salah!');
    }
    return false;
  }

function validateRegister() {
    const nama = document.getElementById('registerNama').value;
    const email = document.getElementById('registerEmail').value;
    const password = document.getElementById('registerPassword').value;
    const role = document.getElementById('registerRole').value;
    const emailPattern = /^[^@]+@[^@]+\.[a-z]{2,}$/i;

    if (!nama || !email || !password || !role) {
    alert("Mohon lengkapi semua data pendaftaran.");
    return false;
    }

    if (!emailPattern.test(email)) {
    alert("Format email tidak valid.");
    return false;
    }

    if (password.length < 6) {
    alert("Password minimal 6 karakter.");
    return false;
    }

    alert("Registrasi berhasil! Silakan login.");
    document.getElementById('login-tab').click();
    return false;
}