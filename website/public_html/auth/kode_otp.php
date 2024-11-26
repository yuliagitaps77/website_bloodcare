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
    <title>kode otp</title>
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
              <div style="display: flex; align-items: center;margin-bottom: 80px;">
                <img src="../assets/imgs/logo apk.png" style="width: 20%; max-width: 10%; margin-bottom: 0px;" alt="icon">
                <strong style="margin-left: 10px; font-size: 24px; font-weight: 900; color: #333; line-height: 1;">BloodCare</strong>
              </div>
            
              <h2 class="form-box__title" style="margin-bottom: 40px;">Masukkan kode OTP</h2> 
              <p class="form-box__subtitle"style="margin-bottom: 40px;">Masukan kode OTP yang dikirimkan ke emailmu</p>
                

                  
              <form action="/website_bloodcare/api/website/verifikasi_kode_otp_website.php" method="POST">
              <!-- Email ditampilkan otomatis -->

        <div class="input-group" style="margin-bottom: 40px;">
    <input type="text" id="otp" name="otp" class="form-box__input" required placeholder=" " maxlength="5" oninput="validateOTP(event)">
    <label for="otp" class="form-box__label">OTP</label>
</div>

<script>
    function validateOTP(event) {
        const input = event.target;
        // Hanya izinkan angka
        input.value = input.value.replace(/[^0-9]/g, '');
    }
</script>

        <button type="submit" class="btn">KONFIRMASI</button>
    </form>
               
            </div>
        </div>
    </div>
</body>
</html>
