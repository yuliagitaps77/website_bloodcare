<?php
session_start();

// Debug: Periksa apakah session tersedia
if (!isset($_SESSION['user_id'])) {
    echo "<p>Session tidak ditemukan. Pengguna belum login.</p>";
    header("Location: public_html/auth/Masuk.html");
    exit();
}

// Debug: Tampilkan ID pengguna dari session
$user_id = $_SESSION['user_id'];


// Konfigurasi koneksi database
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
$query = "SELECT * FROM akun WHERE id_akun = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    die("<p>Gagal mempersiapkan statement: " . $conn->error . "</p>");
}

$stmt->bind_param("i", $user_id);
$stmt->execute();

$result = $stmt->get_result();
if ($result->num_rows === 0) {
    die("<p>Pengguna dengan ID $user_id tidak ditemukan di database.</p>");
}

$user = $result->fetch_assoc();
if (!$user) {
    die("<p>Data pengguna gagal diambil.</p>");
}


$stmt->close();
$conn->close();
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
      <div class="hero-title"><strong>FORMULIR DONOR</strong></div>
      <div class="profile-card">
  <div class="custom-profile-header" id="profile-header">
        <img id="profile-picture-preview" src="https://via.placeholder.com/100" alt="Profile Picture" class="custom-profile-picture">
        <div class="custom-upload-overlay">Ganti Foto</div>
        <input type="file" id="profile-picture-input" class="custom-upload-input" accept="image/*">
    </div>
    <script>
        // Ketika profile-header diklik, buka file picker
        document.getElementById('profile-header').addEventListener('click', function() {
            document.getElementById('profile-picture-input').click();
        });

        // Ketika gambar dipilih, tampilkan pratinjau
        document.getElementById('profile-picture-input').addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profile-picture-preview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
    <form class="profile-form" action="http://localhost/website_bloodcare/api/website/update_profile.php" method="POST">
                <div class="form-group">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" class="form-input" placeholder="Masukkan nama" value="<?php echo htmlspecialchars($user['nama_lengkap'] ?? 'asd'); ?>">
                </div>

                <div class="form-group">
                    <label for="alamat">Alamat</label>
                    <input type="text" id="alamat" name="alamat" class="form-input" placeholder="Masukkan alamat" value="<?php echo htmlspecialchars($user['alamat'] ?? 'asd'); ?>">
                </div>

                <div class="form-group">
                    <label for="tanggal-lahir">Tanggal Lahir</label>
                    <input type="date" id="tanggal-lahir" name="tanggal_lahir" class="form-input" value="<?php echo htmlspecialchars($user['tanggal_lahir'] ?? ''); ?>">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" placeholder="Masukkan email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" readonly>
                </div>

                <div class="form-group">
                    <label for="nomor-telepon">Nomor Telepon</label>
                    <input type="tel" id="nomor-telepon" name="no_hp" class="form-input" placeholder="Masukkan nomor telepon" value="<?php echo htmlspecialchars($user['no_hp'] ?? ''); ?>">
                </div>

                <div class="form-buttons">
                    <button type="submit" class="btn-save">SIMPAN</button>
                </div>
            </form>
</div>
    
			</div>
            <style>
                /* General styling for the profile card */
                .custom-profile-header {
            width: 120px; /* Sesuaikan ukuran */
            height: 120px; /* Sesuaikan ukuran */
            border-radius: 50%; /* Membuat lingkaran */
            overflow: hidden; /* Crop gambar di luar border */
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f0f0f0; /* Placeholder background */
            cursor: pointer; /* Tampilkan kursor pointer */
        }

        .custom-profile-picture {
            width: 100%;
            height: 100%;
            object-fit: cover; /* Fit gambar agar center-crop */
            object-position: center; /* Fokus ke tengah gambar */
        }

        .custom-upload-input {
            display: none; /* Sembunyikan input file */
        }

        .custom-upload-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Overlay transparan */
            color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 14px;
            font-weight: bold;
            opacity: 0;
            transition: opacity 0.3s ease-in-out;
        }

        .custom-profile-header:hover .custom-upload-overlay {
            opacity: 1; /* Tampilkan overlay saat hover */
        }
.profile-card {
    width: 400%;
    max-width: 900px;
    margin: 10px auto;
    padding: 80px;
    border-radius: 15px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
    font-family: Arial, sans-serif;
}

/* Styling for profile header including picture */
.profile-header {
    text-align: center;
    margin-bottom: 20px;
}

.profile-picture {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    border: 3px solid #007bff;
}

/* Styling for form groups with label on left and input on right */
.form-group {
    display: inline-flex; /* Menggunakan inline-flex untuk membuat elemen fleksibel */
    align-items: center; /* Vertikal rata tengah */
    margin-bottom: 15px;
    width: 100%; /* Agar elemen tetap menyesuaikan dengan lebar kontainer */
    box-sizing: border-box; /* Pastikan padding dimasukkan dalam perhitungan lebar */
}

.profile-form label {
    flex: 0 0 150px; /* Lebar tetap untuk label */
    font-weight: bold;
    color: #333;
    margin-right: 10px; /* Spasi antara label dan textfield */
    text-align: left; /* Pastikan teks label rata kiri */
}

.profile-form .form-input {
    flex: 1; /* Input akan mengambil sisa ruang */
    width: 100%; /* Input akan mengisi penuh lebar kontainer */
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1em;
    box-sizing: border-box; /* Agar padding masuk hitungan width */
}

/* Styling for buttons */
.form-buttons {
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

.btn-save, .btn-edit {
    width: 20%;
    padding: 10px;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    font-weight: bold;
    transition: background-color 0.3s;
}

.btn-save {
    background-color: #880000;
    color: white;
    margin-left: 150px; /* Menggeser tombol ke kanan */
}

.btn-edit {
    background-color: #880000;
    color: white;
}

/* Button hover effects */
.btn-save:hover {
    background-color: #c42727;
}

.btn-edit:hover {
    background-color: #c42727;
}

                .main-content {
                    margin-left: 300px; /* Sesuaikan dengan lebar sidebar */
                    padding: 20px;
                }
        
                .card-content p {
                    font-size: 1.2rem;
                }
        
                .hero-title {
                    font-size: 2rem;
                    font-weight: bold;
                    margin-bottom: 1rem;
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
        
/* Responsif untuk layar kecil */
@media (max-width: 768px) {
    .form-group {
        flex-direction: column; /* Pada layar kecil, elemen menjadi vertikal */
        align-items: stretch; /* Input mengisi penuh */
    }

    .profile-form label {
        flex: 0 0 auto; /* Label mengikuti tinggi kontainer */
        margin-right: 0; /* Hapus spasi kanan */
        margin-bottom: 5px; /* Tambah spasi bawah untuk label */
    }

    .profile-form .form-input {
        width: 100%; /* Input memenuhi lebar penuh pada layar kecil */
    }
}

        
            </style>

    
</body>
</html>