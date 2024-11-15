<?php
include "koneksi.php";

// Ambil data dari POST
$tipedarah = $_POST['blood_type'];
$jumlah = $_POST['quantitiy'];
$tanggal = $_POST['inpu_date'];

// Tidak perlu mendefinisikan ID, biarkan database yang menanganinya
$sql = "INSERT INTO `stok_darah` (`blood_type`, `quantitiy`, `inpu_date`) 
VALUES ('$tipedarah', '$jumlah', '$tanggal');";

$query = mysqli_query($db, $sql);
if ($query) {
    echo json_encode(array(
        'status' => 'data_tersimpan'
    ));
} else {
    echo json_encode(array(
        'status' => 'gagal',
        'error' => mysqli_error($db) // Menampilkan error SQL
    ));
}
?>
