<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

header("Content-Type: application/json");

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

// Buat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Cek metode permintaan apakah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari body request
    $data = json_decode(file_get_contents('php://input'), true);

    // Cek apakah email sudah dikirim di body
    if (isset($data['email'])) {
        $recipientEmail = $data['email'];

        // Fungsi untuk generate OTP
        function generateOTP($length = 5) {
            $otp = '';
            for ($i = 0; $i < $length; $i++) {
                $otp .= rand(0, 9);
            }
            return $otp;
        }

        // Fungsi untuk mengirim OTP
        function sendOTP($recipientEmail, $otp) {
            $mail = new PHPMailer(true);
            $senderEmail = 'e41232393@student.polije.ac.id'; // Ganti dengan email Anda
            $senderName = 'bloodcare'; // Ganti dengan nama pengirim yang Anda inginkan
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $senderEmail;  // Ganti dengan email Anda
                $mail->Password = 'wvku xdoa haoo vefu'; // Kata sandi Gmail atau App Password Anda
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;
        
                $mail->setFrom($senderEmail, $senderName);
                $mail->addAddress($recipientEmail);
        
                $mail->isHTML(true);
                $mail->Subject = 'Your OTP Verification Code';
                $mail->Body = '
                <html>
                    <head>
                        <style>
                            .email-container { font-family: Arial, sans-serif; color: #333; padding: 20px; text-align: center; border: 1px solid #ddd; border-radius: 8px; background-color: #f7f7f7; }
                            .otp-code { font-size: 24px; font-weight: bold; color: #4CAF50; margin-top: 20px; }
                            .message { font-size: 16px; color: #555; }
                            .footer { font-size: 14px; color: #999; margin-top: 20px; }
                        </style>
                    </head>
                    <body>
                        <div class="email-container">
                            <h2>Hello!</h2>
                            <p class="message">Thank you for choosing us. Please use the following OTP to complete your verification process. This OTP is valid for 5 minutes.</p>
                            <div class="otp-code">' . $otp . '</div>
                            <p class="footer">If you did not request this OTP, please ignore this email.</p>
                        </div>
                    </body>
                </html>';
                $mail->AltBody = 'Your OTP verification code is: ' . $otp;
        
                $mail->send();
                return true;  // Jika berhasil mengirim email
            } catch (Exception $e) {
                // Log error untuk debugging
                error_log('Error sending OTP: ' . $mail->ErrorInfo);  // Tambahkan log kesalahan
                return false;  // Jika gagal mengirim email
            }
        }

        // Generate OTP
        $otp = generateOTP();

        // Periksa apakah email sudah ada di database
        $check_stmt = $conn->prepare("SELECT * FROM akun WHERE email = ?");
        $check_stmt->bind_param("s", $recipientEmail);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            // Jika email sudah ada, update OTP dan status verifikasi
            $stmt = $conn->prepare("UPDATE akun SET otp = ?, status_verify = 0 WHERE email = ?");
            $stmt->bind_param("is", $otp, $recipientEmail);
            $stmt->execute();

            // Kirim OTP ke email
            if (sendOTP($recipientEmail, $otp)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'OTP has been sent successfully!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Failed to send OTP.'
                ]);
            }
        } else {
            // Jika email tidak ditemukan
            echo json_encode([
                'status' => 'error',
                'message' => 'Email is not registered.'
            ]);
        }

        // Menutup statement dan koneksi database
        $stmt->close();
        $check_stmt->close();
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Email is required.'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid request method. Use POST.'
    ]);
}

// Tutup koneksi database
$conn->close();
?>
