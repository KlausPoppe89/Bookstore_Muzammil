<?php
require_once 'koneksi.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Jika user membatalkan pesanan
if (isset($_POST['batal_id'])) {
    $batal_id = intval($_POST['batal_id']);

    // Pastikan pesanan milik user ini dan statusnya masih pending/paid
    $cek = mysqli_query($conn, "SELECT * FROM pesanan WHERE id = $batal_id AND user_id = $user_id AND (status = 'pending' OR status = 'paid')");
    if (mysqli_num_rows($cek) > 0) {
        mysqli_query($conn, "UPDATE pesanan SET status = 'cancelled' WHERE id = $batal_id");
    }
    header("Location: user_pesanan.php");
    exit;
}

// Ambil daftar pesanan user
$res = mysqli_query($conn, "
        SELECT * FROM pesanan 
        WHERE user_id = $user_id 
        ORDER BY created_at DESC
    ");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-4">
        <h3 class="fw-bold text-primary mb-3">Pesanan Saya</h3>
        <?php if (mysqli_num_rows($res) == 0): ?>
            <div class="alert alert-info">Kamu belum memiliki pesanan.</div>
        <?php else: ?>
            <table class="table table-hover align-middle shadow-sm bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($r = mysqli_fetch_assoc($res)): ?>
                        <tr>
                            <td><?php echo $r['id']; ?></td>
                            <td><b>Rp <?php echo number_format($r['total'], 0, ',', '.'); ?></b></td>
                            <td>
                                <span class="status-badge status-<?php echo $r['status']; ?>">
                                    <?php echo $r['status']; ?>
                                </span>
                            </td>
                            <td><?php echo $r['created_at']; ?></td>
                            <td>
                                <?php if ($r['status'] == 'pending' || $r['status'] == 'paid'): ?>
                                    <form method="post" style="display:inline;" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                                        <input type="hidden" name="batal_id" value="<?php echo $r['id']; ?>">
                                        <button type="submit" class="btn-cancel btn-sm">Batalkan Pesanan</button>
                                    </form>
                                <?php else: ?>
                                    <span class="text-muted small">Tidak dapat dibatalkan</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>

</html>