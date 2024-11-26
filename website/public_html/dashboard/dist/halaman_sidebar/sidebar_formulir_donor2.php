<?php
session_start();

// Periksa session
if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/Masuk.html");
    exit();
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

require_once dirname(__DIR__, levels: 5) . '/api/koneksi.php';

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

// Query untuk mengambil data formulir_donor berdasarkan id_akun dan informasi acara_donor
$sql = "
SELECT 
    fd.id, fd.alamat_lengkap, fd.golongan_darah, fd.berat_badan, 
    fd.lokasi_donor, fd.tanggal_input, 
    ad.lokasi AS acara_lokasi, ad.time_waktu, ad.tgl_acara, 
    a.nama_lengkap AS donor_nama
FROM 
    formulir_donor fd
JOIN 
    acara_donor ad ON fd.lokasi_donor = ad.lokasi
JOIN 
    akun a ON fd.id_akun = a.id_akun
WHERE 
    fd.id_akun = ?
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$resultdata = $stmt->get_result();

if ($resultdata->num_rows > 0) {
    $donor_data = $resultdata->fetch_all(MYSQLI_ASSOC); // Ambil semua data ke dalam array
} else {
    $donor_data = [];
}
$sql = "SELECT lokasi FROM acara_donor";
$resultdata = $conn->query($sql);
$stmt->close();
$conn->close();

