        $_SESSION['status'] = 'Login'; // key value simpel: status=login
        $_SESSION['user_id'] = mysqli_fetch_assoc($result)['id']; // ambil id user dari hasil query
        $_SESSION['username'] = $username; // simpan username ke session
        $_SESSION['role'] = $role; // simpan role ke session