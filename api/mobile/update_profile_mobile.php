<?php 
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'User not authenticated.'
    ]);
    exit();
}

require_once __DIR__ . '/../koneksi.php';

if ($conn->connect_error) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Database connection failed: ' . $conn->connect_error
    ]);
    exit();
}

// Ambil data dari POST request
$user_id = $_SESSION['user_id'];
$nama = $_POST['nama'] ?? null;
$alamat = $_POST['alamat'] ?? null;
$tanggal_lahir = $_POST['tanggal_lahir'] ?? null;
$no_hp = $_POST['no_hp'] ?? null;

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
    $query = "UPDATE akun SET nama_lengkap = ?, alamat = ?, tanggal_lahir = ?, no_hp = ?, profile_picture = ? WHERE id_akun = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query preparation failed: ' . $conn->error
        ]);
        exit();
    }
    $stmt->bind_param("sssssi", $nama, $alamat, $tanggal_lahir, $no_hp, $profile_picture_path, $user_id);
} else {
    // Update tanpa gambar
    $query = "UPDATE akun SET nama_lengkap = ?, alamat = ?, tanggal_lahir = ?, no_hp = ? WHERE id_akun = ?";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Database query preparation failed: ' . $conn->error
        ]);
        exit();
    }
    $stmt->bind_param("ssssi", $nama, $alamat, $tanggal_lahir, $no_hp, $user_id);
}

if ($stmt->execute()) {
    echo json_encode([
        'status' => 'success',
        'message' => 'Profil Anda berhasil diperbarui.'
    ]);
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memperbarui profil: ' . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
