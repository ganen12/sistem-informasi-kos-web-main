<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reset Password - Hunian.id</title>
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
    <h3 class="text-center mb-4">Reset Password</h3>
    <form onsubmit="return handleReset()">
      <div class="mb-3">
        <label for="resetEmail" class="form-label">Masukkan Email Anda</label>
        <input type="email" class="form-control" id="resetEmail" required>
      </div>
      <button type="submit" class="btn btn-warning w-100">Kirim Link Reset</button>
      <div class="mt-3 text-center">
        <a href="login.html" class="text-decoration-none">Kembali ke Login</a>
      </div>
    </form>
  </div>

  <script>
    function handleReset() {
      const email = document.getElementById('resetEmail').value;
      if (!email) {
        alert("Mohon masukkan email anda.");
        return false;
      }
      alert("Link reset telah dikirim ke " + email);
      return false;
    }
  </script>

<script>
    function handleResetPassword() {
      const email = document.getElementById("resetEmail").value;
      const emailPattern = /^[^@]+@[^@]+\.[a-z]{2,}$/i;

      if (!email || !emailPattern.test(email)) {
        alert("Masukkan email yang valid.");
        return false;
      }

      alert("Link reset password telah dikirim ke " + email);
      return false;
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
