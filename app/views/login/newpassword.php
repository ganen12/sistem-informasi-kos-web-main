<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Buat Password Baru - Hunian.id</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 450px;
      margin: 5rem auto;
    }
  </style>
</head>
<body>

  <div class="container form-container">
    <h3 class="text-center mb-4">Buat Password Baru</h3>
    <form onsubmit="return validatePassword()">
      <div class="mb-3">
        <label for="newPassword" class="form-label">Password Baru</label>
        <input type="password" class="form-control" id="newPassword" required>
      </div>
      <div class="mb-3">
        <label for="confirmPassword" class="form-label">Konfirmasi Password</label>
        <input type="password" class="form-control" id="confirmPassword" required>
      </div>
      <button type="submit" class="btn btn-success w-100">Simpan Password</button>
    </form>
    <div class="text-center mt-3">
      <a href="login.html" class="text-decoration-none">Kembali ke Login</a>
    </div>
  </div>

  <script>
    function validatePassword() {
      const pass = document.getElementById('newPassword').value;
      const confirm = document.getElementById('confirmPassword').value;
      if (pass.length < 6) {
        alert("Password minimal 6 karakter.");
        return false;
      }
      if (pass !== confirm) {
        alert("Konfirmasi password tidak cocok.");
        return false;
      }
      alert("Password berhasil diubah.");
      return false;
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
