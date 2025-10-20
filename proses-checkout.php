<?php
require_once 'koneksi.php';

if (!is_logged() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: keranjang.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$nama = mysqli_real_escape_string($conn, $_POST['nama']);
$alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
$telepon = mysqli_real_escape_string($conn, $_POST['telepon']);
$pembayaran = mysqli_real_escape_string($conn, $_POST['pembayaran']);

// Hitung total
$total = 0;
foreach ($cart as $it) {
    $total += $it['harga'] * $it['qty'];
}

// Mulai transaction
mysqli_begin_transaction($conn);

try {
    // 1. Insert ke tabel pesanan
    $insert_pesanan = mysqli_query(
        $conn,
        "INSERT INTO pesanan (user_id, nama_penerima, alamat, telepon, total, pembayaran, status) 
            VALUES ($user_id, '$nama', '$alamat', '$telepon', $total, '$pembayaran', 'pending')"
    );

    if (!$insert_pesanan) {
        throw new Exception("Gagal membuat pesanan: " . mysqli_error($conn));
    }

    $pesanan_id = mysqli_insert_id($conn);

    if (!$pesanan_id) {
        throw new Exception("Gagal mendapatkan ID pesanan");
    }

    // 2. Insert detail pesanan dan ambil info kategori dari database
    foreach ($cart as $it) {
        $buku_id = intval($it['buku_id']);
        $qty = intval($it['qty']);
        $harga = floatval($it['harga']);
        $subtotal = $harga * $qty;

        // Ambil info lengkap buku termasuk kategori
        $query_buku = mysqli_query(
            $conn,
            "SELECT b.*, k.nama as kategori_nama 
                FROM buku b 
                LEFT JOIN kategori k ON b.kategori_id = k.id 
                WHERE b.id = $buku_id"
        );
        $buku_data = mysqli_fetch_array($query_buku);

        if (!$buku_data) {
            throw new Exception("Data buku tidak ditemukan untuk ID: $buku_id");
        }

        // Insert detail pesanan dengan info lengkap
        $insert_detail = mysqli_query(
            $conn,
            "INSERT INTO detail_pesanan (pesanan_id, buku_id, nama_buku, kategori, qty, harga_unit, subtotal) 
                VALUES ($pesanan_id, $buku_id, '{$buku_data['nama']}', '{$buku_data['kategori_nama']}', $qty, $harga, $subtotal)"
        );

        if (!$insert_detail) {
            throw new Exception("Gagal insert detail pesanan: " . mysqli_error($conn));
        }

        // Update stok buku
        $update_stok = mysqli_query(
            $conn,
            "UPDATE buku SET ketersediaan_stok = 'habis' WHERE id = $buku_id"
        );

        if (!$update_stok) {
            throw new Exception("Gagal update stok buku: " . mysqli_error($conn));
        }
    }

    // Commit transaction
    mysqli_commit($conn);

    // Kosongkan keranjang
    unset($_SESSION['cart']);

    $_SESSION['success'] = "Pesanan berhasil dibuat! ID Pesanan: #$pesanan_id";
    header("Location: pesanan.php");
    exit;
} catch (Exception $e) {
    // Rollback transaction jika ada error
    mysqli_rollback($conn);

    $_SESSION['error'] = "Gagal membuat pesanan: " . $e->getMessage();
    header("Location: checkout.php");
    exit;
}
