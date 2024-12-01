<?php
session_start();
require_once dirname(__DIR__, levels: 3) . '/api/koneksi.php';


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
                // Menggunakan BASE_URL untuk mengarahkan ke halaman dashboard
                window.location.href = '" . BASE_URL . "/website/public_html/dashboard/dist/index.php';
            });
        </script>
    </body>
    </html>";
    exit();
}
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet"> <!-- Font Awesome -->


    <link rel="stylesheet" href="./style.css">
</head>
<style>
  .back-button {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            padding: 10px 15px;
            font-size: 18px; /* Ukuran teks */
            color: black; /* Warna teks hitam */
            background-color: transparent; /* Latar belakang transparan */
            border: none;
            cursor: pointer;
            margin-bottom: 20px;
            text-decoration: none; /* Menghilangkan underline pada tautan */
            align-items: center;
        }

        .back-button i {
            margin-right: 8px; /* Memberikan jarak antara ikon dan teks */
        }

        .back-button:hover {
            text-decoration: underline; /* Memberikan efek underline saat hover */
        }

        .back-button:focus {
            outline: none; /* Menghilangkan outline saat tombol difokuskan */
        }
    </style>
    <body>
    <div class="container">
        <!-- Bagian Gambar -->
        <div class="image-container">
            <img src="images/forgot password.png" alt="Ilustrasi Login" class="image">

        </div>
        
        <!-- Bagian Form -->
        <div class="form-container">
            <div class="form-box">
              <div style="display: flex; align-items: center;margin-bottom: 10px;">
                <img src="../assets/imgs/logo apk.png" style="width: 20%; max-width: 10%; margin-bottom: 0px;" alt="icon">
                <strong style="margin-left: 10px; font-size: 24px; font-weight: 900; color: #333; line-height: 1;">BloodCare</strong>
                  
            </div>
            <a href="masuk.php" class="back-button">
    <i class="fas fa-chevron-left"></i> Kembali
</a>

              <!-- Tombol Kembali dengan panah kiri dan teks -->
    <!-- Tombol Kembali dengan ikon panah kiri Font Awesome -->
  <!-- Tombol Kembali dengan ikon fa-chevron-left dan teks -->

              <h2 class="form-box__title" style="margin-bottom: 40px;">Lupa kata sandi</h2> 
              <p class="form-box__subtitle"style="margin-bottom: 40px;">Masukan alamat email untuk mendapatkan kode OTP</p>
              <form action="/website_bloodcare/api/website/send_otp_website.php" method="POST">
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
