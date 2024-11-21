<?php
header("Content-Type: text/html; charset=UTF-8");
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Cek apakah pengguna sudah login
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

// Konfigurasi koneksi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("<script>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Koneksi ke server gagal. Silakan coba lagi nanti!',
                confirmButtonText: 'OK'
            }).then(() => {
                window.history.back();
            });
        </script>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $query = "SELECT * FROM akun WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
                $_SESSION['user_id'] = $user['id_akun'];
                $_SESSION['email'] = $user['email'];

                echo "
                    <html>
                    <head>
                        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
                    </head>
                    <body>
                        <script>
                            let countdown = 3;
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Berhasil',
                                html: 'Anda akan diarahkan ke dashboard dalam <b>' + countdown + '</b> detik.',
                                timer: countdown * 1000,
                                timerProgressBar: true,
                                allowOutsideClick: false,
                                didOpen: () => {
                                    const content = Swal.getHtmlContainer();
                                    const b = content.querySelector('b');
                                    const interval = setInterval(() => {
                                        countdown--;
                                        if (b) b.textContent = countdown;
                                        if (countdown <= 0) clearInterval(interval);
                                    }, 1000);
                                }
                            }).then(() => {
                                window.location.href = 'http://localhost/website_bloodcare/website/public_html/dashboard/dist/index.php';
                            });
                        </script>
                    </body>
                    </html>";
                exit();
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
                                title: 'Oops...',
                                text: 'Password salah. Silakan coba lagi!',
                                confirmButtonText: 'OK'
                            }).then(() => {
                                window.history.back();
                            });
                        </script>
                    </body>
                    </html>";
            }
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
                            title: 'Oops...',
                            text: 'Email tidak ditemukan. Apakah Anda sudah mendaftar?',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.history.back();
                        });
                    </script>
                </body>
                </html>";
        }
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
                        title: 'Oops...',
                        text: 'Email dan Password wajib diisi!',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.history.back();
                    });
                </script>
            </body>
            </html>";
    }
}

$conn->close();
?>
