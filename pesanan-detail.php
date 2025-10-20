<?php
require_once 'koneksi.php';

// Pastikan user sudah login
if (!is_logged()) {
    header("Location: login.php");
    exit;
}

// Ambil ID pesanan dari URL
if (!isset($_GET['id'])) {
    header("Location: user_pesanan.php");
    exit;
}

$pesanan_id = intval($_GET['id']);
$user_id = $_SESSION['user']['id'];

// Cek apakah pesanan ini milik user yang sedang login
$q = mysqli_query($conn, "SELECT * FROM pesanan WHERE id = $pesanan_id AND user_id = $user_id");
if (mysqli_num_rows($q) === 0) {
    echo "<div class='alert alert-danger m-4'>Pesanan tidak ditemukan atau bukan milik Anda.</div>";
    exit;
}
$pesanan = mysqli_fetch_assoc($q);

// Ambil daftar buku dalam pesanan
$detail_query = "
        SELECT pd.*, b.nama, b.foto 
        FROM detail_pesanan pd
        LEFT JOIN buku b ON pd.buku_id = b.id
        WHERE pd.pesanan_id = $pesanan_id
    ";
$details = mysqli_query($conn, $detail_query);
?>
<!doctype html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Pesanan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-5 mb-5">
        <h3 class="mb-4">Detail Pesanan #<?php echo $pesanan['id']; ?></h3>

        <div class="card mb-3 shadow-sm">
            <div class="card-body">
                <p><strong>Status:</strong>
                    <span class="badge 
                        <?php
                        switch ($pesanan['status']) {
                            case 'pending':
                                echo 'bg-warning text-dark';
                                break;
                            case 'paid':
                                echo 'bg-info';
                                break;
                            case 'delivered':
                                echo 'bg-primary';
                                break;
                            case 'arrived':
                                echo 'bg-success';
                                break;
                            case 'cancelled':
                                echo 'bg-danger';
                                break;
                            default:
                                echo 'bg-secondary';
                        }
                        ?>">
                        <?php echo ucfirst($pesanan['status']); ?>
                    </span>
                </p>
                <p><strong>Nama Penerima:</strong> <?php echo htmlspecialchars($pesanan['nama_penerima']); ?></p>
                <p><strong>Alamat:</strong> <?php echo htmlspecialchars($pesanan['alamat']); ?></p>
                <p><strong>Telepon:</strong> <?php echo htmlspecialchars($pesanan['telepon']); ?></p>
                <p><strong>Tanggal Pesanan:</strong> <?php echo $pesanan['created_at']; ?></p>
                <p><strong>Total Pembayaran:</strong> Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></p>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Daftar Buku yang Dipesan</h5>
                <div class="table-responsive">
                    <table class="table table-striped align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Buku</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total = 0;
                            while ($d = mysqli_fetch_assoc($details)):
                                $subtotal = $d['qty'] * $d['harga_unit'];
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td>
                                        <?php if ($d['foto'] && file_exists('image/' . $d['foto'])): ?>
                                            <img src="image/<?php echo htmlspecialchars($d['foto']); ?>" width="60" height="80" style="object-fit:cover;border-radius:5px;">
                                        <?php else: ?>
                                            <div class="text-muted small">No Image</div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($d['nama_buku']); ?></td>
                                    <td><?php echo htmlspecialchars($d['kategori']); ?></td>
                                    <td>Rp <?php echo number_format($d['harga_unit'], 0, ',', '.'); ?></td>
                                    <td><?php echo $d['qty']; ?></td>
                                    <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6" class="text-end">Total</th>
                                <th>Rp <?php echo number_format($pesanan['total'], 0, ',', '.'); ?></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <a href="user_pesanan.php" class="btn btn-secondary mt-4">← Kembali ke Daftar Pesanan</a>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>