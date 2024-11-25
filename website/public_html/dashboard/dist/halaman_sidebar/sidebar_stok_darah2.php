<?php
// Menghubungkan ke database
require_once dirname(__DIR__, levels: 5) . '/api/koneksi.php';

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel stok_darah
$sql = "SELECT jenis_darah, goldar, rhesus, stok FROM stok_darah";
$result = $conn->query($sql);

// Menginisialisasi array untuk menyimpan stok darah berdasarkan jenis, golongan darah, dan rhesus
$stokDarah = [
    'WB' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'PRC' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'TC' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'FFP' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'Cryoprecipitate' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
];

// Memasukkan data hasil query ke dalam array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $jenis_darah = $row['jenis_darah'];
        $goldar = $row['goldar'];
        $rhesus = $row['rhesus'];
        $stokDarah[$jenis_darah][$goldar][$rhesus] += $row['stok'];
    }
}

$conn->close();

// Mengecek apakah ada stok darah yang kurang dari 20
$kebutuhanDarahList = [];
foreach ($stokDarah as $jenis_darah => $dataGolongan) {
    foreach ($dataGolongan as $golongan => $dataRhesus) {
        foreach ($dataRhesus as $rhesus => $stok) {
            if ($stok < 20) {
                $kebutuhanDarahList[] = [
                    'jenis_darah' => $jenis_darah,
                    'golongan' => $golongan,
                    'rhesus' => $rhesus,
                    'stok' => $stok
                ];
            }
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <div class="col s12 m12 l9 offset-10">
      <div class="hero-title"><strong>Stok Darah</strong></div>
      <div class="table-container">
    <table>
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Jenis Darah</th>
                <th colspan="8">Golongan</th>
            </tr>
            <tr>
                <th colspan="2">A</th>
                <th colspan="2">B</th>
                <th colspan="2">AB</th>
                <th colspan="2">O</th>
            </tr>
            <tr>
                <th></th>
                <th></th>
                <th>Rh (+)</th>
                <th>Rh (-)</th>
                <th>Rh (+)</th>
                <th>Rh (-)</th>
                <th>Rh (+)</th>
                <th>Rh (-)</th>
                <th>Rh (+)</th>
                <th>Rh (-)</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($stokDarah as $jenis_darah => $dataGolongan) {
                echo "<tr>";
                echo "<td>" . $no++ . "</td>";
                echo "<td>" . htmlspecialchars($jenis_darah) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['A']['positive']) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['A']['negative']) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['B']['positive']) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['B']['negative']) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['AB']['positive']) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['AB']['negative']) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['O']['positive']) . "</td>";
                echo "<td>" . htmlspecialchars($dataGolongan['O']['negative']) . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>



<div class="info-box">
    <p>INFORMASI KEBUTUHAN DARAH:</p>
    <?php if (!empty($kebutuhanDarahList)): ?>
        <p style="color: red;">
    Kebutuhan darah mendesak! Stok darah berikut ini sangat rendah:
</p>
        <ul>
            <?php foreach ($kebutuhanDarahList as $kebutuhan): ?>
                <li>
                    Jenis Darah: <?php echo htmlspecialchars($kebutuhan['jenis_darah']); ?>, 
                    Golongan: <?php echo htmlspecialchars($kebutuhan['golongan']); ?>, 
                    Rhesus: <?php echo htmlspecialchars($kebutuhan['rhesus']); ?>, 
                    Stok Tersedia: <?php echo htmlspecialchars($kebutuhan['stok']); ?>
                </li>
            <?php endforeach; ?>
        </ul>
        <p style="color: red;">
    Bantuan Anda sangat berarti! Mari bersama-sama memastikan setiap orang yang membutuhkan mendapatkan darah yang mereka perlukan. Ayo segera donor dan selamatkan nyawa!
</p>

    <?php else: ?>
        <p>
            Terima kasih kepada para pendonor! Stok darah saat ini mencukupi, tetapi kebutuhan darah tidak pernah berhenti. Anda tetap bisa membantu dengan berdonasi darah secara berkala.
        </p>
    <?php endif; ?>
