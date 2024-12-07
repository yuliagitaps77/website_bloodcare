<?php
header('Content-Type: application/json');

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Periksa koneksi
if ($conn->connect_error) {
    die(json_encode([
        "success" => false,
        "message" => "Koneksi database gagal: " . $conn->connect_error
    ]));
}

// Ambil data dari request POST
$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['goldar'], $data['stok'], $data['jenis_darah'], $data['rhesus'])) { // Hilangkan kebutuhan
    $golongan_darah = $data['goldar'];
    $stok = $data['stok'];
    $jenis_darah = $data['jenis_darah'];
    $rhesus = $data['rhesus'];

    // Query untuk memperbarui stok darah
    $query = "UPDATE stok_darah 
              SET stok = ? 
              WHERE goldar = ? AND jenis_darah = ? AND rhesus = ?";
    $stmt = $conn->prepare($query);

    if ($stmt) {
        $stmt->bind_param("isss", $stok, $golongan_darah, $jenis_darah, $rhesus);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "Data updated successfully"]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to update data. Error: " . $stmt->error]);
        }
        $stmt->close();
    } else {
        echo json_encode(["success" => false, "message" => "Failed to prepare statement. Error: " . $conn->error]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Incomplete data"]);
}


$conn->close();
?>
