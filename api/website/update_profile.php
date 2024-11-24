<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/Masuk.html");
    exit();
}

// Database connection
$host = "localhost";
$username = "bloodcar_e";
$password = "G_(Q+shgC2Nn";
$dbname = "bloodcar_e";

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$user_id = $_SESSION['user_id'];
$nama = $_POST['nama'] ?? null;
$alamat = $_POST['alamat'] ?? null;
$tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
$no_hp = $_POST['no_hp'] ?? null;

// Proses unggahan gambar
$profile_picture_path = null;
$update_picture = false;
if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = 'uploads/'; // Direktori penyimpanan file
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true); // Buat folder jika belum ada
    }

    // Validasi file
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $file_type = mime_content_type($_FILES['profile_picture']['tmp_name']);

    if (!in_array($file_type, $allowed_types)) {
        echo "
        <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        <script>
            window.onload = function() {
                Swal.fire({
                    title: 'Error!',
                    text: 'File yang diunggah harus berupa gambar (JPG, PNG, atau GIF).',
                    icon: 'error',
                    confirmButtonText: 'OK'
                }).then(() => {
                    window.history.back(); // Kembali ke halaman sebelumnya
                });
            };
        </script>";
        exit();
    }

    $file_name = basename($_FILES['profile_picture']['name']);
    $file_name = str_replace(' ', '_', $file_name); // Ganti spasi dengan underscore
    $target_file = $upload_dir . uniqid() . "_" . $file_name;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture_path = $target_file; // Simpan path gambar untuk database
        $update_picture = true; // Tandai bahwa gambar harus diperbarui
    } else {
        die("Gagal mengunggah gambar. Periksa izin folder.");
    }
}

// Query untuk memperbarui profil
if ($update_picture) {
    // Update dengan gambar
    $query = "UPDATE akun SET nama_lengkap = ?, alamat = ?, tanggal_lahir = ?, no_hp = ?, profile_picture = ? WHERE id_akun = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Gagal mempersiapkan statement: " . $conn->error);
    }
    $stmt->bind_param("sssssi", $nama, $alamat, $tanggal_lahir, $no_hp, $profile_picture_path, $user_id);
} else {
    // Update tanpa gambar
    $query = "UPDATE akun SET nama_lengkap = ?, alamat = ?, tanggal_lahir = ?, no_hp = ? WHERE id_akun = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        die("Gagal mempersiapkan statement: " . $conn->error);
    }
    $stmt->bind_param("ssssi", $nama, $alamat, $tanggal_lahir, $no_hp, $user_id);
}

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
