<?php
include "koneksi.php";
$sql = "SELECT * FROM stok_darah WHERE id='". $_POST['id']."'";

$query = mysqli_query($db, $sql);

$data = mysqli_fetch_assoc($query);
echo json_encode(array(
  'id' => $data['id'],
  'blood_type' => $data['blood_type'],
  'quantitiy' => $data['quantitiy'],
  'inpu_date' => $data['inpu_date'],
))




?>