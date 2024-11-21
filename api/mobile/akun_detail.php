<?php
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

try {
    // Koneksi ke database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Periksa koneksi
    if ($conn->connect_error) {
        http_response_code(500);
        echo json_encode(["success" => false, "message" => "Koneksi gagal: " . $conn->connect_error]);
        exit;
    }

    // Ambil parameter id_akun dari query string
    $id = isset($_GET['id_akun']) && $_GET['id_akun'] > 0 ? intval($_GET['id_akun']) : null;

    if (is_null($id)) {
        http_response_code(400); // Bad Request
        echo json_encode(["success" => false, "message" => "Parameter id_akun tidak valid atau tidak ada"]);
        exit;
    }

    // Query untuk mengambil data akun
    $sql = "SELECT email, username, nama_lengkap, tanggal_lahir, no_hp, alamat FROM akun WHERE id_akun = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $id);
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
} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    echo json_encode(["success" => false, "message" => "Terjadi kesalahan: " . $e->getMessage()]);
} finally {
    $conn->close();
}
?>
