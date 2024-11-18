<?php
session_start(); // Mulai session

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: /website_bloodcare/website/public_html/auth/Masuk.php");
    exit();
}

// Konfigurasi koneksi database
$host = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

$conn = new mysqli($host, $username, $password, $dbname);

// Periksa koneksi database
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Ambil data pengguna dari database berdasarkan session
$user_id = $_SESSION['user_id'];
$query = "SELECT nama_lengkap, profile_picture FROM akun WHERE id_akun = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id); // Bind parameter
$stmt->execute();
$result = $stmt->get_result();

// Ambil data pengguna
$user = $result->fetch_assoc();
$nama_lengkap = $user['nama_lengkap'] ?? "Nama tidak tersedia"; // Default jika nama_lengkap null
$base_url = "http://localhost/website_bloodcare/api/website/";
$profile_picture = !empty($user['profile_picture']) ? $base_url . $user['profile_picture'] : 'https://via.placeholder.com/100';

$stmt->close();
$conn->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DASHBOARD</title>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css'>
  <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons|Poppins'>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> <!-- SweetAlert2 CDN -->
  <link rel="stylesheet" href="./style.css">
  <style>
    body {
      padding-top: 70px; /* Sesuaikan dengan tinggi header untuk menghindari overlap */
    }
    .sidenav-fixed {
      top: 70px; /* Menambahkan offset agar sidebar berada tepat di bawah header */
    }
    header {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      background-color: #DF3232;
      padding: 8px 15px;
      border-bottom: 1px solid #ccc;
      z-index: 1000;
    }
  </style>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>

