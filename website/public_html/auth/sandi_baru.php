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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

    <link rel="stylesheet" href="./style.css">
</head>
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
    /* Target the icon specifically */
    #toggle-password i {
    color: #bebebe; /* Set color of the icon */
    transition: color 0.2s ease; /* Smooth transition for color change */
  }

  /* Optional: Change icon color on hover for better user experience */
  #toggle-password:hover i {
    color: #a8a8a8; /* Slightly darker color on hover */
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
              <div style="display: flex; align-items: center;">
                <img src="../assets/imgs/logo apk.png" style="width: 20%; max-width: 10%; margin-bottom: 40px;" alt="icon">
                <strong style="margin-left: 10px; font-size: 24px; font-weight: 900; color: #333; line-height: 1; margin-bottom: 40px;">BloodCare</strong>
              </div>

              <h2 class="form-box__title" >Masukkan sandi baru</h2>
              <p class="form-box__subtitle">Masukkan sandi baru anda</p>
              <form method="POST" action="/website_bloodcare/api/website/reset_password.php">
                
                <div class="input-group" style="position: relative; width: 100%;">
    <input
                type="password"
                id="password"
                name="password"
                class="form-box__input"
                required
                placeholder=" "
    />
    <label for="password" class="form-box__label">SANDI BARU</label>
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
        <i id="toggle-icon-password" class="fa-regular fa-eye" style="font-size: 20px; color: #bebebe;"></i>
    </button>
    <small
                id="password-error"
                style="
                    color: red;
                    display: none;
                    position: absolute;
                    top: calc(100% + 5px);
                    left: 0;
                "
            >
        Password harus berisi huruf kapital, kecil, angka, dan karakter khusus.
    </small>
</div>

<div class="input-group" style="position: relative; width: 100%;">
    <input
        type="password"
        id="confirm-password"
        name="confirm_password"
        class="form-box__input"
        required
        placeholder=" "
    />
    <label for="confirm-password" class="form-box__label">KONFIRMASI SANDI</label>
    <button
        type="button"
        id="toggle-confirm-password"
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
        <i id="toggle-icon-confirm-password" class="fa-regular fa-eye" style="font-size: 20px; color: #bebebe;"></i>
    </button>
</div>



    <button type="submit" class="btn">GANTI SANDI</button>
</form>
<script>
// Function to validate password criteria
function validatePassword(password) {
    const hasUpperCase = /[A-Z]/.test(password); // Contains uppercase letter
    const hasLowerCase = /[a-z]/.test(password); // Contains lowercase letter
    const hasNumber = /[0-9]/.test(password);    // Contains a number
    const hasSpecialChar = /[!@#$%^&*(),.?":{}|<>]/.test(password); // Contains special character
    return hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar;
}

// Elements for password validation
const passwordInput = document.getElementById('password');
const passwordError = document.getElementById('password-error');
const passwordInputGroup = passwordInput.parentElement; // Reference to input-group

// Validate password on input
passwordInput.addEventListener('input', function () {
    if (validatePassword(passwordInput.value)) {
        passwordError.style.display = 'none'; // Hide error if valid
        passwordInputGroup.style.marginBottom = '0px'; // Reset margin-bottom
    } else {
        passwordError.style.display = 'block'; // Show error if invalid
        passwordInputGroup.style.marginBottom = '40px'; // Add margin-bottom
    }
});

// Toggle password visibility for 'SANDI BARU'
const togglePassword = document.getElementById('toggle-password');
const toggleIconPassword = document.getElementById('toggle-icon-password');

togglePassword.addEventListener('click', function () {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);

    toggleIconPassword.className =
        type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';

    toggleIconPassword.style.color = '#bebebe';
});

// Elements for confirm password
const confirmPasswordInput = document.getElementById('confirm-password');
const toggleConfirmPassword = document.getElementById('toggle-confirm-password');
const toggleIconConfirmPassword = document.getElementById('toggle-icon-confirm-password');

// Toggle password visibility for 'KONFIRMASI SANDI'
toggleConfirmPassword.addEventListener('click', function () {
    const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    confirmPasswordInput.setAttribute('type', type);

    toggleIconConfirmPassword.className =
        type === 'password' ? 'fa-regular fa-eye' : 'fa-regular fa-eye-slash';

    toggleIconConfirmPassword.style.color = '#bebebe';
});


</script>
            </div>
        </div>
    </div>
</body>
</html>
