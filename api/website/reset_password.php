<?php 
session_start();

// Konfigurasi database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

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
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        </body>
        </html>";
    } else {
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
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '/website_bloodcare/website/public_html/auth/masuk.php';
                        });
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
}
?>
