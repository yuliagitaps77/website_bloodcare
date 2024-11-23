<?php
session_start(); // Mulai session

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    // Jika belum login, arahkan ke halaman login
    header("Location: http://localhost/website_bloodcare/website/public_html/auth/masuk.php");
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

<?php
// Menghubungkan ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "bloodcarec3";

$conn = new mysqli($servername, $username, $password, $dbname);

// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel stok_darah
$sql = "SELECT jenis_darah, goldar, rhesus, stok FROM stok_darah";
$result_chart = $conn->query($sql);

// Menginisialisasi array untuk menyimpan stok darah berdasarkan jenis, golongan darah, dan rhesus
$stokDarah = [
    'WB' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'PRC' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'TC' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'FFP' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
    'Cryoprecipitate' => ['A' => ['positive' => 0, 'negative' => 0], 'B' => ['positive' => 0, 'negative' => 0], 'AB' => ['positive' => 0, 'negative' => 0], 'O' => ['positive' => 0, 'negative' => 0]],
];

// Memasukkan data hasil query ke dalam array
if ($result_chart->num_rows > 0) {
    while ($row = $result_chart->fetch_assoc()) {
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
        <!-- Divider (Garis Vertikal) -->
        <div style="width: 1px; height: 30px; background-color: white; margin: 0 15px;"></div>
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
          <img id="profile-picture-preview" style="width: 70px;
    height: 70px;
    border-radius: 50%;
    margin-bottom: 12px;
    object-fit: cover;" src="<?php echo htmlspecialchars($profile_picture); ?>" alt="Profile Picture" class="custom-profile-picture">
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
    const stokDarah = <?php echo json_encode($stokDarah); ?>;
</script>

  <script>
    // Fungsi untuk inisialisasi Chart.js
function initializeCharts() {
    console.log("Initializing charts...");

    if (!stokDarah) {
        console.error("Data stok darah tidak tersedia.");
        return;
    }

    // Labels untuk golongan darah
    const golonganLabels = ['A', 'B', 'AB', 'O'];
    const jenisDarahLabels = ['WB', 'PRC', 'TC', 'FFP', 'Cryoprecipitate'];
    const rhesusLabels = ['positive', 'negative'];

    // Hitung stok darah untuk setiap jenis darah dan rhesus
    const datasets = [];
    const colors = [
        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', // Warna untuk RH+
        '#FF9F40', '#C9CBCF', '#4D4D4D', '#E7E9ED', '#8C564B'  // Warna untuk RH-
    ];

    let colorIndex = 0; // Indeks warna

    jenisDarahLabels.forEach((jenis) => {
        rhesusLabels.forEach((rhesus) => {
            const data = golonganLabels.map((gol) => {
                let total = 0;
                if (stokDarah[jenis] && stokDarah[jenis][gol]) {
                    total = stokDarah[jenis][gol][rhesus];
                }
                return total;
            });

            datasets.push({
                label: `${jenis} RH${rhesus === 'positive' ? '+' : '-'}`,
                data: data,
                backgroundColor: colors[colorIndex++ % colors.length]
            });
        });
    });

    // Bar Chart: Menampilkan stok berdasarkan jenis darah dan rhesus
    const barCanvas = document.getElementById('barChart');
    if (barCanvas) {
        const ctxBar = barCanvas.getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: golonganLabels, // Golongan darah (A, B, AB, O)
                datasets: datasets // Dataset untuk setiap jenis darah dan rhesus
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                },
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    // Pie Chart tetap menggunakan total stok untuk setiap golongan darah
    const totalStok = golonganLabels.map((gol) => {
        return jenisDarahLabels.reduce((sum, jenis) => {
            let total = 0;
            for (const rhesus in stokDarah[jenis][gol]) {
                total += stokDarah[jenis][gol][rhesus];
            }
            return sum + total;
        }, 0);
    });

    const pieCanvas = document.getElementById('pieChart');
    if (pieCanvas) {
        const ctxPie = pieCanvas.getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: golonganLabels,
                datasets: [{
                    data: totalStok,
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top'
                    }
                }
            }
        });
    }

    // Notifikasi untuk stok darah kosong atau rendah
    const batasMinimum = 10; // Batas minimum stok darah
    const stokKosong = [];

    jenisDarahLabels.forEach((jenis) => {
        golonganLabels.forEach((gol) => {
            rhesusLabels.forEach((rhesus) => {
                const stok = stokDarah[jenis][gol][rhesus];
                if (stok < batasMinimum) {
                    stokKosong.push(`${jenis} ${gol} RH${rhesus === 'positive' ? '+' : '-'}`);
                }
            });
        });
    });

    // Cek apakah elemen notifikasi ada
    const notifikasiContainer = document.querySelector('#notifikasi');
    const notifikasiContent = document.querySelector('#notifikasi-content');

    if (notifikasiContainer && notifikasiContent) {
        if (stokKosong.length > 0) {
            notifikasiContent.innerHTML = `
                <ul>
                    ${stokKosong.map((item) => `<li>${item}</li>`).join('')}
                </ul>
            `;
        } else {
            notifikasiContainer.innerHTML = `
                <div class="card-content">
                    <span class="card-title" style="color: green; font-weight: bold;">
                        Semua stok darah mencukupi.
                    </span>
                </div>
            `;
        }
    } else {
        console.warn("Elemen notifikasi tidak ditemukan. Pastikan elemen dengan id 'notifikasi' tersedia di halaman.");
    }

}




    $(document).ready(function(){
        console.log("jQuery is loaded and ready!");

// Pilih semua item sidebar
const sidebarItems = $('.bordered');

// Fungsi untuk mengatur menu yang dipilih berdasarkan ID
function setActiveMenu(menuId) {
    // Hapus highlight dari semua item sidebar
    sidebarItems.removeClass('selected');

    // Tambahkan highlight hanya pada menu yang dipilih
    const activeItem = $(`#${menuId}`);
    if (activeItem.length > 0) {
        activeItem.addClass('selected');
    }
}

// Fungsi untuk memuat halaman berdasarkan menu ID
function loadPage(menuId) {
    let pageTitle = '';
    let pageUrl = '';

    // Tentukan judul halaman dan URL berdasarkan menu ID
    switch (menuId) {
        case 'dashboard':
            pageTitle = 'Dashboard';
            pageUrl = 'halaman_sidebar/sidebar_dashboard7.php';
            break;
        case 'acara-donor':
            pageTitle = 'Acara Donor';
            pageUrl = 'halaman_sidebar/sidebar_acara_donor.php';
            break;
        case 'formulir-donor':
            pageTitle = 'Formulir Donor';
            pageUrl = 'halaman_sidebar/sidebar_formulir_donor2.php';
            break;
        case 'stok-darah':
            pageTitle = 'Stok Darah';
            pageUrl = 'halaman_sidebar/sidebar_stok_darah2.php';
            break;
        case 'riwayat-donor':
            pageTitle = 'Riwayat Donor';
            pageUrl = 'halaman_sidebar/sidebar_riwayat_donor.php';
            break;
        default:
            pageTitle = 'Dashboard';
            pageUrl = 'halaman_sidebar/sidebar_dashboard7.php';
            break;
    }

    // Ubah judul halaman
    $('.hero-title').text(pageTitle);

    // Muat konten halaman
    $('.admin-content').load(pageUrl, function (response, status, xhr) {
        if (status === "error") {
            console.error(`Error loading ${menuId} page:`, xhr.status, xhr.statusText);
        } else {
            console.log(`${menuId} page loaded successfully.`);

            // Inisialisasi grafik jika halaman adalah Dashboard
            if (menuId === 'dashboard') {
                initializeCharts();
            }
        }
    });
}

// Ambil menu yang terakhir dipilih dari LocalStorage atau default ke Dashboard
const savedMenu = localStorage.getItem('activeMenu') || 'dashboard';

// Set menu yang aktif berdasarkan state yang disimpan
setActiveMenu(savedMenu);

// Muat halaman yang sesuai dengan state yang disimpan
loadPage(savedMenu);

// Tambahkan event listener untuk menyimpan state menu ke LocalStorage
sidebarItems.on('click', function () {
    const menuId = this.id;

    // Simpan menu yang dipilih ke LocalStorage
    localStorage.setItem('activeMenu', menuId);

    // Set menu yang dipilih
    setActiveMenu(menuId);

    // Muat halaman terkait
    loadPage(menuId);
});

// Klik pada menu Profil Saya
$('#profile-saya').click(function () {
    console.log("Profile Saya menu clicked.");
    $('.hero-title').text('Profil Saya');

    // Hapus highlight dari semua item di sidebar
    sidebarItems.removeClass('selected');

    // Muat konten halaman profil
    $('.admin-content').load('halaman_sidebar/sidebar_profile.php', function (response, status, xhr) {
        if (status === "error") {
            console.error("Error loading Profile page:", xhr.status, xhr.statusText);
        } else {
            console.log("Profile page loaded successfully.");
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
