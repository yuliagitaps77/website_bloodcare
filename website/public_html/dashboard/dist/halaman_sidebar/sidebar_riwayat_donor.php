<?php
session_start();

// Periksa session
if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/Masuk.html");
    exit();
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

require_once dirname(__DIR__, 5) . '/api/koneksi.php';

// Periksa koneksi database
if ($conn->connect_error) {
    die("<p>Koneksi gagal: " . $conn->connect_error . "</p>");
}

// Ambil data pengguna dari database
$query = "SELECT id_akun, nama_lengkap, alamat, profile_picture, tanggal_lahir, email, no_hp FROM akun WHERE id_akun = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("<p>Gagal mempersiapkan statement: " . $conn->error . "</p>");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("<p>Pengguna tidak ditemukan di database.</p>");
}

$user = $result->fetch_assoc();
$stmt->close();

// Ambil data sertifikat dan acara donor
$query_sertifikat = "
    SELECT s.id_sertifikat, s.link_sertifikat, s.alasan, a.tgl_acara, a.time_waktu, a.lokasi, a.time_waktu AS waktu_donor
    FROM sertifikat s
    JOIN acara_donor a ON s.id_acara_donor = a.id_acara
    WHERE s.id_akun = ?
";
$stmt_sertifikat = $conn->prepare($query_sertifikat);
if (!$stmt_sertifikat) {
    die("<p>Gagal mempersiapkan statement: " . $conn->error . "</p>");
}

$stmt_sertifikat->bind_param("i", $user_id);
$stmt_sertifikat->execute();

$result_sertifikat = $stmt_sertifikat->get_result();
$stmt_sertifikat->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
           <body>
				<div class="container admin-content">
				<div class="col s12 m12 l12 offset-l1" id="responsive-column" 
				style="padding-left: 15px; padding-right: 15px;">				
				<div class="hero-title">Riwayat Donor</div>
	
						<div class="content" id="content">
						<script>
    // Fungsi untuk menyesuaikan margin dan padding elemen
    function adjustColumn() {
        var width = window.innerWidth;  // Mendapatkan lebar layar
        var column = document.getElementById('responsive-column');  // Mendapatkan elemen kolom

        if (width < 600) {
            // Untuk layar kecil (mobile)
            column.style.marginLeft = '10px'; // Margin kiri untuk mobile
            column.style.paddingLeft = '10px';  // Padding kiri untuk mobile
            column.style.paddingRight = '10px'; // Padding kanan untuk mobile
        } else if (width >= 600 && width < 1024) {
            // Untuk layar medium (tablet)
            column.style.marginLeft = '20px'; // Margin kiri untuk tablet
            column.style.paddingLeft = '20px';  // Padding kiri untuk tablet
            column.style.paddingRight = '20px'; // Padding kanan untuk tablet
        } else {
            // Untuk layar besar (desktop)
            column.style.marginLeft = '140px'; // Margin kiri untuk desktop
            column.style.paddingLeft = '40px';  // Padding kiri untuk desktop
            column.style.paddingRight = '40px'; // Padding kanan untuk desktop
        }
    }

    // Menjalankan fungsi saat ukuran layar berubah
    window.addEventListener('resize', adjustColumn);

    // Menjalankan fungsi saat halaman pertama kali dimuat
    window.addEventListener('load', adjustColumn);
</script>
<table class="riwayat-donor">
<?php 
// Pastikan BASE_URL sudah didefinisikan sebelumnya, seperti di contoh sebelumnya

