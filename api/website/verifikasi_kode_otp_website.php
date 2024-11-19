<?php
session_start();
header("Content-Type: text/html; charset=UTF-8");

// Konfigurasi koneksi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi database
if ($conn->connect_error) {
    echo '
    <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Koneksi ke database gagal. Silakan coba lagi nanti!",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
    </html>';
    exit();
}

// Ambil email dari session
if (!isset($_SESSION['email'])) {
    echo '
    <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Email tidak ditemukan. Silakan coba lagi.",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.location.href = "http://localhost/website_bloodcare/website/public_html/auth/lupa_kata_sandi.php";
                });
            </script>
        </body>
    </html>';
    exit();
}

$email = $_SESSION['email']; // Ambil email dari session

// Periksa apakah metode request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $otp = $_POST['otp'] ?? null;

    // Validasi input
    if (empty($otp)) {
        echo '
        <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "OTP wajib diisi!",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
        </html>';
        exit();
    }

    // Escape input untuk keamanan
    $otp = $conn->real_escape_string($otp);

    // Periksa apakah email dan OTP sesuai di database
    $query = "SELECT * FROM akun WHERE email = ? AND otp = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo '
        <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "Terjadi kesalahan pada server.",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
        </html>';
        exit();
    }
    $stmt->bind_param("ss", $email, $otp);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // OTP valid, perbarui status verifikasi
        $update_query = "UPDATE akun SET status_verify = 1, otp = NULL WHERE email = ?";
        $update_stmt = $conn->prepare($update_query);
        if (!$update_stmt) {
            echo '
            <html>
                <head>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Gagal memperbarui status verifikasi.",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.history.back();
                        });
                    </script>
                </body>
            </html>';
            exit();
        }
        $update_stmt->bind_param("s", $email);
        if ($update_stmt->execute()) {
            echo '
            <html>
                <head>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "success",
                            title: "Verifikasi Berhasil!",
                            text: "Akun Anda telah terverifikasi. Anda akan diarahkan ke halaman login.",
                            showConfirmButton: false,
                            timer: 2000,
                            timerProgressBar: true
                        }).then(() => {
                            window.location.href = "http://localhost/website_bloodcare/website/public_html/auth/sandi_baru.php";
                        });
                    </script>
                </body>
            </html>';
        } else {
            echo '
            <html>
                <head>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Gagal memperbarui status verifikasi. Silakan coba lagi.",
                            confirmButtonText: "OK"
                        }).then(() => {
                            window.history.back();
                        });
                    </script>
                </body>
            </html>';
        }
        $update_stmt->close();
    } else {
        // OTP tidak valid
        echo '
        <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Oops...",
                        text: "OTP tidak valid atau email tidak ditemukan.",
                        confirmButtonText: "OK"
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
        </html>';
    }

    $stmt->close();
} else {
    echo '
    <html>
        <head>
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        </head>
        <body>
            <script>
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Metode request tidak valid.",
                    confirmButtonText: "OK"
                }).then(() => {
                    window.history.back();
                });
            </script>
        </body>
    </html>';
}

$conn->close();
?>
