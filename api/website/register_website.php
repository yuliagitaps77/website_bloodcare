<?php
// Konfigurasi database
$host = 'localhost';
$user = 'root';
$password = '';
$dbname = 'bloodcarec3';

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Fungsi untuk membersihkan input
function cleanInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Fungsi validasi password
function isValidPassword($password) {
    return preg_match('/[A-Z]/', $password) &&    // Huruf kapital
           preg_match('/[a-z]/', $password) &&    // Huruf kecil
           preg_match('/[0-9]/', $password) &&    // Angka
           preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password); // Karakter khusus
}

// Proses registrasi
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_lengkap = cleanInput($_POST['name']);
    $no_hp = cleanInput($_POST['phone']);
    $email = cleanInput($_POST['email']);
    $username = cleanInput($_POST['username']);
    $password = cleanInput($_POST['password']);

    // Validasi input
    if (empty($nama_lengkap) || empty($no_hp) || empty($email) || empty($username) || empty($password)) {
        echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Semua kolom wajib diisi!',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
            </html>";
        exit;
    }

    // Validasi password
    if (!isValidPassword($password)) {
        echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Kata Sandi Tidak Valid',
                        text: 'Kata sandi harus terdiri dari huruf kapital, huruf kecil, angka, dan karakter khusus.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
            </html>";
        exit;
    }

    // Cek apakah email atau username sudah terdaftar
    $checkQuery = $conn->prepare("SELECT * FROM akun WHERE email = ? OR username = ?");
    $checkQuery->bind_param("ss", $email, $username);
    $checkQuery->execute();
    $result = $checkQuery->get_result();

    if ($result->num_rows > 0) {
        echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'warning',
                        title: 'Akun sudah terdaftar!',
                        text: 'Email atau Username sudah digunakan. Silakan gunakan data lain.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
            </html>";
        exit;
    }

    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Default values for new user
    $role = 'user';
    $status_verify = 0;

    // Query untuk menyimpan data ke database
    $stmt = $conn->prepare("INSERT INTO akun (email, username, password, nama_lengkap, no_hp, role, status_verify) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssi", $email, $username, $hashed_password, $nama_lengkap, $no_hp, $role, $status_verify);

    if ($stmt->execute()) {
        echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'success',
                        title: 'Registrasi berhasil!',
                        text: 'Silakan login.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '/website_bloodcare/website/public_html/auth/masuk.php';
                    });
                </script>
            </body>
            </html>";
    } else {
        echo "
            <html>
            <head>
                <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: 'error',
                        title: 'Terjadi kesalahan!',
                        text: 'Gagal menyimpan data. Silakan coba lagi.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
            </html>";
    }

    $stmt->close();
}

$conn->close();
?>