</head>
<body>
  <!-- Header Section -->
  <header>
    <div style="display: flex; align-items: center;">
      <img src="../dist/halaman_sidebar/logo bloodcare.png" alt="Logo" style="margin-right: 10px;">
      <span style="font-size: 24px; font-weight: 700; color: white;">BloodCare</span>
    </div>
  </header>
  
  <!-- Sidebar and Main Content -->
  <ul id="dropdown1" class="dropdown-content">
    <li><a href="#!"><i class="material-icons">build</i>Account Settings</a></li>
    <li><a href="#!"><i class="material-icons">logout</i>Logout</a></li>
  </ul>
  
  <div class="navbar-fixed hide-on-large-only show-on-medium-and-down">
    <nav class="colored">
      <div class="nav-wrapper">
        <ul class="right">
          <li>
            <a href="#" data-target="slide-out" class="sidenav-trigger"><i class="material-icons">menu</i></a>
          </li>
        </ul>
      </div>
    </nav>
  </div>
  
  <div class="row">
    <div class="col s12 m12 l3">
      <ul id="slide-out" class="sidenav sidenav-fixed sidebar-clear-parent">
        <li class="sidebar-clear"></li>
        <div class="profile-row">
          <!-- Profile Image -->
          <div class="profile-image">
          <img id="profile-picture-preview" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="custom-profile-picture">
          </div>
          <!-- Text Column with Header and Description -->
        <div class="profile-text">
            <!-- Menampilkan nama pengguna -->
            <p class="text-header"><?php echo htmlspecialchars($nama_lengkap); ?></p>
            <!-- Link ke lengkapi profil -->
            <a id="profile-saya" class="text-desc">Lengkapi profil Anda</a>
        </div>
        </div>
        
        <li id="dashboard" class="bordered selected"><a class="waves-effect" href="#!"><i class="material-icons">home</i>DASHBOARD</a></li>
        <li id="acara-donor" class="bordered"><a class="waves-effect" href="#!"><i class="material-icons">event</i>ACARA DONOR</a></li>
        <li id="formulir-donor" class="bordered"><a class="waves-effect" href="#!"><i class="material-icons">assignment</i>FORMULIR DONOR</a></li>
        <li id="stok-darah" class="bordered"><a class="waves-effect" href="#!"><i class="material-icons">invert_colors</i>STOK DARAH</a></li>
        <li id="riwayat-donor" class="bordered"><a class="waves-effect" href="#!"><i class="material-icons">history</i>RIWAYAT DONOR</a></li>
        <li id="keluar" class="bordered"><a class="waves-effect" href="#!"><i class="material-icons">exit_to_app</i>KELUAR</a></li>
      </ul>
    </div>
    
    <div class="container admin-content">
      <!-- Main Content -->
    </div>
  </div>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js'></script>
  <script src="./script.js"></script>
  <script>
    $(document).ready(function(){
        console.log("jQuery is loaded and ready!");
        // Klik pada menu Dashboard
        $('#profile-saya').click(function(){
            console.log("Dashboard menu clicked.");
            $('.hero-title').text('Dashboard');
            $('.admin-content').load('halaman_sidebar/sidebar_profile.php', function(response, status, xhr){
                if (status == "error") {
                    console.error("Error loading Dashboard page:", xhr.status, xhr.statusText);
                } else {
                    console.log("Dashboard page loaded successfully.");
                }
            });
        });

        $('#dashboard').click(function() {
    console.log("Dashboard menu clicked.");
    $('.hero-title').text('Dashboard');
    $('.admin-content').load('halaman_sidebar/sidebar_dashboard3.html', function(response, status, xhr) {
        if (status == "error") {
            console.error("Error loading Dashboard page:", xhr.status, xhr.statusText);
        } else {
            console.log("Dashboard page loaded successfully.");

            // Setelah halaman dimuat, jalankan kode untuk inisialisasi Chart.js
            initializeCharts();
        }
    });
});

// Fungsi untuk inisialisasi Chart.js
function initializeCharts() {
    console.log("Initializing charts...");

    // Cek apakah Chart.js berhasil dimuat
    if (typeof Chart === 'undefined') {
        console.error("Chart.js tidak ditemukan. Pastikan library Chart.js sudah dimuat.");
        return;
    }

    // Cek elemen canvas untuk Pie Chart
    const pieCanvas = document.getElementById('pieChart');
    if (!pieCanvas) {
        console.error("Canvas untuk Pie Chart tidak ditemukan. Pastikan ID 'pieChart' ada di halaman.");
        return;
    }

    // Cek elemen canvas untuk Bar Chart
    const barCanvas = document.getElementById('barChart');
    if (!barCanvas) {
        console.error("Canvas untuk Bar Chart tidak ditemukan. Pastikan ID 'barChart' ada di halaman.");
        return;
    }

    // Data untuk Pie Chart
    const pieData = {
        labels: ['Golongan A', 'Golongan B', 'Golongan AB', 'Golongan O'],
        datasets: [{
            data: [12, 19, 3, 5],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
        }]
    };

    // Inisialisasi Pie Chart
    try {
        const ctxPie = pieCanvas.getContext('2d');
        const pieChart = new Chart(ctxPie, {
            type: 'pie',
            data: pieData,
            options: {
                responsive: true
            }
        });
        console.log("Pie Chart berhasil dibuat:", pieChart);
    } catch (error) {
        console.error("Gagal membuat Pie Chart:", error);
    }

    // Data untuk Bar Chart
    const barData = {
        labels: ['Golongan A', 'Golongan B', 'Golongan AB', 'Golongan O'],
        datasets: [{
            label: 'Jumlah Stok Darah',
            data: [12, 19, 3, 5],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
        }]
    };

    // Inisialisasi Bar Chart
    try {
        const ctxBar = barCanvas.getContext('2d');
        const barChart = new Chart(ctxBar, {
            type: 'bar',
            data: barData,
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        console.log("Bar Chart berhasil dibuat:", barChart);
    } catch (error) {
        console.error("Gagal membuat Bar Chart:", error);
    }
}

        // Klik pada menu Acara Donor
        $('#acara-donor').click(function(){
            console.log("Acara Donor menu clicked.");
            $('.hero-title').text('Acara Donor');
            $('.admin-content').load('halaman_sidebar/sidebar_acara_donor.html', function(response, status, xhr){
                if (status == "error") {
                    console.error("Error loading Acara Donor page:", xhr.status, xhr.statusText);
                } else {
                    console.log("Acara Donor page loaded successfully.");
                }
            });
        });

        // Klik pada menu Formulir Donor
        $('#formulir-donor').click(function(){
            console.log("Formulir Donor menu clicked.");
            $('.hero-title').text('Formulir Donor');
            $('.admin-content').load('halaman_sidebar/sidebar_formulir_donor2.html', function(response, status, xhr){
                if (status == "error") {
                    console.error("Error loading Formulir Donor page:", xhr.status, xhr.statusText);
                } else {
                    console.log("Formulir Donor page loaded successfully.");
                }
            });
        });

        // Klik pada menu Stok Darah
        $('#stok-darah').click(function(){
            console.log("Stok Darah menu clicked.");
            $('.hero-title').text('Stok Darah');
            $('.admin-content').load('halaman_sidebar/sidebar_stok_darah2.html', function(response, status, xhr){
                if (status == "error") {
                    console.error("Error loading Stok Darah page:", xhr.status, xhr.statusText);
                } else {
                    console.log("Stok Darah page loaded successfully.");
                }
            });
        });

        // Klik pada menu Riwayat Donor
        $('#riwayat-donor').click(function(){
            console.log("Riwayat Donor menu clicked.");
            $('.hero-title').text('Riwayat Donor');
            $('.admin-content').load('halaman_sidebar/sidebar_riwayat_donor.html', function(response, status, xhr){
                if (status == "error") {
                    console.error("Error loading Riwayat Donor page:", xhr.status, xhr.statusText);
                } else {
                    console.log("Riwayat Donor page loaded successfully.");
                }
            });
        });
        // Klik pada menu Keluar
        $('#keluar').click(function (event) {
            event.preventDefault(); // Mencegah default behavior tombol

            // Tampilkan dialog konfirmasi menggunakan SweetAlert2
            Swal.fire({
                title: 'Anda yakin ingin keluar?',
                text: "Setelah keluar, Anda harus login kembali untuk mengakses akun.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, keluar!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Jika pengguna menekan tombol "Ya, keluar", panggil logout.php
                    $.ajax({
                        url: 'http://localhost/website_bloodcare/api/website/logout.php', // Path ke file PHP logout
                        type: 'POST',
                        success: function (response) {
                            const res = JSON.parse(response); // Parse respons JSON
                            if (res.success) {
                                // Tampilkan dialog sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil Keluar',
                                    text: 'Anda akan diarahkan ke halaman login.',
                                    showConfirmButton: false,
                                    timer: 2000,
                                    timerProgressBar: true
                                }).then(() => {
                                    // Redirect ke halaman login
                                    window.location.href = 'http://localhost/website_bloodcare/website/public_html/auth/Masuk.php'; // Path disesuaikan
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: 'Logout gagal. Silakan coba lagi.',
                                    confirmButtonText: 'OK'
                                });
                            }
                        },
                        error: function () {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Terjadi kesalahan pada server.',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                }
            });
        });


    });
</script>
</body>
</html>
