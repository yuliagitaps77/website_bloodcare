<?php
header("Content-Type: application/json; charset=UTF-8");
// Konfigurasi koneksi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcare";
// Koneksi ke database
$conn = new mysqli($host, $username, $password, $dbname);
// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]));
}
// Memeriksa metode request
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari $_POST
    if (!empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password'])) {
        // Escape data untuk keamanan
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

        // Nilai default untuk kolom lainnya
        $nama_lengkap = "";
        $no_hp = "";
        $alamat = "";
        $role = 0;
        $tanggal_lahir = "0000-00-00";
        $otp = 0;

        // Query untuk menyimpan data pengguna
        $query = "INSERT INTO akun (username, email, password, nama_lengkap, no_hp, alamat, role, tanggal_lahir, otp)
                  VALUES ('$username', '$email', '$password', '$nama_lengkap', '$no_hp', '$alamat', '$role', '$tanggal_lahir', '$otp')";

        if ($conn->query($query) === TRUE) {
            echo json_encode(["success" => true, "message" => "Registrasi berhasil"]);
        } else {
            echo json_encode(["success" => false, "message" => "Registrasi gagal: " . $conn->error]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Metode request tidak valid"]);
}

// Tutup koneksi
$conn->close();
?>

