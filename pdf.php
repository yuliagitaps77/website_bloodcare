<?php
// Memasukkan file koneksi database
require('api/koneksi.php');  // Pastikan path ini benar sesuai dengan lokasi koneksi.php Anda

// Memasukkan library FPDF
require('fpdf/fpdf.php');  // Pastikan path ini benar sesuai dengan lokasi fpdf.php Anda

// Fungsi untuk mengambil data acara donor dan nama peserta
function getAcaraDonorData($id_acara_donor, $id_akun) {
    global $conn;

    // Query untuk mengambil data acara donor dan informasi akun peserta
    $query = "
        SELECT a.tgl_acara, a.time_waktu, u.nama_lengkap
        FROM acara_donor a
        JOIN akun u ON u.id_akun = ? 
        WHERE a.id_acara = ?";
        
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ii", $id_akun, $id_acara_donor);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->fetch_assoc();
}

// Fungsi untuk menyimpan sertifikat ke dalam database
function saveSertifikat($id_akun, $id_acara_donor, $link_sertifikat, $waktu_donor, $alasan, $status_donor) {
    global $conn;
    
    $query = "INSERT INTO sertifikat (id_akun, id_acara_donor, link_sertifikat, alasan, status_donor, waktu_donor) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iissss", $id_akun, $id_acara_donor, $link_sertifikat, $alasan, $status_donor, $waktu_donor);
    
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

// Cek apakah data diterima dengan metode POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data dari POST
    $id_akun = $_POST['id_akun'] ?? null;
    $id_acara_donor = $_POST['id_acara_donor'] ?? null;
    $alasan = $_POST['alasan'] ?? '';
    $status_donor = $_POST['status_donor'] ?? 'Berhasil';

    if ($id_akun && $id_acara_donor) {
        // Ambil data acara donor dan nama peserta berdasarkan id
        $data = getAcaraDonorData($id_acara_donor, $id_akun);

        if ($data) {
            // Inisialisasi FPDF
            $pdf = new FPDF('L', 'mm', 'A4'); // Landscape, mm unit, A4 size
            $pdf->AddPage();

            // Menambahkan font kustom (Poppins-Regular dan Poppins-Bold)
            // Gunakan path relatif untuk font sesuai dengan struktur folder Anda

            $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php'); // Untuk nama (font Bold)
            $pdf->AddFont('Poppins-Regular', '', 'Poppins-Regular.php'); // Untuk tanggal (font Regular)
           

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
            $pdf->Image('sertif.png', 0, 0, $finalWidth, $finalHeight); // Pastikan path gambar benar

            // Tambahkan nama peserta menggunakan font Poppins-Regular
            $pdf->SetFont('Poppins-Regular', '', 38); // Ukuran font 38
            $pdf->SetTextColor(0, 0, 0); // Warna hitam
            $pdf->SetXY(0, 90); // Posisi teks (sesuaikan jika perlu)
            $pdf->Cell(0, 10, $data['nama_lengkap'], 0, 1, 'C'); // Nama peserta

            // Format tanggal dan waktu acara
            $tanggal = date('d F Y', strtotime($data['tgl_acara'])); // Format tanggal: 01 Januari 2024

            // Tambahkan tanggal acara menggunakan font Poppins-Regular
            $pdf->SetFont('Poppins-Regular', '', 19); // Ukuran font 19
            $pdf->SetXY(0, 140); // Posisi teks (sesuaikan jika perlu)
            $pdf->Cell(0, 10, $tanggal, 0, 1, 'C'); // Tanggal acara

            // Menghasilkan nama file sertifikat dengan UUID
            $uuid = uniqid();  // Menghasilkan UUID yang unik
            $fileName = $uuid . '_' . str_replace(' ', '_', $data['nama_lengkap']) . '_' . date('YmdHis') . '.pdf';
            $outputPath = 'api/sertifikat/' . $fileName;  // Path untuk menyimpan sertifikat gambar

            // Simpan PDF sebagai gambar PNG
            $pdf->Output('F', $outputPath);  // Menyimpan sebagai file PNG

            // Simpan data sertifikat ke database
            $waktu_donor = date('Y-m-d H:i:s', strtotime($data['time_waktu'])); // Ambil waktu donor
            if (saveSertifikat($id_akun, $id_acara_donor, $outputPath, $waktu_donor, $alasan, $status_donor)) {
                echo json_encode(['success' => 'Sertifikat berhasil dibuat dan disimpan.', 'link' => $outputPath]);
            } else {
                echo json_encode(['error' => 'Gagal menyimpan data sertifikat ke database.']);
            }

        } else {
            echo json_encode(['error' => 'Data acara donor atau peserta tidak ditemukan']);
        }
    } else {
        echo json_encode(['error' => 'Parameter tidak lengkap']);
    }
} else {
    echo json_encode(['error' => 'Metode request tidak valid']);
}
?>
