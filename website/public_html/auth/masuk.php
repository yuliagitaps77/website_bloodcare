<?php
session_start(); // Mulai session

// Jika sudah login, arahkan ke dashboard
if (isset($_SESSION['user_id'])) {
    echo "
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: 'info',
                    title: 'Sudah Login',
                    text: 'Anda sudah login, akan diarahkan ke dashboard.',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = 'http://localhost/website_bloodcare/website/public_html/dashboard/dist/index.php';
                });
            </script>
        </body>
        </html>";
    exit();
}
?>


<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Login</title>
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
            <img src="images/logo login.png" alt="Ilustrasi Login" class="image">

        </div>
        
        <!-- Bagian Form -->
        <div class="form-container">
            <div class="form-box">
              <div style="display: flex; align-items: center;">
                <img src="../assets/imgs/logo apk.png" style="width: 20%; max-width: 10%; margin-bottom: 40px;" alt="icon">
                <strong style="margin-left: 10px; font-size: 24px; font-weight: 900; color: #333; line-height: 1; margin-bottom: 40px;">BloodCare</strong>
              </div>
            
              <p class="form-box__subtitle">Selamat datang kembali👋🏻</p>
                <form action="http://localhost/website_bloodcare/api/website/login_website.php" method="POST">

                <h2 class="form-box__title" >Masuk ke akun Anda.</h2>

                  
                  <div class="input-group">
                    <input type="email" id="email" name="email" class="form-box__input" required placeholder=" ">
                    <label for="email" class="form-box__label">EMAIL</label>
                  </div>
                  
                  <div class="input-group">
                    <input type="password" id="password" name="password" class="form-box__input" required placeholder=" ">
                    <label for="password" class="form-box__label">PASSWORD</label>
                  </div>

                  <a href="lupa_kata_sandi.php" style="text-align: right; color: black; font-weight: 900; margin-bottom: 20px; display: block; text-decoration: none;">Lupa Sandi?</a>

                    <button type="submit" class="btn">MASUK</button>
                </form>

                <a href="daftar.html" style="text-decoration: none;">
                    <p class="form-box__bottom-text" style="color: #616161;">Belum punya akun? <strong>BUAT AKUN</strong></p>
                </a>
                
            </div>
        </div>
    </div>
    
  
  
</body>
</html>
