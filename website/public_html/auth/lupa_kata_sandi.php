<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'] ?? null;

    if (empty($email)) {
        echo "<script>
            alert('Email wajib diisi!');
            window.history.back();
        </script>";
        exit();
    }

    // Simpan email ke session
    $_SESSION['email'] = $email;

    // Redirect ke halaman OTP
    header("Location: verifikasi_otp_website.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Kata Sandi</title>
    <!-- Link Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Link CSS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
            
              <h2 class="form-box__title" style="margin-bottom: 40px;">Lupa kata sandi</h2> 
              <p class="form-box__subtitle"style="margin-bottom: 40px;">Masukan alamat email untuk mendapatkan kode OTP</p>
              <form action="http://localhost/website_bloodcare/api/website/send_otp_website.php" method="POST">
                <div class="input-group" style="margin-bottom: 40px;">
                    <input type="email" id="email" name="email" class="form-box__input" required placeholder=" ">
                    <label for="email" class="form-box__label">EMAIL</label>
                </div>
                <button type="submit" class="btn">KIRIM</button>
            </form>
              
                
            </div>
        </div>
    </div>
</body>
</html>
