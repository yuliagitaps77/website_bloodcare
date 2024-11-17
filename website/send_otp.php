<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

header("Content-Type: application/json");

// Cek metode permintaan apakah POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari body request
    $data = json_decode(file_get_contents('php://input'), true);

    // Cek apakah email sudah dikirim di body
    if (isset($data['email'])) {
        $recipientEmail = $data['email'];
        
        // Fungsi untuk generate OTP
        function generateOTP($length = 6) {
            $otp = '';
            for ($i = 0; $i < $length; $i++) {
                $otp .= rand(0, 9);
            }
            return $otp;
        }

        // Fungsi untuk mengirim OTP
        function sendOTP($recipientEmail, $otp) {
            $mail = new PHPMailer(true);
            $senderEmail = 'BloodCareServicec3@gmail.com'; // Ganti dengan email Anda
            $senderName = 'BloodCare';     // Ganti dengan nama pengirim yang Anda inginkan

            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = $senderEmail;               
                $mail->Password = 'bloodcare123*'; // Kata sandi Gmail atau App Password Anda
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
                return true;
            } catch (Exception $e) {
                return false;
            }
        }

        // Generate OTP
        $otp = generateOTP();

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
?>