// Fungsi untuk menghitung selisih waktu dan menampilkan tanggal
function formatWaktuAcara($waktu_acara) {
    $now = time(); // Waktu saat ini dalam format timestamp
    $acara_time = strtotime($waktu_acara); // Mengubah waktu acara menjadi timestamp

    // Menghitung selisih waktu dalam detik
    $selisih_detik = $acara_time - $now;

    // Memformat tanggal acara ke format yang mudah dibaca
    $formatted_date = date("d M Y, H:i", $acara_time); // Format: 25 Nov 2024, 10:00

    // Menambahkan keterangan "Hari ini" jika acara berlangsung hari ini
    if (date("Y-m-d", $acara_time) === date("Y-m-d", $now)) {
        $formatted_date .= " (Hari ini)";
    }
    
    // Jika acara sudah lewat, tampilkan "Saat ini"
    if ($selisih_detik <= 0) {
        return $formatted_date;
    }

    // Menghitung jumlah hari
    $selisih_hari = floor($selisih_detik / (60 * 60 * 24));

    // Jika acara lebih dari 1 hari, tampilkan "X hari lagi"
    if ($selisih_hari == 1) {
        return $formatted_date . " (1 hari lagi)";
    } elseif ($selisih_hari > 1) {
        return $formatted_date . " ($selisih_hari hari lagi)";
    }

    return $formatted_date; // Untuk acara yang sudah terjadi (past tense)
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
<div class="col s12 m12 l12 offset-l1" id="responsive-column" 
     style="padding-left: 15px; padding-right: 15px;">
      <div class="hero-title"><strong>Formulir Donor</strong></div>
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



    
      <form class="form-donor" action="<?php echo BASE_URL . '/api/website/send_formulir_donor.php'; ?>" method="POST" enctype="multipart/form-data" 
        style="background-color: white; padding: 30px; border-radius: 15px; border: 1px solid #d1d1d1; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); transition: transform 0.3s ease-in-out;">

    <!-- Row 1 -->
    <div class="form-row">
      <div class="form-group">
      <input type="hidden" name="id_akun" value="<?php echo $user['id_akun']; ?>">

    <label for="nama-lengkap">Nama Lengkap</label>
    <input type="text" 
           id="nama-lengkap" 
           name="nama_lengkap" 
           class="form-input" 
           placeholder="Masukkan nama lengkap" 
           style="outline: none; border: 2px solid #BE7171; background-color: #f9f9f9; color: #999; pointer-events: none;"

           value="<?php echo htmlspecialchars($user['nama_lengkap'] ?? ''); ?>" 
           readonly>
</div>

<div class="form-group">
    <label>No Telepon</label>
    <input 
        type="text" 
        name="no_hp_disabled" 
        value="<?php echo htmlspecialchars($user['no_hp']); ?>" 
        placeholder="Masukkan nomor telepon" 
        maxlength="14" 
        pattern="\d+" 
        required 
        title="Nomor telepon harus berupa angka"
        disabled
        style="outline: none; border: 2px solid #BE7171; background-color: #f9f9f9; color: #999; pointer-events: none;"
    >
    <!-- Input hidden untuk mengirimkan nomor telepon -->
    <input 
        type="hidden" 
        name="no_hp" 
        value="<?php echo htmlspecialchars($user['no_hp']); ?>">
</div>


<div class="form-group">
    <label for="tanggal-lahir" style="display: block;">Tanggal Lahir</label>
    <input 
        type="date" 
        id="tanggal-lahir" 
        name="tanggal_lahir" 
        class="form-input" 
        readonly
        style="outline: none; border: 2px solid #BE7171; background-color: #f9f9f9; color: #999; pointer-events: none;"
        value="<?php echo htmlspecialchars($user['tanggal_lahir'] ?? ''); ?>"
        min="1930-01-01" 
        max="<?php echo date('Y-m-d'); ?>" 
    >
</div>


    </div>

    <!-- Row 2 -->
    <div class="form-row">
        <div class="form-group form-group-wide">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" placeholder=""><?php echo htmlspecialchars($user['alamat'] ?? ''); ?></textarea>
            </div>
        <div class="form-column">
        <div class="form-group" style="position: relative; width: 100%;">
    <label>Golongan Darah</label>
    <input 
        id="golongan_darah" 
        name="golongan_darah" 
        placeholder="Pilih golongan darah..." 
        readonly 
        onclick="toggleDropdown()" 
        style="
            background-color: #f0f0f0;
            border: 2px solid #BE7171;
            border-radius: 8px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
            outline: none;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
            cursor: pointer;
        "
    />
    <div 
        id="dropdown" 
        style="
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #f0f0f0;
            border: 2px solid #BE7171;
            border-radius: 8px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            z-index: 10;
        "
    >
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('A+')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >A+</div>
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('A-')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >A-</div>
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('B+')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >B+</div>
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('B-')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >B-</div>
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('AB+')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >AB+</div>
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('AB-')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >AB-</div>
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('O+')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >O+</div>
        <div 
            style="padding: 4px; cursor: pointer;" 
            onclick="selectOption('O-')"
            onmouseover="highlightOption(this)"
            onmouseout="unhighlightOption(this)"
        >O-</div>
    </div>
</div>


<div class="form-group">
                <label>Berat Badan</label>
                <input type="number" name="berat_badan" placeholder="">
            </div>
        </div>
    </div>

    <!-- Full-width field -->
<!-- Form dengan datalist untuk Lokasi Donor -->
<div class="form-group" style="position: relative; width: 100%;">
    <label>Lokasi Donor</label>
    <input 
        id="lokasi_donor" 
        name="lokasi_donor" 
        placeholder="Pilih lokasi donor..." 
        readonly 
        onclick="toggleDropdownLokasi()" 
        style="
            background-color: #f0f0f0;
            border: 2px solid #BE7171;
            border-radius: 8px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
            outline: none;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease-in-out;
            cursor: pointer;
        "
    />
    <div 
        id="lokasi-dropdown" 
        style="
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #f0f0f0;
            border: 2px solid #BE7171;
            border-radius: 8px;
            padding: 8px;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
            box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1);
            z-index: 10;
        "
    >
        <?php
        // Menampilkan opsi lokasi
        if ($resultdata->num_rows > 0) {
            while ($row = $resultdata->fetch_assoc()) {
                echo '<div 
                        style="padding: 4px; cursor: pointer;" 
                        onclick="selectLokasi(\'' . htmlspecialchars($row['lokasi']) . '\')"
                        onmouseover="highlightOption(this)"
                        onmouseout="unhighlightOption(this)"
                      >' . htmlspecialchars($row['lokasi']) . '</div>';
            }
        } else {
            echo '<div 
                    style="padding: 4px; cursor: pointer;" 
                    onclick="selectLokasi(\'Tidak ada lokasi tersedia\')"
                    onmouseover="highlightOption(this)"
                    onmouseout="unhighlightOption(this)"
                  >Tidak ada lokasi tersedia</div>';
        }
        ?>
    </div>
