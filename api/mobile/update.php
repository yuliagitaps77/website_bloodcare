<?php
include 'koneksi.php';

// Pastikan semua parameter tersedia
if (isset($_POST['id'], $_POST['blood_type'], $_POST['quantitiy'], $_POST['inpu_date'])) {
    // Siapkan pernyataan SQL
    $sql = "UPDATE stok_darah 
            SET 
                blood_type = '".$_POST['blood_type']."',
                quantitiy = '".$_POST['quantitiy']."',
                inpu_date = '".$_POST['inpu_date']."' 
            WHERE id = '".$_POST['id']."'";

    // Eksekusi query
    $query = mysqli_query($db, $sql);

    if ($query) {
        echo json_encode(array(
            'status' => 'data berhasil di update'
        ));
    } else {
        echo json_encode(array(
            'status' => 'gagal',
            'error' => mysqli_error($db) // Menampilkan error SQL
        ));
    }
} else {
    echo json_encode(array(
        'status' => 'gagal',
        'error' => 'Parameter tidak lengkap.'
    ));
}
?>
