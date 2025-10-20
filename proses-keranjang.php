<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}

$action = $_POST['action'] ?? 'add';
$buku_id = intval($_POST['buku_id'] ?? 0);
$qty = max(1, intval($_POST['qty'] ?? 1));

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

if ($action === 'add') {
    // Ambil data buku dari database termasuk stok
    $query = mysqli_query($conn, "SELECT * FROM buku WHERE id='$buku_id'");
    $b = mysqli_fetch_array($query);

    if (!$b) {
        $_SESSION['error'] = "Buku tidak ditemukan!";
        header("Location: buku.php");
        exit;
    }

    // VALIDASI STOK - TAMBAHKAN KEMBALI
    if ($b['Stok'] <= 0) {
        $_SESSION['error'] = "Maaf, stok untuk buku '{$b['nama']}' sedang habis!";
        header("Location: buku.php");
        exit;
    }

    // Hitung total quantity yang akan dimasukkan ke keranjang
    $current_cart_qty = isset($_SESSION['cart'][$buku_id]) ? $_SESSION['cart'][$buku_id]['qty'] : 0;
    $total_qty_after_add = $current_cart_qty + $qty;

    // Validasi stok mencukupi
    if ($total_qty_after_add > $b['Stok']) {
        $_SESSION['error'] = "Maaf, stok untuk buku '{$b['nama']}' tidak mencukupi. Stok tersedia: {$b['Stok']} pcs";
        header("Location: buku.php");
        exit;
    }

    // Tambahkan ke keranjang
    if (isset($_SESSION['cart'][$buku_id])) {
        $_SESSION['cart'][$buku_id]['qty'] += $qty;
    } else {
        $_SESSION['cart'][$buku_id] = [
            'buku_id' => $buku_id,
            'nama' => $b['nama'],
            'harga' => $b['harga'],
            'qty' => $qty,
            'stok_tersedia' => $b['Stok'] // Simpan info stok untuk validasi
        ];
    }

    $_SESSION['success'] = "Buku '{$b['nama']}' berhasil ditambahkan ke keranjang!";
    header("Location: keranjang.php");
    exit;
}

if ($action === 'update') {
    $has_error = false;

    foreach ($_POST['qty'] as $id => $quantity) {
        $id = intval($id);
        $quantity = max(1, intval($quantity));

        // Validasi stok sebelum update
        if (isset($_SESSION['cart'][$id])) {
            $query_stok = mysqli_query($conn, "SELECT Stok, nama FROM buku WHERE id='$id'");
            $stok_data = mysqli_fetch_array($query_stok);

            if ($stok_data && $quantity > $stok_data['Stok']) {
                $_SESSION['error'] = "Stok untuk buku '{$stok_data['nama']}' tidak mencukupi. Stok tersedia: {$stok_data['Stok']} pcs";
                $has_error = true;
                // Tetap update dengan quantity maksimum yang tersedia
                $quantity = min($quantity, $stok_data['Stok']);
            }

            $_SESSION['cart'][$id]['qty'] = $quantity;
            $_SESSION['cart'][$id]['stok_tersedia'] = $stok_data['Stok'];
        }
    }

    if ($has_error) {
        $_SESSION['warning'] = "Beberapa quantity disesuaikan dengan stok tersedia.";
    } else {
        $_SESSION['success'] = "Keranjang berhasil diupdate!";
    }

    header("Location: keranjang.php");
    exit;
}

if ($action === 'remove') {
    if (isset($_SESSION['cart'][$buku_id])) {
        $nama_buku = $_SESSION['cart'][$buku_id]['nama'];
        unset($_SESSION['cart'][$buku_id]);
        $_SESSION['success'] = "Buku '$nama_buku' berhasil dihapus dari keranjang!";
    } else {
        $_SESSION['error'] = "Item tidak ditemukan di keranjang!";
    }
    header("Location: keranjang.php");
    exit;
}

// Fallback jika action tidak dikenali
header("Location: buku.php");
exit;
