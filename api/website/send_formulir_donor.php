<?php
session_start();
require_once __DIR__ . '/../koneksi.php';

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/Masuk.html");
    exit();
}

// Periksa koneksi database
if ($conn->connect_error) {
    die("<p>Koneksi gagal: " . $conn->connect_error . "</p>");
}

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil id_akun dari form
    $id_akun = trim($_POST['id_akun'] ?? '');
    $alamat_lengkap = trim($_POST['alamat'] ?? '');
    $golongan_darah = trim($_POST['golongan_darah'] ?? '');
    $berat_badan = trim($_POST['berat_badan'] ?? '');
    $lokasi_donor = trim($_POST['lokasi_donor'] ?? '');

    // Daftar golongan darah yang valid
    $golongan_darah_valid = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    // Validasi data
    $errors = [];
    if (empty($alamat_lengkap)) {
        $errors[] = "Alamat lengkap tidak boleh kosong.";
    }
    if (empty($golongan_darah) || !in_array($golongan_darah, $golongan_darah_valid)) {
        $errors[] = "Golongan darah tidak valid. Pilih salah satu dari: " . implode(", ", $golongan_darah_valid);
    }
    if (empty($berat_badan) || !is_numeric($berat_badan) || $berat_badan <= 0) {
        $errors[] = "Berat badan harus berupa angka positif.";
    }
    if (empty($lokasi_donor)) {
        $errors[] = "Lokasi donor tidak boleh kosong.";
    }

    // Jika ada error, tampilkan pesan dan kembali ke halaman sebelumnya
    if (!empty($errors)) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan pada Formulir!',
                        html: '" . implode('<br>', $errors) . "',
                        showConfirmButton: true
                    }).then(() => {
                        history.back();
                    });
                });
            </script>
        </body>
        </html>
        ";
        exit();
    }

    // Query untuk mengambil data pengguna berdasarkan id_akun
    $query_user = "SELECT nama_lengkap, no_hp, tanggal_lahir FROM akun WHERE id_akun = ?";
    $stmt_user = $conn->prepare($query_user);
    if (!$stmt_user) {
        die("<p>Gagal mempersiapkan statement: " . $conn->error . "</p>");
    }
    $stmt_user->bind_param("i", $id_akun);
    $stmt_user->execute();
    $result_user = $stmt_user->get_result();
    if ($result_user->num_rows === 0) {
        die("<p>Pengguna tidak ditemukan di database.</p>");
    }
    $user = $result_user->fetch_assoc();
    $stmt_user->close();

    // Query untuk memasukkan data ke tabel formulir_donor
    $query_insert = "INSERT INTO formulir_donor (alamat_lengkap, golongan_darah, berat_badan, lokasi_donor, id_akun) 
                     VALUES (?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($query_insert);

    if (!$stmt_insert) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Kesalahan Sistem',
                        text: 'Gagal mempersiapkan statement database.',
                        showConfirmButton: true
                    }).then(() => {
                        history.back();
                    });
                });
            </script>
        </body>
        </html>
        ";
        exit();
    }

    // Bind parameter dan eksekusi statement
    $stmt_insert->bind_param(
        "sssss",
        $alamat_lengkap,
        $golongan_darah,
        $berat_badan,
        $lokasi_donor,
        $id_akun
    );

    if ($stmt_insert->execute()) {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Success</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Formulir berhasil dikirim!',
                        text: 'Terima kasih telah berpartisipasi.',
                        timer: 3000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = '" . BASE_URL . "/website/public_html/dashboard/dist/index.php#!';
                    });
                });
            </script>
        </body>
        </html>
        ";
    } else {
        echo "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Error</title>
            <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
        </head>
        <body>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal mengirim data!',
                        text: 'Terjadi kesalahan saat menyimpan ke database. Silakan coba lagi.',
                        showConfirmButton: true
                    }).then(() => {
                        history.back();
                    });
                });
            </script>
        </body>
        </html>
        ";
    }

    $stmt_insert->close();
}

$conn->close();
?>
