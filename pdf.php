<?php 
// Memasukkan file koneksi database
require('api/koneksi.php');  // Pastikan path ini benar sesuai dengan lokasi koneksi.php Anda

// Memasukkan library FPDF
require('fpdf/fpdf.php');  // Pastikan path ini benar sesuai dengan lokasi fpdf.php Anda

/**
 * Fungsi untuk mengambil data acara donor dan nama peserta berdasarkan lokasi
 *
 * @param string $lokasi Lokasi acara donor yang akan dicari
 * @param int $id_akun ID akun peserta
 * @return array|null Data acara donor dan peserta atau null jika tidak ditemukan
 */
function getAcaraDonorDataByLokasi($lokasi, $id_akun) {
    global $conn;

    // Query untuk mengambil data acara donor dan informasi akun peserta berdasarkan lokasi menggunakan LIKE
    $query = "
        SELECT a.id_acara, a.tgl_acara, a.time_waktu, u.nama_lengkap
        FROM acara_donor a
        JOIN akun u ON u.id_akun = ? 
        WHERE a.lokasi LIKE ?
        ORDER BY a.tgl_acara DESC
        LIMIT 1";  // Mengambil acara terbaru jika ada beberapa yang cocok
        
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        // Handle error jika prepare gagal
        return null;
    }

    $like_lokasi = "%" . $lokasi . "%";
    $stmt->bind_param("is", $id_akun, $like_lokasi);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

/**
 * Fungsi untuk menyimpan sertifikat ke dalam database
 *
 * @param int $id_akun ID akun peserta
 * @param int $id_acara_donor ID acara donor
 * @param string $link_sertifikat Link atau path ke sertifikat
 * @param string $waktu_donor Waktu donor
 * @param string $alasan Alasan donor
 * @param string $status_donor Status donor
 * @return bool True jika berhasil, false jika gagal
 */
function saveSertifikat($id_akun, $id_acara_donor, $link_sertifikat, $waktu_donor, $alasan, $status_donor) {
    global $conn;
    
    $query = "INSERT INTO sertifikat (id_akun, id_acara_donor, link_sertifikat, alasan, status_donor, waktu_donor) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    if (!$stmt) {
        // Handle error jika prepare gagal
        return false;
    }
    $stmt->bind_param("iissss", $id_akun, $id_acara_donor, $link_sertifikat, $alasan, $status_donor, $waktu_donor);
    
    return $stmt->execute();
}

// Cek apakah data diterima dengan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari POST
    $id_akun = isset($_POST['id_akun']) ? intval($_POST['id_akun']) : null;
    $lokasi = isset($_POST['lokasi']) ? trim($_POST['lokasi']) : null;  // Mengambil 'lokasi' alih-alih 'id_acara_donor'
    $alasan = isset($_POST['alasan']) ? trim($_POST['alasan']) : '';
    $status_donor = isset($_POST['status_donor']) ? trim($_POST['status_donor']) : 'Berhasil';

    // Validasi input
    if ($id_akun && $lokasi) {
        // Ambil data acara donor dan nama peserta berdasarkan lokasi
        $data = getAcaraDonorDataByLokasi($lokasi, $id_akun);

        if ($data) {
            $id_acara_donor = $data['id_acara'];  // Mendapatkan id_acara_donor dari data yang diambil

            // Inisialisasi FPDF
            $pdf = new FPDF('L', 'mm', 'A4'); // Landscape, mm unit, A4 size
            $pdf->AddPage();

            // Menambahkan font kustom (Poppins-Regular dan Poppins-Bold)
            // Pastikan file font tersedia dan path-nya benar
            $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php'); 
            $pdf->AddFont('Poppins-Bold', 'B', 'Poppins-Bold.php'); 

            // Ukuran halaman A4 (Landscape) dalam milimeter
            $pageWidth = 297; // Lebar halaman
            $pageHeight = 210; // Tinggi halaman

            // Resolusi gambar latar belakang
            $imageWidth = 2000;
            $imageHeight = 1414;

            // Hitung skala agar sesuai dengan halaman
            $aspectRatio = $imageWidth / $imageHeight; 
            if ($pageWidth / $pageHeight > $aspectRatio) {
                $finalHeight = $pageHeight;
                $finalWidth = $pageHeight * $aspectRatio;
            } else {
                $finalWidth = $pageWidth;
                $finalHeight = $pageWidth / $aspectRatio;
            }

            // Tambahkan gambar sebagai latar belakang
            $backgroundImage = 'sertif.png'; // Pastikan path gambar benar
            if (file_exists($backgroundImage)) {
                $pdf->Image($backgroundImage, 0, 0, $finalWidth, $finalHeight);
            } else {
                // Handle jika gambar tidak ditemukan
                // Misalnya, isi latar belakang dengan warna putih
                $pdf->SetFillColor(255, 255, 255);
                $pdf->Rect(0, 0, $pageWidth, $pageHeight, 'F');
            }

            // Tambahkan nama peserta menggunakan font Poppins-Bold
            $pdf->SetFont('Poppins-Bold', 'B', 38); // Ukuran font 38
            $pdf->SetTextColor(0, 0, 0); // Warna hitam
            $pdf->SetXY(10, 90); // Posisi teks (sesuaikan jika perlu)
            $pdf->Cell($pageWidth - 20, 10, $data['nama_lengkap'], 0, 1, 'C'); // Nama peserta

            // Format tanggal dan waktu acara
            $tanggal = date('d F Y', strtotime($data['tgl_acara'])); // Format tanggal: 01 Januari 2024

            // Tambahkan tanggal acara menggunakan font Poppins-Regular
            $pdf->SetFont('Poppins-Regular', '', 19); // Ukuran font 19
            $pdf->SetXY(10, 140); // Posisi teks (sesuaikan jika perlu)
            $pdf->Cell($pageWidth - 20, 10, "Tanggal Acara: " . $tanggal, 0, 1, 'C'); // Tanggal acara

            // Menghasilkan nama file sertifikat dengan UUID
            $uuid = uniqid();  // Menghasilkan UUID yang unik
            $fileName = $uuid . '_' . preg_replace('/\s+/', '', $data['nama_lengkap']) . '_' . date('YmdHis') . '.pdf';
            $outputPath = 'api/sertifikat/' . $fileName;  // Path untuk menyimpan sertifikat PDF
            
            // Pastikan direktori tujuan ada
            if (!is_dir('api/sertifikat/')) {
                mkdir('api/sertifikat/', 0755, true);
            }

            // Simpan PDF sebagai file
            $pdf->Output('F', $outputPath);  // Menyimpan sebagai file PDF

            // Simpan data sertifikat ke database
            $waktu_donor = date('Y-m-d H:i:s', strtotime($data['time_waktu'])); // Ambil waktu donor
            if (saveSertifikat($id_akun, $id_acara_donor, $outputPath, $waktu_donor, $alasan, $status_donor)) {
                echo json_encode(['success' => 'Sertifikat berhasil dibuat dan disimpan.', 'link' => $outputPath]);
            } else {
                echo json_encode(['error' => 'Gagal menyimpan data sertifikat ke database.']);
            }

        } else {
            echo json_encode(['error' => 'Data acara donor atau peserta tidak ditemukan berdasarkan lokasi yang diberikan.']);
        }
    } else {
        echo json_encode(['error' => 'Parameter tidak lengkap. Pastikan id_akun dan lokasi diisi.']);
    }
} else {
    echo json_encode(['error' => 'Metode request tidak valid. Harus menggunakan POST.']);
}
?>
