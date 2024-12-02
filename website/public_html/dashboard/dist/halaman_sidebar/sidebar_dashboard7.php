
<?php
require_once dirname(__DIR__, levels: 5) . '/api/koneksi.php';


// Mengecek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel acara_donor
$sql = "SELECT tgl_acara, lokasi, fasilitas FROM acara_donor";
$resultdata = $conn->query($sql);
?>
            <style>
                #notifikasi-content ul {
                    padding-left: 20px;
                    margin: 0;
                }

                #notifikasi-content li {
                    list-style-type: disc;
                    margin-bottom: 8px;
                    color: red;
                    font-weight: bold;
                }
                
            </style>
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Dashboard</title>
                <!-- Materialize CSS -->

            </head>
            <body>
                
                <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
                <script>
                // Mengonversi data PHP ke format JSON untuk digunakan di JavaScript
                const stokDarah = <?php echo json_encode($stokDarah); ?>;
                console.log("Data stok darah dari PHP:", stokDarah);
            </script>

            <div class="col s12 m12 l12 offset-l1" id="responsive-column" 
                style="padding-left: 15px; padding-right: 15px;">
                    <div class="content">
                    </div>
				<div class="hero-title"><strong>Beranda</strong></div>
				 <!-- Donation Call-to-Action Card -->
                    <div style="display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; margin-left: 60px;">
                        <!-- Kolom untuk Teks dan Tombol -->
                        <div style="flex: 1; min-width: 250px; display: flex; flex-direction: column; align-items: flex-start;">
                            <h5 style="margin: 0; margin-bottom: 10px;">DONORKAN DARAHMU UNTUK</h5>
                            <h5 style="margin: 0;">MENYELAMATKAN HIDUP SESEORANG</h5>
                            <div id="isi-formulir" class="button-container" style="margin-top: 20px;">
                                    <button  type="button" class="submit-btn">ISI FORMULIR</button>
                            </div>
                        </div>
                        <!-- Gambar -->
                        <div style="flex: 1; min-width: 250px; text-align: center;">
                            <img src="../dist/halaman_sidebar/gambar_baru.png" alt="Gambar" style="width: 100%; max-width: 300px; height: auto;">
                        </div>
                    </div>

                    <div class="cards-container">
                        <!-- Card Jadwal (di kiri) -->
                        <div id="card-jadwal" class="card pink lighten-4 card-jadwal">
                        <div class="card-content">

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


                    <h6 class="card-title">JADWAL ACARA DONOR</h6>
                    <table class="jadwal-tabel">
                        <thead>
                            <tr>
                                <th>Tanggal</th>
                                <th>Tempat</th>
                                <th>Fasilitas</th>
                            </tr>
                        </thead>
                        <tbody>

                            <?php
                            if ($resultdata->num_rows > 0) {
                                // Looping data hasil query
                                while ($row = $resultdata->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars(date("d F Y", strtotime($row['tgl_acara']))) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['lokasi']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['fasilitas']) . "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                // Jika tidak ada data
                                echo "<tr><td colspan='3'>Tidak ada data tersedia</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="reminer-title">⚠️ Peringatan: Pastikan Anda hadir pada acara donor darah yang sudah terjadwal. Jangan lupa membawa identitas diri dan memenuhi persyaratan kesehatan.</div>
                </div>

                        </div>
                    
                        <!-- Kontainer Kanan (2 card-stok) -->
                        <div class="cards-right">
                            <!-- Card Stok 1 --> <div class="card pink lighten-4 card-stok">
                                <div class="card-content">
                                    <h6 class="card-title">STOK DARAH</h6>
                                    <div class="chart-container-pie-chart   ">
                                        <canvas id="barChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            <div class="card pink lighten-4 card-stok">
                                <div class="card-content">
                                    <h6 class="card-title">STOK DARAH</h6>
                                    <div class="chart-container-pie-chart">
                                        <canvas id="pieChart"></canvas>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Card Stok 2 -->
                           
                            <div id="notifikasi" class="card pink lighten-4 card-stok" style="margin-top: 20px;">
                            <div class="card-content">
                                <span class="card-title" style="color: red; font-weight: bold;">
                                    Perhatian: Stok darah kurang dari batas minimum!
                                </span>
                                <div id="notifikasi-content" style="max-height: 200px; overflow-y: auto;">
                                    <!-- Konten dinamis akan diisi oleh JavaScript -->
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    <script>
                       
            // Menangani klik pada Card Jadwal // Klik pada menu Formulir Donor

            $(document).on('click', '#card-jadwal', function () {
                console.log("Card Jadwal diklik.");
                
                // Ubah judul sidebar atau elemen hero
                $('.hero-title').text('Jadwal Acara Donor');

                // Perbarui highlight sidebar di index.php
                const targetMenuId = 'acara-donor'; // ID menu sidebar yang ingin di-highlight
                localStorage.setItem('activeMenu', targetMenuId); // Simpan state ke LocalStorage

                // Hapus highlight dari semua item sidebar dan tambahkan ke target
                $('.bordered').removeClass('selected');
                $(`#${targetMenuId}`).addClass('selected');

                // Memuat konten Jadwal Acara Donor ke dalam sidebar
                $('.admin-content').load('halaman_sidebar/sidebar_acara_donor.php', function (response, status, xhr) {
                    if (status == "error") {
                        console.error("Gagal memuat halaman Jadwal Acara Donor:", xhr.status, xhr.statusText);
                    } else {
                        console.log("Halaman Jadwal Acara Donor berhasil dimuat.");
                    }
                });
            });

            $(document).on('click', '#isi-formulir', function () {
                console.log("Card Isi Formulir diklik.");
                
                // Ubah judul sidebar atau elemen hero
                $('.hero-title').text('Formulir Donor');

                // Perbarui highlight sidebar di index.php
                const targetMenuId = 'formulir-donor'; // ID menu sidebar yang ingin di-highlight
                localStorage.setItem('activeMenu', targetMenuId); // Simpan state ke LocalStorage

                // Hapus highlight dari semua item sidebar dan tambahkan ke target
                $('.bordered').removeClass('selected');
                $(`#${targetMenuId}`).addClass('selected');

                // Memuat konten Formulir Donor ke dalam sidebar
                $('.admin-content').load('halaman_sidebar/sidebar_formulir_donor2.php', function (response, status, xhr) {
                    if (status == "error") {
                        console.error("Gagal memuat halaman Formulir Donor:", xhr.status, xhr.statusText);
                    } else {
                        console.log("Halaman Formulir Donor berhasil dimuat.");
                    }
                });
            });

                    </script>
                        
            <style>
                .card .card-content .card-title {
                display: block;
                line-height: 32px;
                margin-bottom: 8px;
                text-align: center; /* Menambahkan ini untuk memastikan judul berada di tengah secara horizontal */
}

                button.submit-btn {
                    width: 100%;
                    background-color: #df3232;
                    color: #fff;
                    padding: 10px;
                    border: none;
                    border-radius: 60px;
                    font-size: 16px;
                    cursor: pointer;
                    font-weight: bold;
                    transition: background-color 0.3s;
                }
        
                button.submit-btn:hover {
                    background-color: #b71c1c;
                }
                    .chart-container-pie-chart {
                    width: 100%;
                    height: 100%;
                    padding: 10px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                }
                    
                    .chart-container {
                    position: relative;
                    width: 100%;
                    height: 100%;
                    padding: 10px;

}
                        .reminer-title {
                    margin-top: 50px; /* Jarak dari elemen sebelumnya */
                    padding: 15px; /* Padding agar teks terlihat lebih nyaman */
                    background-color: #ffe4e6; /* Warna latar belakang merah muda lembut */
                    color: #721c24; /* Warna teks merah gelap agar menonjol */
                    border-left: 5px solid #BE7171; /* Garis tepi kiri berwarna merah untuk penekanan */
                    border-radius: 8px; /* Membuat sudut yang sedikit membulat */
                
                    font-weight: 500; /* Memberikan ketebalan sedang pada teks */
                    line-height: 1.6; /* Menambah spasi antar baris untuk kenyamanan baca */
                    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Menambahkan sedikit bayangan agar terlihat menonjol */
                    position: relative; /* Menambahkan posisi relatif untuk elemen hiasan */
            }

                    .reminer-title::before {
                    
                        position: absolute;
                        top: 10px;
                        left: 10px;
                        font-size: 0.9em; /* Ukuran font yang cukup nyaman dibaca */
                    }


                    .cards-container {
                        display: flex; /* Mengatur elemen secara horizontal */
                        justify-content: center; /* Pusatkan konten secara horizontal */
                        align-items: stretch; /* Pastikan semua elemen memiliki tinggi yang sama */
                        margin-top: 20px;
                    }

                    .card-jadwal {
                        flex: 1; /* Biarkan kartu jadwal mengambil ruang sesuai kebutuhan */
                        max-width: 40%; /* Batasi lebar kartu */
                        display: flex; /* Membuat isinya fleksibel */
                        flex-direction: column; /* Agar konten di dalam tetap vertikal */
                    }

                    .cards-right {
                        display: flex; /* Membuat kolom kanan fleksibel */
                        flex-direction: column; /* Tumpuk dua kartu di kolom kanan */
                        flex: 1; /* Kolom kanan mengambil ruang fleksibel */
                    }

                    .card-stok {
                        flex: 1; /* Setiap kartu stok mengambil tinggi yang sama di kolom kanan */
                    }

                        /* Container for title and image */
                        .card-title-container {
                            display: flex;
                            align-items: center; /* Rata tengah secara vertikal */
                            justify-content: space-between; /* Ruang antara teks dan gambar */
                            
                        }

                        /* Styling for the image */
                        .card-title-image {
                            width: 100%;           
                            max-width: 300px;       
                            height: auto;           
                            margin: 0 auto;        
                            display: block;         
                        }


                        /* Pastikan konten memiliki margin kiri sesuai ukuran sidebar */
                        .main-content {
                            margin-left: 300px; /* Sesuaikan dengan lebar sidebar */
                            padding: 15px;
                        }
                
                        .card-content p {
                            font-size: 1.5rem;
                        }
                        .card-content h5 {
                            font-size: 1.5rem;
                            color: #CA2424;
                        }
            

                        .hero-title {
                            font-size: 2rem;
                            margin-left: -20px;
                            font-weight: bold;
                            margin-bottom: 1rem;
                        }
                        strong {
                            font-weight: 800;
                            color: #746464;
                        }
                                
                        
                        .jadwal-acara-donor ul {
                        list-style-type: disc;
                        margin-left: 20px;
                        }
                                    @media (max-width: 768px) {
                        .cards-container {
                            flex-direction: column; /* Ubah layout menjadi vertikal */
                            gap: 20px; /* Tetap berikan jarak antar elemen */
                            align-items: center; /* Pusatkan elemen dalam kolom */
                        }

                        .card-jadwal, 
                        .cards-right {
                            max-width: 100%; /* Semua elemen mengambil lebar penuh */
                            flex: none; /* Tidak memaksakan fleksibilitas horizontal */
                        }

                        .cards-right {
                            display: flex;
                            flex-direction: column; /* Tetap tumpuk dua kartu stok */
                            gap: 20px; /* Jarak antar kartu stok */
                        }
                    }

                        @media (max-width: 992px) {
                            /* Responsif untuk layar kecil: sidebar tidak fixed */
                            .main-content {
                                margin-left: 0;
                            }
                        }

                        h5 {
                        font-size: 1.64rem;
                        font-weight: 800;
                        line-height: 110%;
                        margin: 1.0933333333rem 0 .656rem 0;
                        color: #CA2424;
                    }

                        button.btn {
                        display: flex;
                        justify-content: center;
                        align-items: center;
                        margin: 20px;
                        margin-left: 5px;
                        margin-bottom: 10px;
                        padding: 10px 40px;
                        font-size: 16px;
                        font-weight: bold;
                        background-color: #DF3232;
                        color: #fff;
                        border: none;
                        border-radius: 20px;
                        cursor: pointer;
                        }

                        .btn:hover, .btn-large:hover, .btn-small:hover {
                            background-color: #f32828;
                        }
                        /* Atau lebih spesifik */
                        button.btn.my-custom-class:active {
                            background-color: #8B0000;
                        }

                        .btn, .btn-large, .btn-small {
                        text-decoration: none;
                        color: #fff;
                        background-color: #d1392e;
                        text-align: center;
                        letter-spacing: .5px;
                        -webkit-transition: background-color .2s ease-out;
                        transition: background-color .2s ease-out;
                        cursor: pointer;
                        }
                        
                    
                        .cards-container {
                            display: flex;
                            gap: 5px; /* Memberi jarak antara dua card */
                            margin-top: 30px;
                            justify-content: center; /* Memusatkan kedua card di dalam container */
                            
                        }

                        .card {
                            border: 1px solid #d1d1d1; /* Garis tipis untuk memberikan batas */
                            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                        }

                        .card-jadwal {
                            flex: 0 1 400px; /* Mengatur card jadwal dengan lebar tetap 500px */
                        }

                        .card-stok {
                            flex: 0 1 400px; /* Mengatur card stok dengan lebar tetap 400px */
                        }

                        .jadwal-tabel {
                            width: 100%; /* Mengurangi lebar tabel agar tidak melebihi container card */
                            border-collapse: collapse;
                            margin: 20px auto; /* Memastikan tabel berada di tengah dengan margin atas dan bawah */
                            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1), -2px 0 5px rgba(0, 0, 0, 0.1); /* Box shadow kanan dan kiri */
                            border-radius: 20px;
                                        }

                        .jadwal-tabel thead th {
                            background-color: #C73F3F; /* Warna merah untuk header tabel */
                            color: #FFFFFF; /* Warna teks putih */
                            padding: 5px;
                            text-align: center;
                            font-weight: bold;
                            border-radius:5px;
                        }


                        .jadwal-tabel tbody tr:nth-child(odd) {
                            background-color: #FFFFFF; /* Baris ganjil berwarna putih */
                        }

                        .jadwal-tabel tbody tr:nth-child(even) {
                            background-color: #F5F5F5; /* Baris genap berwarna abu-abu muda */
                        }
                        .jadwal-tabel thead th {
                            background-color: #DF3232; /* Warna merah header */
                            color: #FFFFFF; /* Warna teks putih */
                            border-radius: 2px;
                            padding: 10px;
                            text-align: center;
                            font-weight: bold;
                            border: none;
                        }

                        .header-container {
                            display: flex;
                            justify-content: space-around; /* Agar teks tersebar dengan rapi */
                            align-items: center;
                            width: 100%;
                        }

                        .header-container span {
                            flex: 1; /* Membuat setiap elemen span memiliki lebar yang sama */
                            text-align: center; /* Agar teks di tengah */
                        }

                    


                        .card-title {
                            font-weight: bold;
                            color: #333333;
                            margin-bottom: 20px;
                            text-align: left;
                        }

                        .chart-container {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            height: 250px; /* Menetapkan tinggi agar grafik tetap konsisten */
                        }

                        @media (max-width: 768px) {
                            .card {
                                flex: 1 1 100%; /* Pada layar lebih kecil dari 768px, card menempati seluruh lebar */
                                max-width: 100%; /* Memastikan card memenuhi lebar penuh */
                            }

                            .cards-container {
                                gap: 15px; /* Mengurangi jarak antar card pada layar kecil */
                            }

                            .jadwal-tabel thead th, .jadwal-tabel tbody tr td {
                                padding: 8px; /* Mengurangi padding untuk membuat tabel lebih responsif di layar kecil */
                                font-size: 13px; /* Mengurangi ukuran font untuk layar kecil */
                            }

                            .card-title {
                                font-size: 16px; /* Mengurangi ukuran font judul card pada layar kecil */
                            }
                        }
                        .card .card-title {
                            font-size: 20px;
                            font-weight: 900;
                        }
                        .card .card-content {
                            padding: 15px;
                            border-radius: 0 0 2px 2px;
                        }
                        .card-container {
                            padding: 0; /* Menghilangkan padding container */
                            margin: 0 auto; /* Pastikan container tetap berada di tengah */
                        }


                            </style>
                        </div>
                    </div>
                </div>
                </div>
                                </div>
                            </div>
                </body>
                </html>