<?php
session_start();

// Periksa apakah email tersedia di session
if (!isset($_SESSION['email'])) {
    echo "<script>
        alert('Email tidak ditemukan. Silakan coba lagi.');
        window.location.href = 'lupa_password.php';
    </script>";
    exit();
}

$email = $_SESSION['email']; // Ambil email dari session
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandi Baru</title>
    <!-- Link Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Link CSS -->
    <link rel="stylesheet" href="./style.css">
</head>
<body>
    <div class="container">
        <!-- Bagian Gambar -->
        <div class="image-container">
            <img src="images/forgot password.png" alt="Ilustrasi Login" class="image">

        </div>
        
        <!-- Bagian Form -->
        <div class="form-container">
            <div class="form-box">
              <div style="display: flex; align-items: center;">
                <img src="../assets/imgs/logo apk.png" style="width: 20%; max-width: 10%; margin-bottom: 40px;" alt="icon">
                <strong style="margin-left: 10px; font-size: 24px; font-weight: 900; color: #333; line-height: 1; margin-bottom: 40px;">BloodCare</strong>
              </div>

              <h2 class="form-box__title" >Masukkan sandi baru</h2>
              <p class="form-box__subtitle">Masukkan sandi baru anda</p>
              <form method="POST" action="http://localhost/website_bloodcare/api/website/reset_password.php">
                
    <div class="input-group">
        <input type="password" id="password" name="password" class="form-box__input" required placeholder=" ">
        <label for="password" class="form-box__label">SANDI BARU</label>
    </div>
    <div class="input-group">
        <input type="password" id="confirm_password" name="confirm_password" class="form-box__input" required placeholder=" ">
        <label for="confirm_password" class="form-box__label">KONFIRMASI SANDI</label>
    </div>
    <button type="submit" class="btn">GANTI SANDI</button>
</form>
            </div>
        </div>
    </div>
</body>
</html>
