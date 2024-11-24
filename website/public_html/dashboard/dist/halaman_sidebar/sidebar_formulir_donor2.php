<?php
session_start();

// Periksa session
if (!isset($_SESSION['user_id'])) {
    header("Location: public_html/auth/Masuk.html");
    exit();
}

// Ambil ID pengguna dari session
$user_id = $_SESSION['user_id'];

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

// Ambil data pengguna dari database
$query = "SELECT nama_lengkap, alamat, profile_picture, tanggal_lahir,email, no_hp FROM akun WHERE id_akun = ?";
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
// Query untuk mengambil data lokasi dari tabel acara_Donor
$sql = "SELECT lokasi FROM acara_Donor";
$resultdata = $conn->query($sql);

$conn->close();

$base_url = "http://localhost/website_bloodcare/api/website/";
$profile_picture = !empty($user['profile_picture']) ? $base_url . $user['profile_picture'] : 'https://via.placeholder.com/100';
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
      <div class="hero-title"><strong>Formulir Donor</strong></div>

      <form class="form-donor" action="http://localhost/website_bloodcare/api/website/send_formulir_donor.php" method="POST">

    <!-- Row 1 -->
    <div class="form-row">
      <div class="form-group">
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


<div class="form-group" >
    <label for="tanggal-lahir" style="display: block;">Tanggal Lahir</label>
    <input 
        type="date" 
        id="tanggal-lahir" 
        name="tanggal_lahir" 
        class="form-input" 
        disabled
        style="outline: none; border: 2px solid #BE7171; background-color: #f9f9f9; color: #999; pointer-events: none;"

        value="<?php echo htmlspecialchars($user['tanggal_lahir'] ?? ''); ?>" 
    >
</div>

    </div>

    <!-- Row 2 -->
    <div class="form-row">
        <div class="form-group form-group-wide">
            <label>Alamat Lengkap</label>
            <textarea name="alamat" placeholder=""><?php echo htmlspecialchars($user['alamat']); ?></textarea>
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
</f>
    			</div>
            <style>
                .main-content {
                    margin-left: 300px; /* Sesuaikan dengan lebar sidebar */
                    padding: 20px;
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