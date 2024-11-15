<?php
include 'koneksi.php';
$id = $_POST['id'];
$sql = "DELETE FROM stok_darah WHERE id='".$id."'";

$query = mysqli_query($db, $sql);
if ($query){
 echo json_encode(array(
  'status' => 'data berhasil dihapus'
 ));

} else {
 echo "data gagal dihapus";
}


?>