<?php
// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

// Set header untuk JSON response
header("Content-Type: application/json");

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari body POST
    $data = json_decode(file_get_contents("php://input"), true);
    
    if (isset($data['goldar'], $data['jenis_darah'], $data['rhesus'], $data['stok'])) {
        // Ambil data input
        $goldar = $data['goldar'];
        $jenis_darah = $data['jenis_darah'];
        $rhesus = $data['rhesus'];
        $stok_baru = $data['stok'];  // stok baru yang ingin ditambahkan
    
        $tanggal_pembaruan = date('Y-m-d H:i:s'); // Timestamp saat ini

        // Query update: Menambahkan stok darah yang baru
        $query = "UPDATE stok_darah 
                  SET stok = stok + ?, tanggal_pembaruan = ? 
                  WHERE goldar = ? AND jenis_darah = ? AND rhesus = ?";

        // Siapkan statement
        $stmt = $conn->prepare($query);
        if ($stmt) {
            // Bind parameter (hapus $kebutuhan)
            $stmt->bind_param("issss", $stok_baru, $tanggal_pembaruan, $goldar, $jenis_darah, $rhesus);
            
            // Eksekusi query
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Stok darah berhasil diperbarui.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Terjadi kesalahan saat memperbarui data stok darah.';
            }
            $stmt->close();
        } else {
            $response['status'] = 'error';
            $response['message'] = 'Kesalahan dalam persiapan query.';
        }
    } else {
        $response['status'] = 'error';
        $response['message'] = 'Parameter yang dibutuhkan tidak lengkap.';
    }
} else {
    $response['status'] = 'error';
    $response['message'] = 'Metode request harus POST.';
}

// Menampilkan response JSON
echo json_encode($response);
?>
