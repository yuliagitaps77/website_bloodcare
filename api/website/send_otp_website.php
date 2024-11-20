<?php
session_start(); // Mulai session

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi email
    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $recipientEmail = $_POST['email'];

        function generateOTP($length = 5) {
            $otp = '';
            for ($i = 0; $i < $length; $i++) {
                $otp .= rand(0, 9);
            }
            return $otp;
        }

        function sendOTP($recipientEmail, $otp) {
            $mail = new PHPMailer(true);
            $senderEmail = 'e41232393@student.polije.ac.id';
            $senderName = 'bloodcare';
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $senderEmail;
                $mail->Password = 'wvku xdoa haoo vefu';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($senderEmail, $senderName);
                $mail->addAddress($recipientEmail);

                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Verification Code';
                $mail->Body = '<p>Your OTP: <strong>' . $otp . '</strong></p>';
                $mail->AltBody = 'Your OTP: ' . $otp;

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        // Periksa apakah akun sudah terdaftar
        $check_stmt = $conn->prepare("SELECT * FROM akun WHERE email = ?");
        $check_stmt->bind_param("s", $recipientEmail);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Akun ditemukan, perbarui OTP
            $otp = generateOTP();
            $stmt = $conn->prepare("UPDATE akun SET otp = ?, status_verify = 0 WHERE email = ?");
            $stmt->bind_param("is", $otp, $recipientEmail);
            $stmt->execute();

            // Kirim OTP ke email
            if (sendOTP($recipientEmail, $otp)) {
                // Simpan email ke session
                $_SESSION['email'] = $recipientEmail;

                echo '
                <html>
                    <head>
                        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    </head>
                    <body>
                        <script>
                            Swal.fire({
                                icon: "success",
                                title: "OTP Sent!",
                                text: "OTP has been sent successfully to your email.",
                                showConfirmButton: false,
                                timer: 2000
                            }).then(() => {
                                window.location.href = "http://localhost/website_bloodcare/website/public_html/auth/kode_otp.php";
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
                                title: "Failed",
                                text: "Failed to send OTP. Please try again later.",
                                showConfirmButton: true
                            });
                        </script>
                    </body>
                </html>';
            }
        } else {
            // Akun tidak ditemukan, redirect ke lupa_kata_sandi.php
            echo '
            <html>
                <head>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "warning",
                            title: "Account Not Registered",
                            text: "This email is not registered in our system. Redirecting to Forgot Password page.",
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.href = "http://localhost/website_bloodcare/website/public_html/auth/lupa_kata_sandi.php";
                        });
                    </script>
                </body>
            </html>';
        }

        $check_stmt->close();
    } else {
        // Email tidak valid
        echo '
        <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Invalid Email",
                        text: "Please enter a valid email address.",
                        showConfirmButton: true
                    });
                </script>
            </body>
        </html>';
    }
}

$conn->close();
?>