</div>
<script>
    function toggleDropdown() {
        const dropdown = document.getElementById('dropdown');
        dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
    }

    function selectOption(value) {
        const input = document.getElementById('golongan_darah');
        input.value = value; // Set nilai input
        document.getElementById('dropdown').style.display = 'none'; // Sembunyikan dropdown
    }

    function highlightOption(element) {
        element.style.backgroundColor = '#df3232';
        element.style.color = '#fff';
        element.style.borderRadius = '4px';
    }

    function unhighlightOption(element) {
        element.style.backgroundColor = 'transparent';
        element.style.color = 'inherit';
    }

    // Menutup dropdown jika pengguna mengklik di luar elemen
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('dropdown');
        const input = document.getElementById('golongan_darah');
        if (!dropdown.contains(event.target) && event.target !== input) {
            dropdown.style.display = 'none';
        }
    });
</script>
<script>
    function toggleDropdownLokasi() {
        const dropdown = document.getElementById('lokasi-dropdown');
        dropdown.style.display = dropdown.style.display === 'none' || dropdown.style.display === '' ? 'block' : 'none';
    }

    function selectLokasi(value) {
        const input = document.getElementById('lokasi_donor');
        input.value = value; // Set nilai input
        document.getElementById('lokasi-dropdown').style.display = 'none'; // Sembunyikan dropdown
    }

    function highlightOption(element) {
        element.style.backgroundColor = '#df3232';
        element.style.color = '#fff';
        element.style.borderRadius = '4px';
    }

    function unhighlightOption(element) {
        element.style.backgroundColor = 'transparent';
        element.style.color = 'inherit';
    }

    // Menutup dropdown jika pengguna mengklik di luar elemen
    document.addEventListener('click', function (event) {
        const dropdown = document.getElementById('lokasi-dropdown');
        const input = document.getElementById('lokasi_donor');
        if (!dropdown.contains(event.target) && event.target !== input) {
            dropdown.style.display = 'none';
        }
    });
</script>


    <button type="submit" class="submit-btn">KIRIM</button>

