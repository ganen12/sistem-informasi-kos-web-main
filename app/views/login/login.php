<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Masuk atau Daftar - Hunian.id</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Montserrat', sans-serif;
      background-color: #f8f9fa;
    }
    .form-container {
      max-width: 500px;
      margin: 4rem auto;
    }
  </style>
</head>
<body>

  <div class="container form-container">
    <ul class="nav nav-tabs mb-3" id="authTabs" role="tablist">
      <li class="nav-item" role="presentation">
        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Masuk</button>
      </li>
      <li class="nav-item" role="presentation">
        <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Daftar</button>
      </li>
    </ul>

    <div class="tab-content" id="authTabsContent">
      <!-- Login Tab -->
      <div class="tab-pane fade show active" id="login" role="tabpanel">
        <?php if (isset($_GET['login_error'])): ?>
          <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_GET['login_error']); ?>
          </div>
        <?php endif; ?>  
        <?php if (isset($_GET['login_message'])): ?>
          <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($_GET['login_message']); ?>
          </div>
        <?php endif; ?>
      <form action="../../controllers/aksi_login.php" method="POST">
          <div class="mb-3">
            <label for="loginEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="loginEmail" name="loginEmail" placeholder="email@domain.com" required>
            <span class="error">
                <?php if (isset($errors['loginEmail'])) echo $errors['loginEmail']; ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="loginPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="loginPassword" name="loginPassword" required>
            <span class="error">
                <?php if (isset($errors['loginPassword'])) echo $errors['loginPassword']; ?>
            </span>
          </div>
          <div class="mb-4">
            <label for="role" class="form-label">Masuk Sebagai</label>
            <select class="form-select" id="role" name="role" required>
              <option value="pemilik">Pemilik Properti</option>
              <option value="pembeli">Pembeli / Pencari</option>
            </select>
            <span class="error">
                <?php if (isset($errors['role'])) echo $errors['role']; ?>
            </span>
          </div>
          <div class="mb-3 text-end">
            <a href="" class="text-decoration-none">Lupa password?</a>
          </div>
          <button type="submit" class="btn btn-warning w-100">Masuk</button>
        </form>
      </div>

      <!-- Register Tab -->
      <div class="tab-pane fade" id="register" role="tabpanel">
        <?php if (isset($_GET['register_error'])): ?>
          <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($_GET['register_error']); ?>
          </div>
        <?php endif; ?>

        <?php if (isset($_GET['register_message'])): ?>
            <div class="alert alert-success" role="alert">
                <?php echo htmlspecialchars($_GET['register_message']); ?>
            </div>
        <?php endif; ?>

        <form action="../../controllers/aksi_register.php" method="POST">
          <div class="mb-3">
            <label class="form-label">Daftar Sebagai</label>
            <select class="form-select" id="registerRole" name="registerRole" required>
              <option value="pembeli">Pembeli / Pencari Kost</option>
              <option value="pemilik">Pemilik Properti</option>
            </select>
            <span class="error">
                <?php if (isset($errors['role'])) echo $errors['role']; ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="registerNama" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="registerNama" name="registerNama" required>
            <span class="error">
                <?php if (isset($errors['registerNama'])) echo $errors['registerNama']; ?>
            </span>
          </div>
          <div class="mb-3">
            <label for="registerEmail" class="form-label">Email</label>
            <input type="email" class="form-control" id="registerEmail" name="registerEmail" required>
          </div>
          <div class="mb-3">
            <label for="registerPassword" class="form-label">Password</label>
            <input type="password" class="form-control" id="registerPassword" name="registerPassword" required>
          </div>
          <div class="mb-3 d-none" id="phoneField">
            <label for="registerPhone" class="form-label">Nomor HP</label>
            <input type="text" class="form-control" id="registerPhone" name="registerPhone">
          </div>
          <button type="submit" class="btn btn-success w-100">Daftar</button>
        </form>
      </div>
    </div>
  </div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
<!-- <script src="js/login.js"></script> -->

<script>
const registerRole = document.getElementById('registerRole');
const phoneField = document.getElementById('phoneField');
const registerPhone = document.getElementById('registerPhone');

registerRole.addEventListener('change', () => {
  if (registerRole.value === 'pemilik') {
    phoneField.classList.remove('d-none');
    registerPhone.required = true;
  } else {
    phoneField.classList.add('d-none');
    registerPhone.required = false;
    registerPhone.value = '';
  }
});
</script>

</body>
</html>
