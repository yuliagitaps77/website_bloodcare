<?php
header("Content-Type: application/json");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Cek koneksi
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Koneksi gagal: " . $conn->connect_error]);
    exit();
}

// Periksa apakah request adalah POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id_akun']) && isset($_FILES['profile_picture'])) {
        $id_akun = $conn->real_escape_string($_POST['id_akun']);

        // Validasi tipe file dan ukuran file
        $allowedTypes = ['image/jpeg', 'image/png'];
        $fileType = $_FILES['profile_picture']['type'];
        $fileSize = $_FILES['profile_picture']['size'];
        
        if (!in_array($fileType, $allowedTypes)) {
            echo json_encode(["status" => "error", "message" => "Format file tidak didukung. Gunakan JPEG atau PNG."]);
            exit();
        }
        
        if ($fileSize > 2 * 1024 * 1024) { // Maksimal 2MB
            echo json_encode(["status" => "error", "message" => "Ukuran file maksimal 2MB."]);
            exit();
        }

        // Simpan gambar ke folder uploads
        $uploadsDir = "uploads/";
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0777, true);
        }

        $fileName = uniqid() . "_" . basename($_FILES['profile_picture']['name']);
        $targetFilePath = $uploadsDir . $fileName;

        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetFilePath)) {
            // Update path gambar ke database
            $query = "UPDATE akun SET profile_picture = ? WHERE id_akun = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $targetFilePath, $id_akun);

            if ($stmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Foto profil berhasil diperbarui.", "file_path" => $targetFilePath]);
            } else {
                echo json_encode(["status" => "error", "message" => "Gagal memperbarui foto profil di database."]);
            }

            $stmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Gagal mengunggah file ke server."]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Parameter id_akun dan profile_picture wajib diisi."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "Metode request tidak valid. Gunakan POST."]);
}

$conn->close();
?>
