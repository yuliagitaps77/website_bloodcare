<?php
// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Koneksi database gagal: " . $conn->connect_error
    ]));
}

// Query untuk mengambil stok darah berdasarkan golongan darah dan rhesus
$sql = "SELECT 
            SUM(CASE WHEN goldar = 'A' AND rhesus = 'positive' THEN stok ELSE 0 END) AS A_plus,
            SUM(CASE WHEN goldar = 'A' AND rhesus = 'negative' THEN stok ELSE 0 END) AS A_minus,
            SUM(CASE WHEN goldar = 'B' AND rhesus = 'positive' THEN stok ELSE 0 END) AS B_plus,
            SUM(CASE WHEN goldar = 'B' AND rhesus = 'negative' THEN stok ELSE 0 END) AS B_minus,
            SUM(CASE WHEN goldar = 'AB' AND rhesus = 'positive' THEN stok ELSE 0 END) AS AB_plus,
            SUM(CASE WHEN goldar = 'AB' AND rhesus = 'negative' THEN stok ELSE 0 END) AS AB_minus,
            SUM(CASE WHEN goldar = 'O' AND rhesus = 'positive' THEN stok ELSE 0 END) AS O_plus,
            SUM(CASE WHEN goldar = 'O' AND rhesus = 'negative' THEN stok ELSE 0 END) AS O_minus
        FROM stok_darah";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Ambil hasil query sebagai array asosiatif
    $data = $result->fetch_assoc();

    // Respons JSON
    echo json_encode([
        "success" => true,
        "data" => $data
    ]);
} else {
    // Jika tidak ada data ditemukan
    echo json_encode([
        "success" => false,
        "message" => "Tidak ada data stok darah ditemukan"
    ]);
}

// Tutup koneksi database
$conn->close();
?>
