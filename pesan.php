<?php
require_once 'koneksi.php';
require_once 'navbar.php';

if (!is_logged()) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];
$is_admin = is_admin();

// Tentukan ID admin (bisa juga query dinamis)
$admin = mysqli_fetch_assoc(mysqli_query($conn, "SELECT id, email FROM users WHERE role='admin' LIMIT 1"));
$admin_id = $admin['id'];

// Jika ada kiriman pesan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesan'])) {
    $isi = mysqli_real_escape_string($conn, $_POST['pesan']);
    $penerima_id = $is_admin ? intval($_POST['penerima_id']) : $admin_id;

    // Cegah mengirim ke diri sendiri
    if ($penerima_id != $user_id) {
        mysqli_query($conn, "
            INSERT INTO pesan (id_pengirim, penerima_id, email_tujuan, subjek, isi)
            VALUES ($user_id, $penerima_id, '', 'Chat', '$isi')
        ");
    }
    header("Location: pesan.php");
    exit;
}

// Ambil pesan antara user dan admin
if ($is_admin) {
    // admin bisa melihat semua pesan
    $pesan = mysqli_query($conn, "
        SELECT p.*, u.nama AS pengirim_nama
        FROM pesan p
        LEFT JOIN users u ON p.id_pengirim = u.id
        ORDER BY p.tanggal ASC
    ");
} else {
    // user hanya lihat pesan dengan admin
    $pesan = mysqli_query($conn, "
        SELECT p.*, u.nama AS pengirim_nama
        FROM pesan p
        LEFT JOIN users u ON p.id_pengirim = u.id
        WHERE (p.id_pengirim = $user_id AND p.penerima_id = $admin_id)
           OR (p.id_pengirim = $admin_id AND p.penerima_id = $user_id)
        ORDER BY p.tanggal ASC
    ");
}
?>

<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Pesan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        body {
            background-color: #f5f7fa;
            font-family: "Poppins", sans-serif;
        }

        .chat-box {
            background: white;
            border-radius: 10px;
            padding: 15px;
            height: 60vh;
            overflow-y: auto;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .msg {
            padding: 8px 12px;
            border-radius: 10px;
            margin: 6px 0;
            max-width: 70%;
        }

        .msg.user {
            background: #0d6efd;
            color: white;
            margin-left: auto;
        }

        .msg.admin {
            background: #e9ecef;
            color: #333;
            margin-right: auto;
        }
    </style>
</head>

<body>
    <div class="container mt-4">
        <h3 class="text-primary">💬 Pesan</h3>
        <div class="chat-box mb-3">
            <?php while ($p = mysqli_fetch_assoc($pesan)): ?>
                <div class="msg <?= $p['id_pengirim'] == $user_id ? 'user' : 'admin' ?>">
                    <small><b><?= htmlspecialchars($p['pengirim_nama']); ?></b></small><br>
                    <?= nl2br(htmlspecialchars($p['isi'])); ?><br>
                    <small class="text-muted"><?= $p['tanggal']; ?></small>
                </div>
            <?php endwhile; ?>
        </div>

        <form method="post" class="d-flex gap-2">
            <?php if ($is_admin): ?>
                <select name="penerima_id" class="form-select w-auto" required>
                    <option value="">Kirim ke...</option>
                    <?php
                    $users = mysqli_query($conn, "SELECT id, nama FROM users WHERE role='user'");
                    while ($u = mysqli_fetch_assoc($users)):
                    ?>
                        <option value="<?= $u['id']; ?>"><?= htmlspecialchars($u['nama']); ?></option>
                    <?php endwhile; ?>
                </select>
            <?php endif; ?>
            <input type="text" name="pesan" class="form-control" placeholder="Ketik pesan..." required>
            <button class="btn btn-primary">Kirim</button>
        </form>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>