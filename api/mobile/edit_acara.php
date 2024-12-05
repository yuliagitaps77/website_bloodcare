<?php
// Impor koneksi database
require_once __DIR__ . '/../koneksi.php';

$id_acara = $_POST['id_acara'];
$lokasi = $_POST['lokasi'];
$fasilitas = $_POST['fasilitas'];
$time_waktu = $_POST['time_waktu'];
$tgl_acara = $_POST['tgl_acara'];

$response = array();

if ($id_acara && $lokasi && $fasilitas && $time_waktu && $tgl_acara) {
    $query = "UPDATE acara_donor SET lokasi = '$lokasi', fasilitas = '$fasilitas', time_waktu = '$time_waktu', tgl_acara = '$tgl_acara' WHERE id_acara = '$id_acara'";

    if (mysqli_query($conn, $query)) {
        $response['success'] = true;
        $response['message'] = 'Acara berhasil diperbarui';
    } else {
        $response['success'] = false;
        $response['message'] = 'Gagal memperbarui acara';
    }
} else {
    $response['success'] = false;
    $response['message'] = 'Semua data harus diisi';
}

echo json_encode($response);
?>
