<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'toko_buku';

$conn = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// helper sederhana untuk admin
function is_logged()
{
    return isset($_SESSION['user']);
}

function is_admin()
{
    return is_logged() && ($_SESSION['user']['role'] === 'admin');
}

// Redirect jika bukan admin
function require_admin()
{
    if (!is_admin()) {
        header("Location: /toko-buku/login.php");
        exit;
    }
}
