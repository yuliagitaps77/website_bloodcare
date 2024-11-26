<?php
// Menghubungkan ke database
require_once dirname(__DIR__, levels: 5) . '/api/koneksi.php';


// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel acara_donor
$sql = "SELECT tgl_acara, lokasi, fasilitas FROM acara_donor";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Materialize CSS -->
</head>
<body>
<div class="col s12 m12 l12 offset-l1" id="responsive-column" 
     style="padding-left: 15px; padding-right: 15px;">
      
        <div class="card-content">
            <h6 class="card-title">Acara Donor</h6>
            <div class="jadwal-acara-donor">
            <table class="jadwal-tabel">
                    <thead>
                        <tr>
                            <th colspan="3">
                                <div class="thead-content">
                                    <span>Tanggal</span>
                                    <span>Tempat</span>
                                    <span>Fasilitas</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($result->num_rows > 0) {
                            // Output data per baris
                            while($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . date("d F Y", strtotime($row["tgl_acara"])) . "</td>";
                                echo "<td>" . htmlspecialchars($row["lokasi"]) . "</td>";
                                echo "<td>" . htmlspecialchars($row["fasilitas"]) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='3'>Tidak ada data tersedia</td></tr>";
                        }
                        $conn->close();
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
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




      <style>
        .hero-title {
            .hero-title {
    font-size: 24px;
    font-family: "Poppins", serif;
    font-weight: 900; /* Menjadikan teks sangat bold */
    margin-top: 30px;
    color: rgba(0, 0, 0, 0.65); /* Warna hitam dengan transparansi 65% */
}
 
        }
        .card-content {
    padding: 20px;
}

.card-title {
    font-size: 28px;
    font-weight: 900;
    color: rgba(0, 0, 0, 0.65); /* Warna hitam dengan 65% transparansi */
    margin-left: -30px;
}

.jadwal-tabel {
    width: 100%; /* Pastikan tabel mengisi kontainer */
    max-width: 100%; /* Hindari overflow */
    margin: 0 auto; /* Tengah-kan tabel jika perlu */
    border-collapse: collapse;
    border-spacing: 0; /* Hindari jarak tambahan antar sel */
    border: 2px solid #C8C8C8; /* Menambahkan border box di seluruh tabel */
    box-shadow: 0 5 px rgba(0, 0, 0, 0.1); /* Menambahkan efek shadow */
}


.jadwal-tabel thead th {
    background-color: #DF3232; /* Warna merah header */
    color: #FFFFFF; /* Warna teks putih */
    border-radius: 5px;
    padding: 15px;
    text-align: center;
    font-weight: bold;
    border: none;
    font-size: 1em; /* Ukuran font header */
}

.thead-content {
    display: flex;
    justify-content: space-around; /* Memberikan jarak yang merata di antara elemen */
    align-items: center;
}

.thead-content span {
    flex: 1;
    text-align: center; /* Memastikan setiap teks berada di tengah kolomnya */
}


.jadwal-tabel tbody tr:nth-child(odd) {
    background-color: #FFFFFF; /* Baris ganjil berwarna putih */
}

.jadwal-tabel tbody tr:nth-child(even) {
    background-color: #F5F5F5; /* Baris genap berwarna abu-abu muda */
}
.jadwal-tabel th,
.jadwal-tabel td {
    width: 33.33%; /* Setiap kolom mendapat lebar sama */
}
.jadwal-tabel tbody tr td {
    border-bottom: 1px solid #746464; /* Garis merah di bagian bawah setiap baris */
    padding: 20px;
    text-align: center;
}
.grey.lighten-3 {
    background-color: rgb(255, 255, 255) !important;
}
.jadwal-tabel thead th,
.jadwal-tabel tbody td {
    padding: 15px; /* Ruang dalam */
    text-align: center; /* Teks tengah */
}

.jadwal-tabel thead th {
    background-color: #DF3232; /* Warna merah header */
    color: #FFFFFF; /* Warna teks putih */
    font-weight: bold;
}

.jadwal-tabel tbody td {
    border-bottom: 1px solid #746464; /* Garis bawah */
}

     </style>
</body>
</html>
