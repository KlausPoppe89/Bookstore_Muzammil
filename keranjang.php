<?php
require_once 'koneksi.php';
$cart = $_SESSION['cart'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-4">
        <h3>Keranjang Belanja</h3>
        <?php
        // Tampilkan notifikasi
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <?php if (empty($cart)): ?>
            <div class="alert alert-info">Keranjang kosong.</div>
        <?php else: ?>
            <form method="post" action="proses-keranjang.php">
                <input type="hidden" name="action" value="update">
                <table class="table table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Nama Buku</th>
                            <th>Harga</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total = 0;
                        foreach ($cart as $item):
                            $subtotal = $item['harga'] * $item['qty'];
                            $total += $subtotal;
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                <td>
                                    <input type="number" name="qty[<?php echo $item['buku_id']; ?>]"
                                        value="<?php echo $item['qty']; ?>" min="1"
                                        class="form-control" style="width:100px">
                                </td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <form action="proses-keranjang.php" method="post" style="display:inline">
                                        <input type="hidden" name="action" value="remove">
                                        <input type="hidden" name="buku_id" value="<?php echo $item['buku_id']; ?>">
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Hapus item dari keranjang?')">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot class="table-secondary">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total:</strong></td>
                            <td colspan="2"><strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong></td>
                        </tr>
                    </tfoot>
                </table>
                <div class="d-flex justify-content-between align-items-center">
                    <a href="buku.php" class="btn btn-outline-primary">Lanjut Belanja</a>
                    <div>
                        <button type="submit" class="btn btn-warning">Update Qty</button>
                        <a href="checkout.php" class="btn btn-success">Checkout</a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>