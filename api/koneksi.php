<?php
// BASE_URL untuk pengaturan URL dinamis
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
$host = $_SERVER['HTTP_HOST'];
$basePath = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
define('BASE_URL', $protocol . $host . '/website_bloodcare');

// Konfigurasi database
$hostDB = 'localhost';
$userDB = 'root';
$passwordDB = '';
$dbname = 'bloodcarec3';

// Koneksi ke database
$conn = new mysqli($hostDB, $userDB, $passwordDB, $dbname);

?>