if ($result_sertifikat->num_rows > 0) {
    // Loop untuk menampilkan data sertifikat dalam format tabel
    while ($row = $result_sertifikat->fetch_assoc()) {
        // Ambil path relatif sertifikat dari database
        $path_sertifikat = $row['link_sertifikat']; // Misalnya 'api/sertifikat/filename.png'
        
        // Gabungkan BASE_URL dengan path relatif untuk mendapatkan link lengkap
        $full_link_sertifikat = BASE_URL . '/' . $path_sertifikat;
        
        // Mengambil nama lengkap pengguna
        $nama_lengkap = htmlspecialchars($user['nama_lengkap']); // Ambil nama lengkap dari data pengguna

        // Cek apakah alasan kosong atau tidak
        $alasan = $row['alasan'];
        $status_donor = ($alasan === null || $alasan === '') ? 'Berhasil' : 'Ditolak';

        // Ambil tanggal dan waktu acara
        $waktu_donor = new DateTime($row['waktu_donor']);
        $tgl_donor = $waktu_donor->format('d M Y, H:i');
        $waktu_acara = new DateTime($row['waktu_donor']);
        $waktu_acara_end = clone $waktu_acara;
        $waktu_acara_end->add(new DateInterval('PT2H')); // Asumsikan durasi acara 2 jam
        $lokasi_donor = htmlspecialchars($row['lokasi']); // Ambil lokasi dari data acara donor

        // Format pesan berdasarkan status donor
        if ($status_donor == 'Berhasil') {
            $pesan_status = "[{$tgl_donor} - {$waktu_acara_end->format('H:i')}] Anda telah berhasil mendonorkan darah di {$lokasi_donor}. Status: ✅ Sukses.";

        } else {
            $pesan_status = "[{$tgl_donor} - {$waktu_acara_end->format('H:i')}] Anda mencoba mendonorkan darah di {$lokasi_donor}. Status: ❌ Ditolak (Alasan: {$alasan}).";
        }

        ?>
        
        <!-- Tabel untuk menampilkan nama lengkap dan tombol unduh sertifikat -->
        <table class="riwayat-donor">
            <tr>
                <td class="nama-unduh-container">
                    <!-- Menampilkan pesan status donor -->
                    <p><?php echo $pesan_status; ?></p> <!-- Menampilkan pesan status donor -->
                    <!-- Tombol Unduh Sertifikat -->
                    <?php if ($status_donor == 'Berhasil') { ?>
                        <a href="<?php echo $full_link_sertifikat; ?>" class="btn-unduh" target="_blank">Unduh Sertifikat</a>
                    <?php } ?>
                </td>
            </tr>
        </table>

        <?php
    }
} else {
    echo "<p>Belum ada sertifikat yang tersedia.</p>";
}
?>



							</table>

						</div>
					</div>
					
		<style>
		  .hero-title {
    font-size: 28px;
    font-family: "Poppins", serif;
    font-weight: 900; /* Menjadikan teks sangat bold */
    margin-top: 30px;
    color: rgba(0, 0, 0, 0.65); /* Warna hitam dengan transparansi 65% */
}
	

	strong {
		font-weight: 500;
	}
.riwayat-donor {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse;
}


.riwayat-donor td {
    padding: 10px;
    background-color: #F5F5F5;
    text-align: right; /* Ini akan memastikan tombol di sebelah kanan */
    border-radius: 15px;
    box-shadow: inset 0 0 0 1px rgba(0, 0, 0, 0.35);
}

.riwayat-donor .btn-unduh {
    display: inline-block;
    padding: 7px 10px;
    background-color: #C73F3F;
    color: white;
    text-decoration: none;
    border-radius: 10px;
    text-align: center;
}

.nama-unduh-container {
    display: flex;
    justify-content: space-between; /* Memisahkan elemen kiri dan kanan */
    align-items: center; /* Menyelaraskan elemen secara vertikal */
}

.btn-unduh {
    background-color: #4CAF50;
    color: white;
    padding: 8px 16px;
    text-decoration: none;
    border-radius: 4px;
    font-size: 14px;
}

.btn-unduh:hover {
    background-color: #45a049;
}

.riwayat-donor .btn-unduh:hover {
    background-color: #FF3D3D;
}

@media (max-width: 768px) {
    /* Untuk layar kecil, tampilkan tabel dalam bentuk stack */
    .riwayat-donor {
        border-collapse: separate;
        border-spacing: 0 10px; /* Menambahkan jarak antar baris */
    }

    .riwayat-donor td {
        display: block;
        width: 100%; /* Memperluas sel tabel ke seluruh lebar */
        text-align: center; /* Meratakan teks dan tombol di tengah */
        border-bottom: none; /* Menghapus border bawah */
        margin-bottom: 10px; /* Memberi jarak antar baris */
    }

    .riwayat-donor .btn-unduh {
        width: 100%; /* Tombol akan memiliki lebar penuh */
        margin: 10px 0 0; /* Menambahkan jarak di atas dan bawah tombol */
        padding: 10px; /* Menambah padding agar lebih besar */
        font-size: 14px; /* Menyesuaikan ukuran font */
    }
}
                    
	</style>
		</body>
		</html>
				
						