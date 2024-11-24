<?php 
session_start();

// Konfigurasi database
$servername = "localhost";
$username = "bloodcar_e";
$password = "G_(Q+shgC2Nn";
$dbname = "bloodcar_e";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Periksa apakah email ada di sesi
if (!isset($_SESSION['email'])) {
    echo "
    <!DOCTYPE html>
    <html>
    <head>
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    </head>
    <body>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Email Tidak Ditemukan',
                    text: 'Silakan coba lagi.',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.location.href = 'lupa_password.php';
                });
            });
        </script>
    </body>
    </html>";
    exit();
}

$email = $_SESSION['email'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validasi password
    function isValidPassword($password) {
        return preg_match('/[A-Z]/', $password) &&    // Huruf kapital
               preg_match('/[a-z]/', $password) &&    // Huruf kecil
               preg_match('/[0-9]/', $password) &&    // Angka
               preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password); // Karakter khusus
    }

    if (!isValidPassword($password)) {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Kata Sandi Tidak Valid',
                        text: 'Kata sandi harus terdiri dari huruf kapital, huruf kecil, angka, dan karakter khusus.',
                        timer: 3000, // Durasi 3 detik
                        showConfirmButton: false // Sembunyikan tombol OK
                    }).then(() => {
                        window.history.back(); // Kembali ke halaman sebelumnya
                    });
    
                    // Jaga-jaga kembali otomatis setelah 3 detik
                    setTimeout(() => {
                        window.history.back();
                    }, 3000);
                });
            </script>
        </body>
        </html>";
        exit();
    }


    // Periksa apakah password dan konfirmasi cocok
    if ($password !== $confirm_password) {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Konfirmasi sandi tidak cocok. Silakan coba lagi.',
                        timer: 3000, // Durasi 3 detik
                        showConfirmButton: false // Sembunyikan tombol OK
                    }).then(() => {
                        window.history.back(); // Kembali ke halaman sebelumnya
                    });
    
                    // Kembali otomatis setelah timer selesai
                    setTimeout(() => {
                        window.history.back();
                    }, 3000);
                });
            </script>
        </body>
        </html>";
        exit();
    }
    

    // Enkripsi password
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Update password di database
    $query = "UPDATE akun SET password = ? WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $hashed_password, $email);

    if ($stmt->execute()) {
echo "
<!DOCTYPE html>
<html>
<head>
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
</head>
<body>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: 'Kata sandi berhasil diubah. Silakan login.',
                timer: 3000, // Durasi 3 detik
                showConfirmButton: false // Sembunyikan tombol OK
            }).then(() => {
                window.location.href = '/website_bloodcare/website/public_html/auth/masuk.php';
            });

            // Pengalihan otomatis setelah 3 detik (untuk berjaga-jaga)
            setTimeout(() => {
                window.location.href = '/website_bloodcare/website/public_html/auth/masuk.php';
            }, 3000);
        });
    </script>
</body>
</html>";

        session_destroy();
        exit();
    } else {
        echo "
        <!DOCTYPE html>
        <html>
        <head>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan',
                        text: 'Terjadi kesalahan. Silakan coba lagi.',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        </body>
        </html>";
    }
}
?>
