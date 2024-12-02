<?php
session_start(); // Mulai session
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
                // Menggunakan BASE_URL unt uk mengarahkan ke halaman dashboard
                window.location.href = '" . BASE_URL . "/website/public_html/dashboard/dist/index.php';
            });
        </script>
    </body>
    </html>";
    exit();
}
?>
                <style>
                    /* Sembunyikan tombol bawaan "show password" di semua browser */
            input[type="password"]::-webkit-reveal-button,
            input[type="password"]::-ms-reveal,
            input[type="password"]::-webkit-clear-button {
            display: none !important; /* Pastikan ini tidak muncul sama sekali */
            visibility: hidden;
}

            /* Menonaktifkan semua ikon bawaan */
            input[type="password"] {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-clip: padding-box;
            }
            #toggle-password i {
                color: #bebebe; /* Set color of the icon */
                transition: color 0.2s ease; /* Smooth transition for color change */
            }

            /* Optional: Change icon color on hover for better user experience */
            #toggle-password:hover i {
                color: #a8a8a8; /* Slightly darker color on hover */
            }
                </style>
            


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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">


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
              <form action="/website_bloodcare/api/website/login_website.php" method="POST">

                <h2 class="form-box__title" >Masuk ke akun Anda.</h2>

                  
                  <div class="input-group">
                    <input type="email" id="email" name="email" class="form-box__input" required placeholder=" ">
                    <label for="email" class="form-box__label">EMAIL</label>
                  </div>
                  <div class="input-group" style="position: relative; width: 100%;">
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-box__input"
                    required
                    placeholder=" "
                    autocomplete="off"
                />
                <label for="password" class="form-box__label">PASSWORD</label>
                <button
                    type="button"
                    id="toggle-password"
                    style="
                    cursor: pointer;
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    border: none;
                    background: transparent;
                    "
                >
                    <i id="toggle-icon" class="fa-regular fa-eye" style="font-size: 20px; color: #bebebe;"></i>
                </button>
                </div>


                <script>
                    const passwordInput = document.getElementById('password');
                    const togglePassword = document.getElementById('toggle-password');
                    const toggleIcon = document.getElementById('toggle-icon');
                
                    togglePassword.addEventListener('click', function () {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    
                    // Change icon based on visibility
                    toggleIcon.className =
                    type === 'password'
                    ? 'fa-regular fa-eye'  // Outline "eye" icon
                    : 'fa-regular fa-eye-slash'; // Outline "eye-slash" icon

                // Ensure the color stays consistent
                toggleIcon.style.color = '#bebebe';   
                    });
                </script>


                  <a href="lupa_kata_sandi.php" style="text-align: right; color: black; font-weight: 900; margin-bottom: 0px    ; display: block; text-decoration: none;">Lupa Sandi?</a>

                    <button type="submit" class="btn">MASUK</button>
                </form>

                <a href="daftar.html" style="text-decoration: none;">
                    <p class="form-box__bottom-text" style="color: #616161; margin-top: 5px">Belum punya akun? <strong>BUAT AKUN</strong></p>
                </a>
                
            </div>
        </div>
    </div>
  
</body>
</html>
