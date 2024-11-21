<?php
// Set header untuk JSON response
header('Content-Type: application/json');

// Menghubungkan ke database
$servername = "localhost";
$username = "root"; // Sesuaikan dengan username MySQL Anda
$password = ""; // Sesuaikan dengan password MySQL Anda
$dbname = "bloodcarec3"; // Nama database Anda

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Inisialisasi response default
$response = ["success" => false, "message" => "Data gagal disimpan"];

// Memastikan request method adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari POST request
    $lokasi = isset($_POST['lokasi']) ? $_POST['lokasi'] : '';
    $fasilitas = isset($_POST['fasilitas']) ? $_POST['fasilitas'] : '';
    $time_waktu = isset($_POST['time_waktu']) ? $_POST['time_waktu'] : '';
    $tgl_acara = isset($_POST['tgl_acara']) ? $_POST['tgl_acara'] : '';

    // Pastikan data tidak kosong
    if (!empty($lokasi) && !empty($fasilitas) && !empty($time_waktu) && !empty($tgl_acara)) {
        // Query untuk menyimpan data ke database
        $sql = "INSERT INTO acara_donor (lokasi, fasilitas, time_waktu, tgl_acara) 
                VALUES ('$lokasi', '$fasilitas', '$time_waktu', '$tgl_acara')";

        if ($conn->query($sql) === TRUE) {
            // Jika data berhasil disimpan
            $response = ["success" => true, "message" => "Data berhasil disimpan"];
        } else {
            // Jika terjadi kesalahan saat menyimpan data
            $response = ["success" => false, "message" => "Terjadi kesalahan: " . $conn->error];
        }
    } else {
        // Jika ada data yang kosong
        $response = ["success" => false, "message" => "Lengkapi semua data"];
    }
}

// Menutup koneksi
$conn->close();

// Mengirimkan response dalam format JSON
echo json_encode($response);
?>
