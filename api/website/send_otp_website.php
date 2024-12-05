<?php
session_start(); // Mulai session

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../koneksi.php';  // Pastikan koneksi ke database sudah benar

// Cek koneksi ke database
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Pastikan request method adalah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi email
    if (isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $recipientEmail = $_POST['email'];

        // Fungsi untuk generate OTP (5 digit angka)
        function generateOTP($length = 5) {
            $otp = '';
            for ($i = 0; $i < $length; $i++) {
                $otp .= rand(0, 9);
            }
            return $otp;
        }

        // Fungsi untuk mengirim OTP ke email
        function sendOTP($recipientEmail, $otp) {
            global $conn;  // Mengakses variabel $conn untuk koneksi database

            // Ambil nama_lengkap berdasarkan email
            $stmt = $conn->prepare("SELECT nama_lengkap FROM akun WHERE email = ?");
            $stmt->bind_param("s", $recipientEmail);
            $stmt->execute();
            $stmt->bind_result($namaLengkap);
            $stmt->fetch();
            $stmt->close();

            // Jika nama_lengkap tidak ditemukan, gunakan placeholder
            if (empty($namaLengkap)) {
                $namaLengkap = "Pengguna BloodCare";
            }

            // PHPMailer setup
            $mail = new PHPMailer(true);
            $senderEmail = 'e41232393@student.polije.ac.id';  // Ganti dengan email pengirim yang valid
            $senderName = 'BloodCare';

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $senderEmail;
                $mail->Password = 'wvku xdoa haoo vefu';  // Ganti dengan password atau app password yang benar
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom($senderEmail, $senderName);
                $mail->addAddress($recipientEmail);

                $mail->isHTML(true);
                $mail->Subject = 'Kode OTP Verifikasi Anda';

                // Email body dengan nama pengguna dan logo
                $mail->Body = '
                <div style="font-family: Arial, sans-serif; color: #444; line-height: 1.6; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <img src="https://i.ibb.co.com/1ZnTzKj/logo-apk.png" alt="BloodCare Logo" style="width: 100px; margin-bottom: 10px;">
                        <h2 style="color: #e74c3c;">Kode OTP Verifikasi</h2>
                    </div>
                    <p style="font-size: 16px;">Halo <strong>' . htmlspecialchars($namaLengkap) . '</strong>,</p>
                    <p>Terima kasih telah menggunakan layanan BloodCare. Berikut adalah kode OTP Anda untuk menyelesaikan proses ganti sandi:</p>
                    <div style="text-align: center; margin: 20px 0;">
                        <span style="font-size: 24px; color: #e74c3c; font-weight: bold;">' . $otp . '</span>
                    </div>
                    <p>Jika Anda tidak merasa meminta kode ini, abaikan email ini atau hubungi tim dukungan kami.</p>
                    <p style="color: #777; font-size: 14px; text-align: center; margin-top: 20px;">
                        <em>BloodCare - Peduli pada setiap tetes kehidupan.</em><br>
                        <strong>Hubungi Kami:</strong> support@bloodcare.com    
                    </p>
                </div>';

                $mail->AltBody = 'Halo ' . htmlspecialchars($namaLengkap) . ', Kode OTP Anda: ' . $otp . '. Gunakan kode ini untuk menyelesaikan verifikasi. Terima kasih telah menggunakan layanan BloodCare.';

                $mail->send();
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        // Periksa apakah akun sudah terdaftar di database
        $check_stmt = $conn->prepare("SELECT * FROM akun WHERE email = ?");
        $check_stmt->bind_param("s", $recipientEmail);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        // Cek apakah akun ditemukan
        if ($result->num_rows > 0) {
            // Akun ditemukan, buat OTP baru
            $otp = generateOTP();  // OTP 5 digit
            
            // Pastikan bahwa OTP adalah string
            $otp = (string) $otp;

            // Update OTP di database
            $stmt = $conn->prepare("UPDATE akun SET otp = ?, status_verify = 0 WHERE email = ?");
            $stmt->bind_param("ss", $otp, $recipientEmail);  // Gunakan "ss" karena kedua parameter adalah string

            if ($stmt->execute()) {
                // Jika berhasil update OTP, kirim OTP ke email
                if (sendOTP($recipientEmail, $otp)) {
                    // Simpan email ke session
                    $_SESSION['email'] = $recipientEmail;

                    // Tampilkan notifikasi sukses
                    echo '
                    <html>
                        <head>
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        </head>
                        <body>
                            <script>
                                Swal.fire({
                                    icon: "success",
                                    title: "OTP Terkirim!",
                                    text: "OTP berhasil dikirim ke email Anda.",
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    // Ganti dengan BASE_URL yang dinamis
                                    window.location.href = "' . BASE_URL . '/website/public_html/auth/kode_otp.php";
                                });
                            </script>
                        </body>
                    </html>';
                } else {
                    // Jika gagal mengirim OTP
                    echo '
                    <html>
                        <head>
                            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                        </head>
                        <body>
                            <script>
                                Swal.fire({
                                    icon: "error",
                                    title: "Gagal",
                                    text: "Gagal mengirim OTP. Silakan coba lagi nanti.",
                                    showConfirmButton: true
                                }).then(() => {
                                    window.history.back(); // Kembali ke halaman sebelumnya
                                });
                            </script>
                        </body>
                    </html>';
                }
            } else {
                // Jika gagal memperbarui OTP
                echo "Gagal memperbarui OTP di database: " . $stmt->error;
            }
            $stmt->close();
        } else {
            // Akun tidak ditemukan, beri notifikasi dan redirect
            echo '
            <html>
                <head>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                </head>
                <body>
                    <script>
                        Swal.fire({
                            icon: "warning",
                            title: "Akun Tidak Terdaftar",
                            text: "Email ini tidak terdaftar di sistem kami. Mengarahkan ke halaman Lupa Kata Sandi.",
                            showConfirmButton: false,
                            timer: 3000
                        }).then(() => {
                            window.location.href = "' . BASE_URL . '/website/public_html/auth/lupa_kata_sandi.php";
                        });
                    </script>
                </body>
            </html>';
        }

        $check_stmt->close();
    } else {
        // Jika email tidak valid
        echo '
        <html>
            <head>
                <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            </head>
            <body>
                <script>
                    Swal.fire({
                        icon: "error",
                        title: "Email Tidak Valid",
                        text: "Silakan masukkan alamat email yang valid.",
                        showConfirmButton: true
                    }).then(() => {
                        window.history.back(); // Kembali ke halaman sebelumnya
                    });
                </script>
            </body>
        </html>';
    }
}

?>
