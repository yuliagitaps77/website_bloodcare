<?php
header("Content-Type: application/json; charset=UTF-8");

// Konfigurasi koneksi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi ke server gagal. Silakan coba lagi nanti!"]));
}

// Memeriksa metode request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = $conn->real_escape_string($_POST['email']);
        $password = $_POST['password'];

        $query = "SELECT * FROM akun WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];

                // Kirim respons JSON dengan URL redirect
                echo json_encode([
                    "success" => true,
                    "message" => "Login berhasil! Mengarahkan ke dashboard...",
                    "redirect" => "http://localhost/bloodcare_website/website/public_html/dashboard/dist/index.html"
                ]);
                exit();
            } else {
                echo json_encode([
                    "success" => false,
                    "message" => "Oops! Password salah. Silakan coba lagi."
                ]);
            }
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Email tidak ditemukan. Apakah Anda sudah mendaftar?"
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Email dan Password wajib diisi!"
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Metode request tidak valid."
    ]);
}

$conn->close();
?>
