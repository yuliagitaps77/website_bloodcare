<?php
session_start(); // Mulai session

// Hapus semua data session
session_unset();
session_destroy();

// Kirim respons JSON
echo json_encode([
    "success" => true,
    "message" => "Logout berhasil."
]);
?>
