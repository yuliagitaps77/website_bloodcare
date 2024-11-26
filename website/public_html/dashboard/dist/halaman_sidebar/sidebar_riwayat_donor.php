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
								<tr>
									
									<td><a href="#" class="btn-unduh">Unduh Sertifikat</a></td>
								</tr>
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
    border-collapse: separate;
    border-spacing: 0 40px; /* Menambahkan ruang vertikal antar baris */
    margin-top: 40px;
}

.riwayat-donor td {
    padding: 10px;
    background-color: #F5F5F5;
    text-align: right; /* Ini akan memastikan tombol di sebelah kanan */
    border-radius: 15px;
    border-bottom: 2px solid white;
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
				
						