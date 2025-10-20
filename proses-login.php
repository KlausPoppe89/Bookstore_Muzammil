<?php
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $password = $_POST['password'];

    $res = mysqli_query($conn, "SELECT * FROM users WHERE nama='$nama' LIMIT 1");
    if (mysqli_num_rows($res) === 1) {
        $u = mysqli_fetch_assoc($res);

        // Verifikasi password
        if (password_verify($password, $u['password'])) {
            // simpan session
            $_SESSION['user'] = [
                'id' => $u['id'],
                'nama' => $u['nama'],
                'email' => $u['email'],
                'role' => $u['role']
            ];
            $_SESSION['success'] = "Login berhasil! Selamat datang {$u['nama']}";

            // Redirect berdasarkan role
            if ($u['role'] === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: index.php");
            }
            exit;
        }
    }

    $_SESSION['error'] = "Nama atau password salah!";
    header("Location: login.php");
    exit;
}