</div>
			</div>
            <style>
                .main-content {
                    margin-left: 300px; /* Sesuaikan dengan lebar sidebar */
                    padding: 20px;
                }
        
                .card-content p {
                    font-size: 1.2rem;
                }
        
                .hero-title {
                    font-size: 28px;
                    font-weight: 1000;
                    margin-bottom: 3rem;
                    color: rgba(0, 0, 0, 0.65); /* Warna hitam dengan 65% transparansi */
                    margin-left: -30px;
                }
          /* Table Styling */
     .table-container {
         margin-bottom: 50px;
     }
     
     table {
         width: 120%;
         border-collapse: collapse;
         margin-bottom: 0px;
       
     }
     
     thead th {
         background-color: #C73F3F;
         color: white;
         padding: 5px;
         font-weight: bold;
         border: 2px solid #C73F3F;
     }
     
     tbody tr:nth-child(odd) {
    background-color: #f0f0f0; /* Warna abu-abu untuk baris ganjil */
    
}

tbody tr:nth-child(even) {
    background-color: #ffffff; /* Warna putih untuk baris genap */
}

th, td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ccc; /* Tambahkan garis pembatas antar sel */
}
     th, td {
         padding: 20px;
         text-align: center;
     }
     
     
     /* Info Box Styling */
     .info-box {
    background-color: #FFFFFF;
    border: 2px solid #be7070; /* Warna dan ketebalan border */
    border-radius: 10px;
    padding: 20px; /* Menyederhanakan padding untuk lebih rapi */
    color: black;
    text-align: left;
    font-weight: bold;
    display: flex;
    flex-direction: column; /* Membuat konten dalam satu kolom */
    align-items: flex-start;
    width: 120%; /* Mengatur lebar agar tidak melewati container */
    box-shadow: inset 0 0 10px rgba(207, 121, 121, 0.25);
    box-sizing: border-box; /* Memastikan padding termasuk dalam ukuran box */
}


                .divider {
                    margin: 20px 0;
                }
                .content {
                    padding: 40px;
                }
                h1 {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333;
                    margin-bottom: 30px;
                }
               .form-container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: #fff;
                    padding: 20px;
                    border-radius: 8px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
                }
        
                h1 {
                    text-align: center;
                    margin-bottom: 20px;
                    font-size: 24px;
                    color: #333;
                }
        
                form {
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                }
        
                .form-row {
                    display: flex;
                    gap: 20px;
                }
        
                .form-group {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                }
        
                .form-group-wide {
                    flex: 3;
                }
        
                .form-column {
                    display: flex;
                    flex-direction: column;
                    gap: 20px;
                    flex: 1;
                }
        
                .form-group-full {
                    width: 100%;
                    display: flex;
                    flex-direction: column;
                }
        
                .form-donor label {
                    font-size: 15px;
                    color: #333;
                    margin-bottom: 5px;
                }
        
                .form-donor input[type="text"],
                .form-donor input[type="date"],
                .form-donor input[type="number"],
                .form-donor textarea {
                    background-color: #f0f0f0;
                    border: none;
                    border-radius: 4px;
                    padding: 8px;
                    width: 100%;
                    box-sizing: border-box;
                    font-size: 14px;
                }
                .form-group-wide textarea {
                    height: calc(2 * 65px + 10px); /* Sama dengan tinggi dua field */
                }
        
                .form-group-wide {
                    flex: 1; /* Panjangkan kolom untuk mendekati kanan */
                }
        
                .form-column {
                    gap: 10px; /* Sesuaikan jarak antar field di kanan */
                }
        
                .form-row {
                    align-items: stretch; /* Pastikan semua elemen sejajar secara vertikal */
                }
                textarea {
                    resize: none;
                    height: 90px;
                }
        
                button.submit-btn {
                    width: 20%;
                    background-color: #d32f2f;
                    color: #fff;
                    padding: 10px;
                    border: none;
                    border-radius: 5px;
                    font-size: 16px;
                    cursor: pointer;
                    transition: background-color 0.3s;
                }
        
                button.submit-btn:hover {
                    background-color: #b71c1c;
                }
        
            @media (max-width: 768px) {
            .form-group-wide,
            .form-group,
            .form-group-full {
                width: 100%;
            }
        
            .form-row {
                flex-direction: column;
                gap: 10px; /* Tambahkan jarak antar field */
            }
        
            .form-donor input[type="text"],
            .form-donor input[type="date"],
            .form-donor input[type="number"],
            .form-donor textarea {
                width: 100%; /* Pastikan elemen memanfaatkan full width */
            }
        
            button.submit-btn {
                width: 100%; /* Tombol juga menyesuaikan dengan full width */
            }
        }
        strong {
    font-weight: 900;
}
            </style>

    
</body>
</html>