<?php if (!empty($donor_data)): ?>
    <!-- Kontainer yang membungkus tabel untuk memberikan scroll -->
     
    <div class="table-container">
    <h1>Riwayat Pengisian Formulir Donor</h1>

        <table class="riwayat-donor">
            <thead>
                <tr>
                    <th>Nama Donor</th>
                    <th>Alamat Donor</th>
                    <th>Golongan Darah</th>
                    <th>Berat Badan</th>
                    <th>Lokasi Donor</th>
                    <th>Tanggal Donor</th>
                    <th>Waktu Acara</th>
                    <th>Lokasi Acara</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donor_data as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['donor_nama']); ?></td>
                        <td><?php echo htmlspecialchars($row['alamat_lengkap']); ?></td>
                        <td><?php echo htmlspecialchars($row['golongan_darah']); ?></td>
                        <td><?php echo htmlspecialchars($row['berat_badan']); ?> kg</td>
                        <td><?php echo htmlspecialchars($row['lokasi_donor']); ?></td>
                        <td><?php echo htmlspecialchars($row['tanggal_input']); ?></td>
                        <td>
                            <?php echo formatWaktuAcara($row['time_waktu']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($row['acara_lokasi']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>
    <h1>Anda Belum Mengisi Formulir Donor</h1>
<?php endif; ?>

    			</div>
                </form>
            <style>
                .main-content {
                    margin-left: 300px; /* Sesuaikan dengan lebar sidebar */
                    padding: 20px;
                }
        /* Styling untuk tabel */
.riwayat-donor {
    width: 100%;
    margin: 20px 0;
    border-collapse: collapse; /* Menggabungkan border antar sel */
}

/* Styling untuk setiap cell (td dan th) */
.riwayat-donor th, .riwayat-donor td {
    padding: 10px;
    text-align: left;
    border: 1px solid #ddd; /* Menambahkan border pada setiap kolom */
}

/* Styling untuk header tabel */
.riwayat-donor th {
    background-color: #c73f3f; /* Background merah untuk header */
    color: #fff; /* Warna teks putih */
    font-weight: bold; /* Membuat teks header tebal */
}

/* Styling untuk baris ganjil */
.riwayat-donor tr:nth-child(odd) {
    background-color: #f0f0f0; /* Background abu-abu muda untuk baris ganjil */
}

/* Styling untuk baris genap */
.riwayat-donor tr:nth-child(even) {
    background-color: #ffffff; /* Background putih untuk baris genap */
}

/* Styling tambahan jika diperlukan */
.riwayat-donor td {
    border: 1px solid #ddd; /* Border pada setiap cell */
}

/* Menambahkan styling untuk kontainer yang membungkus tabel agar responsif */
.table-container {
    width: 100%;
    overflow-x: auto; /* Membuat tabel dapat di-scroll secara horizontal */
    -webkit-overflow-scrolling: touch; /* Memberikan efek scroll yang lebih halus di perangkat iOS */
}

/* Styling untuk perangkat mobile (lebar layar <= 768px) */
@media (max-width: 768px) {
    .riwayat-donor th, .riwayat-donor td {
        padding: 8px; /* Mengurangi padding untuk ruang yang lebih kecil */
    }

    .riwayat-donor th {
        font-size: 14px; /* Menyesuaikan ukuran font di header */
    }

    .riwayat-donor td {
        font-size: 12px; /* Menyesuaikan ukuran font di sel */
    }
}

                .card-content p {
                    font-size: 1rem;
                }
        
                .hero-title {
                    font-size: 28px;
                    font-weight: bold;
                    margin-bottom: 1rem;
                    color: rgba(0, 0, 0, 0.65); /* Warna hitam dengan 65% transparansi */
                    margin-left: -30px;
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
                    gap: 60px;
                    margin-top: 50px;
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
                .form-donor input[type="text"],
                .form-donor input[type="date"],
                .form-donor input[type="number"],
                .form-donor textarea,
                .form-donor input[list] {
                    background-color: #f0f0f0;
                    border: 2px solid #BE7171; /* Border warna merah */
                    border-radius: 8px;
                    padding: 8px;
                    width: 100%;
                    box-sizing: border-box;
                    font-size: 14px;
                    outline: none; /* Hapus outline default */
                    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1); /* Shadow */
                    transition: box-shadow 0.3s ease-in-out; /* Animasi saat fokus */
                }

                .form-donor input[type="text"]:focus,
                .form-donor input[type="date"]:focus,
                .form-donor input[type="number"]:focus,
                .form-donor textarea:focus,
                .form-donor input[list]:focus {
                    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2); /* Shadow lebih besar saat fokus */
                }

                /* Datalist styling for better appearance */
                .form-donor input[list]::placeholder {
                    color: #999; /* Placeholder warna abu-abu */
                    font-style: italic;
                }
                                .form-donor input[type="text"],
                .form-donor input[type="date"],
                .form-donor input[type="number"],
                .form-donor textarea {
                    background-color: #f0f0f0;
                    border: 2px solid #BE7171; /* Border warna merah */
                    border-radius: 8px;
                    padding: 8px;
                    width: 100%;
                    box-sizing: border-box;
                    font-size: 14px;
                    outline: none; /* Hapus outline default */
                    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.1); /* Shadow */
                    transition: box-shadow 0.3s ease-in-out; /* Animasi saat fokus */
                }

                .form-donor input[type="text"]:focus,
                .form-donor input[type="date"]:focus,
                .form-donor input[type="number"]:focus,
                .form-donor textarea:focus {
                    box-shadow: 0 2px 3px rgba(0, 0, 0, 0.2); /* Shadow lebih besar saat fokus */
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