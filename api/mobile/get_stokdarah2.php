<?php
header('Content-Type: application/json');

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Periksa koneksi
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Koneksi database gagal: " . $conn->connect_error
    ]);
    exit;
}

// Ambil parameter dari request
$goldar = isset($_GET['golongan_darah']) ? $_GET['golongan_darah'] : null;
$rhesus = isset($_GET['rhesus']) ? $_GET['rhesus'] : null;
$jenis_darah = isset($_GET['jenis_darah']) ? $_GET['jenis_darah'] : null;

// Validasi parameter
if ($goldar && $rhesus && $jenis_darah) {
    // Query untuk mendapatkan data stok darah berdasarkan goldar, rhesus, dan jenis darah
    $query = "SELECT goldar, rhesus, stok, jenis_darah FROM stok_darah WHERE goldar = ? AND rhesus = ? AND jenis_darah = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("sss", $goldar, $rhesus, $jenis_darah);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            echo json_encode([
                "success" => true,
                "data" => $data
            ]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Data tidak ditemukan untuk golongan darah: " . $goldar . ", rhesus: " . $rhesus . ", dan jenis darah: " . $jenis_darah
            ]);
        }
        $stmt->close();
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Query tidak dapat diproses."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Parameter golongan_darah, rhesus, dan jenis_darah diperlukan."
    ]);
}

$conn->close();
?>
