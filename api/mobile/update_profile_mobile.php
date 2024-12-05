<?php 
session_start();

header('Content-Type: application/json');

require_once __DIR__ . '/../koneksi.php';

if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit();
}

// Ambil data dari POST request
$id_akun = $_POST['id_akun'] ?? null;
$username = $_POST['username'] ?? null;
$nama_lengkap = $_POST['nama_lengkap'] ?? null;
$no_hp = $_POST['no_hp'] ?? null;
$alamat = $_POST['alamat'] ?? null;
$tanggal_lahir = $_POST['tanggal_lahir'] ?? null;

// Log input yang diterima
error_log("Data diterima: id_akun=$id_akun, username=$username, nama_lengkap=$nama_lengkap, alamat=$alamat, tanggal_lahir=$tanggal_lahir, no_hp=$no_hp");

// Validasi input (Anda bisa menyesuaikan sesuai kebutuhan)
if (!$id_akun || !$username || !$nama_lengkap || !$no_hp || !$alamat || !$tanggal_lahir) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Semua field wajib diisi.'
    ]);
    exit();
}

// Validasi tanggal lahir (tidak boleh lebih dari tanggal hari ini)
$current_date = date('Y-m-d');
if ($tanggal_lahir > $current_date) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tanggal lahir tidak boleh lebih dari hari ini.'
    ]);
    exit(); // Hentikan eksekusi script jika tanggal lahir tidak valid
}

// Proses unggahan gambar jika ada
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
        echo json_encode([
            'status' => 'error',
            'message' => 'File yang diunggah harus berupa gambar (JPG, PNG, atau GIF).'
        ]);
        exit();
    }

    $file_name = basename($_FILES['profile_picture']['name']);
    $file_name = str_replace(' ', '_', $file_name); // Ganti spasi dengan underscore
    $target_file = $upload_dir . uniqid() . "_" . $file_name;

    if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
        $profile_picture_path = $target_file; // Simpan path gambar untuk database
        $update_picture = true; // Tandai bahwa gambar harus diperbarui
        error_log("Gambar berhasil diunggah ke: $target_file");
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengunggah gambar. Periksa izin folder.'
        ]);
        exit();
    }
}

// Query untuk memperbarui profil
if ($update_picture) {
    // Update dengan gambar
    $query = "UPDATE akun SET username = ?, nama_lengkap = ?, alamat = ?, tanggal_lahir = ?, no_hp = ?, profile_picture = ? WHERE id_akun = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query preparation failed: ' . $conn->error
        ]);
        exit();
    }
    // Pastikan urutan variabel sesuai dengan urutan placeholder
    $stmt->bind_param("ssssssi", $username, $nama_lengkap, $alamat, $tanggal_lahir, $no_hp, $profile_picture_path, $id_akun);
} else {
    // Update tanpa gambar
    $query = "UPDATE akun SET username = ?, nama_lengkap = ?, alamat = ?, tanggal_lahir = ?, no_hp = ? WHERE id_akun = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query preparation failed: ' . $conn->error
        ]);
        exit();
    }
    // Pastikan urutan variabel sesuai dengan urutan placeholder
    $stmt->bind_param("sssssi", $username, $nama_lengkap, $alamat, $tanggal_lahir, $no_hp, $id_akun);
}

// Eksekusi query
if ($stmt->execute()) {
    error_log("Profil berhasil diperbarui untuk id_akun=$id_akun");
    echo json_encode([
        'status' => 'success',
        'message' => 'Profil Anda berhasil diperbarui.'
    ]);
} else {
    error_log("Terjadi kesalahan saat memperbarui profil: " . $stmt->error);
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>