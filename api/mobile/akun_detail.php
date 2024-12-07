<?php
header("Content-Type: application/json; charset=UTF-8");

// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

    // Periksa koneksi
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]);
        exit;
    }

    // Ambil parameter username_or_email dari query string
    $usernameOrEmail = isset($_GET['username_or_email']) ? $_GET['username_or_email'] : null;

    if (empty($usernameOrEmail)) {
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "message" => "Parameter username_or_email tidak valid atau tidak ada"]);
        exit;
    }

    // Query untuk mengambil data akun berdasarkan email atau username
    $sql = "SELECT email, username, nama_lengkap, tanggal_lahir, no_hp, alamat, id_akun FROM akun WHERE email = ? OR username = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
        $stmt->execute();
        $result = $stmt->get_result();

        // Periksa apakah data ditemukan
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            http_response_code(200); // OK
            echo json_encode(["success" => true, "data" => $data]);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(["success" => false, "message" => "Data tidak ditemukan"]);
        }

        $stmt->close();
    } else {
        http_response_code(500); // Internal Server Error
        echo json_encode(["success" => false, "message" => "Gagal memproses permintaan"]);
    }

    
?>
