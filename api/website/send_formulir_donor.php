<?php
session_start();

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/Masuk.html");
    exit();
}

// Konfigurasi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi database
if ($conn->connect_error) {
    die("<p>Koneksi gagal: " . $conn->connect_error . "</p>");
}

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data dari form
    $nama_lengkap = trim($_POST['nama_lengkap'] ?? '');
    $no_telepon = trim($_POST['no_hp'] ?? ''); // Ambil dari input hidden
    $tanggal_lahir = trim($_POST['tanggal_lahir'] ?? '');
    $alamat_lengkap = trim($_POST['alamat'] ?? '');
    $golongan_darah = trim($_POST['golongan_darah'] ?? '');
    $berat_badan = trim($_POST['berat_badan'] ?? '');
    $lokasi_donor = trim(string: $_POST['lokasi_donor'] ?? '');

    // Daftar golongan darah yang valid
    $golongan_darah_valid = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];

    // Validasi data
    $errors = [];
    if (empty($nama_lengkap)) {
        $errors[] = "Nama lengkap tidak boleh kosong.";
    }
    if (empty($no_telepon)) {
        $errors[] = "Nomor telepon tidak boleh kosong.";
    }
    if (empty($tanggal_lahir)) {
        $errors[] = "Tanggal lahir tidak boleh kosong.";
    }
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

    // Query untuk memasukkan data ke tabel
    $query = "INSERT INTO formulir_donor (nama_lengkap, no_telepon, tanggal_lahir, alamat_lengkap, golongan_darah, berat_badan, lokasi_donor) 
    VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($query);

    if (!$stmt) {
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
    $stmt->bind_param(
        "sssssss",
        $nama_lengkap,
        $no_telepon,
        $tanggal_lahir,
        $alamat_lengkap,
        $golongan_darah,
        $berat_badan,
        $lokasi_donor
    );

    if ($stmt->execute()) {
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
                    window.location.href = 'http://localhost/website_bloodcare/website/public_html/dashboard/dist/index.php';
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

    $stmt->close();
}

$conn->close();
?>
