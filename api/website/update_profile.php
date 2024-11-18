<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/Masuk.html");
    exit();
}

$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];
$nama = $_POST['nama'] ?? null;
$alamat = $_POST['alamat'] ?? null;
$tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
$no_hp = $_POST['no_hp'] ?? null;

// Proses unggahan gambar
$profile_picture_path = null;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/'; // Direktori untuk menyimpan gambar
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Buat direktori jika belum ada
    }

    $file_name = basename($_FILES['profile_picture']['name']);
    $target_file = $upload_dir . uniqid() . "_" . $file_name; // Tambahkan uniqid untuk menghindari nama file duplikat

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture_path = $target_file; // Simpan path gambar untuk database
    } else {
        echo "<p>Gagal mengunggah gambar.</p>";
        exit();
    }
}

// Query update profil
$query = "UPDATE akun SET nama_lengkap = ?, alamat = ?, tanggal_lahir = ?, no_hp = ?, profile_picture = ? WHERE id_akun = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("Gagal mempersiapkan statement: " . $conn->error);
}
$stmt->bind_param("sssssi", $nama, $alamat, $tanggal_lahir, $no_hp, $profile_picture_path, $user_id);

if ($stmt->execute()) {
    echo "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script>
        window.onload = function() {
            Swal.fire({
                title: 'Berhasil!',
                text: 'Profil Anda berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            }).then((result) => {
                if (result.isConfirmed || result.dismiss === Swal.DismissReason.timer) {
                    window.location.href = 'http://localhost/website_bloodcare/website/public_html/dashboard/dist/index.php#!';
                }
            });
        };
    </script>";
} else {
    echo "<p>Terjadi kesalahan saat memperbarui profil: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();
?>
