<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'db_wisata';

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Set timezone
date_default_timezone_set('Asia/Jakarta');
