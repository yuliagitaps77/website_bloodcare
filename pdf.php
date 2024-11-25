<?php
require('fpdf/fpdf.php');

// Inisialisasi FPDF
$pdf = new FPDF('L', 'mm', 'A4'); // Landscape, mm unit, A4 size
$pdf->AddPage();

// Tambahkan font kustom
$pdf->AddFont('GreatVibes', '', 'GreatVibes-Regular.php'); // Untuk nama
$pdf->AddFont('AristotelicaSmallCaps', '', 'AristotelicaSmallCaps-Regular.php'); // Untuk tanggal

// Ukuran halaman A4 (Landscape) dalam milimeter
$pageWidth = 297; // Lebar halaman
$pageHeight = 210; // Tinggi halaman

// Resolusi gambar Anda (2000 x 1414)
$imageWidth = 2000;
$imageHeight = 1414;

// Hitung skala agar sesuai dengan halaman
$aspectRatio = $imageWidth / $imageHeight; // Rasio aspek gambar
if ($pageWidth / $pageHeight > $aspectRatio) {
    // Jika halaman lebih lebar, sesuaikan tinggi
    $finalHeight = $pageHeight;
    $finalWidth = $pageHeight * $aspectRatio;
} else {
    // Jika halaman lebih tinggi, sesuaikan lebar
    $finalWidth = $pageWidth;
    $finalHeight = $pageWidth / $aspectRatio;
}

// Tambahkan gambar sebagai latar belakang
$pdf->Image('1.png', 0, 0, $finalWidth, $finalHeight);

// Tambahkan nama menggunakan font GreatVibes
$pdf->SetFont('GreatVibes', '', 56); // Gunakan font GreatVibes
$pdf->SetTextColor(0, 0, 0); // Warna hitam
$pdf->SetXY(0, 90); // Posisi teks
$pdf->Cell(0, 10, 'Yulia Gita', 0, 1, 'C'); // Nama peserta

// Tambahkan tanggal menggunakan font AristotelicaSmallCaps
$pdf->SetFont('AristotelicaSmallCaps', '', 32); // Gunakan font AristotelicaSmallCaps
$pdf->SetXY(0, 140); // Posisi teks
$pdf->Cell(0, 10, '', 0, 1, 'C'); // Tanggal

// Simpan atau tampilkan PDF
$pdf->Output('D', 'sertifikat.pdf'); // Unduh file PDF
?>
