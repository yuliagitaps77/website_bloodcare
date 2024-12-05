<?php 
session_start();
require_once __DIR__ . '/../koneksi.php';

// Definisikan BASE_URL jika belum didefinisikan
if (!defined('BASE_URL')) {
    define('BASE_URL', 'http://yourdomain.com'); // Ganti dengan URL dasar Anda
}

// Periksa apakah user sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/masuk.php");
    exit();
}

// Periksa koneksi database
if ($conn->connect_error) {
    die("<p>Koneksi gagal: " . $conn->connect_error . "</p>");
}

// Periksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil id_akun dari session (lebih aman daripada dari POST)
    $id_akun = $_SESSION['user_id'];
    $alamat_lengkap = trim($_POST['alamat'] ?? '');
    $golongan_darah = trim($_POST['golongan_darah'] ?? '');
    $berat_badan = trim($_POST['berat_badan'] ?? '');
    $lokasi_donor = trim($_POST['lokasi_donor'] ?? '');

    // Daftar golongan darah yang valid
    $golongan_darah_valid = ['A', 'B', 'AB', 'O'];

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

    // Mulai transaksi
    $conn->begin_transaction();

    try {
        // Query untuk memeriksa jumlah formulir donor yang sudah dikirim oleh user
        $query_count = "SELECT COUNT(*) AS total_donor FROM formulir_donor WHERE id_akun = ?";
        $stmt_count = $conn->prepare($query_count);
        if (!$stmt_count) {
            throw new Exception("Gagal mempersiapkan statement count: " . $conn->error);
        }
        $stmt_count->bind_param("i", $id_akun);
        $stmt_count->execute();
        $result_count = $stmt_count->get_result();
        $row_count = $result_count->fetch_assoc();
        $stmt_count->close();

        // Jika sudah lebih dari 2 formulir, tampilkan pesan error dan hentikan eksekusi
        if ($row_count['total_donor'] >= 2) {
            throw new Exception("Anda hanya dapat mengisi formulir donor sebanyak 2 kali.");
        }

        // Query untuk mengambil data pengguna berdasarkan id_akun
        $query_user = "SELECT nama_lengkap, no_hp, tanggal_lahir FROM akun WHERE id_akun = ?";
        $stmt_user = $conn->prepare($query_user);
        if (!$stmt_user) {
            throw new Exception("Gagal mempersiapkan statement user: " . $conn->error);
        }
        $stmt_user->bind_param("i", $id_akun);
        $stmt_user->execute();
        $result_user = $stmt_user->get_result();
        if ($result_user->num_rows === 0) {
            throw new Exception("Pengguna tidak ditemukan di database.");
        }
        $user = $result_user->fetch_assoc();
        $stmt_user->close();

        // Query untuk memasukkan data ke tabel formulir_donor
        $query_insert_donor = "INSERT INTO formulir_donor (alamat_lengkap, golongan_darah, berat_badan, lokasi_donor, id_akun) 
                               VALUES (?, ?, ?, ?, ?)";
        $stmt_insert_donor = $conn->prepare($query_insert_donor);

        if (!$stmt_insert_donor) {
            throw new Exception("Gagal mempersiapkan statement formulir_donor: " . $conn->error);
        }

        // Bind parameter dan eksekusi statement
        $stmt_insert_donor->bind_param(
            "ssdsi",
            $alamat_lengkap,
            $golongan_darah,
            $berat_badan,
            $lokasi_donor,
            $id_akun
        );

        if (!$stmt_insert_donor->execute()) {
            throw new Exception("Gagal mengirim data ke formulir_donor: " . $stmt_insert_donor->error);
        }

        $stmt_insert_donor->close();

        // Query untuk mendapatkan tgl_acara dari acara_donor berdasarkan lokasi_donor menggunakan LIKE
        // Pastikan bahwa ada acara_donor dengan lokasi yang sesuai dan tgl_acara >= CURDATE()
        $query_tgl_acara = "SELECT tgl_acara FROM acara_donor 
                            WHERE lokasi LIKE CONCAT('%', ?, '%') 
                            ORDER BY tgl_acara ASC 
                            LIMIT 1";
        $stmt_tgl_acara = $conn->prepare($query_tgl_acara);
        if (!$stmt_tgl_acara) {
            throw new Exception("Gagal mempersiapkan statement tgl_acara: " . $conn->error);
        }
        $stmt_tgl_acara->bind_param("s", $lokasi_donor);
        $stmt_tgl_acara->execute();
        $result_tgl_acara = $stmt_tgl_acara->get_result();

        if ($result_tgl_acara->num_rows === 0) {
            throw new Exception("Tidak ditemukan acara donor untuk lokasi tersebut atau acara sudah berlalu.");
        }

        $acara = $result_tgl_acara->fetch_assoc();
        $tgl_acara = $acara['tgl_acara'];

        $stmt_tgl_acara->close();

        // Query untuk memasukkan data ke tabel data_pendonor
        $query_insert_pendonor = "INSERT INTO data_pendonor 
            (id_akun, nama_pendonor, no_telp, tanggal_lahir, alamat, lokasi_donor, berat_badan, goldar, tgl_acara) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt_insert_pendonor = $conn->prepare($query_insert_pendonor);

        if (!$stmt_insert_pendonor) {
            throw new Exception("Gagal mempersiapkan statement data_pendonor: " . $conn->error);
        }

        // Bind parameter dan eksekusi statement
        $stmt_insert_pendonor->bind_param(
            "issssssss",
            $id_akun,
            $user['nama_lengkap'],
            $user['no_hp'],
            $user['tanggal_lahir'],
            $alamat_lengkap,
            $lokasi_donor,
            $berat_badan,
            $golongan_darah,
            $tgl_acara
        );

        if (!$stmt_insert_pendonor->execute()) {
            throw new Exception("Gagal mengirim data ke data_pendonor: " . $stmt_insert_pendonor->error);
        }

        $stmt_insert_pendonor->close();

        // Commit transaksi
        $conn->commit();

        // Tampilkan pesan sukses
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
    } catch (Exception $e) {
        // Rollback transaksi jika terjadi kesalahan
        $conn->rollback();

        // Tampilkan pesan error
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
                        text: '" . addslashes($e->getMessage()) . "',
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
}

$conn->close();
?>
