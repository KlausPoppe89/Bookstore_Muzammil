<?php
require_once 'koneksi.php';
if (!is_logged()) {
    header("Location: login.php");
    exit;
}
$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
    header("Location: cart.php");
    exit;
}

// Hitung total
$total = 0;
foreach ($cart as $item) {
    $total += $item['harga'] * $item['qty'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>
    <div class="container mt-4">
        <h3>Checkout - Payment at Delivery</h3>

        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
            unset($_SESSION['error']);
        }
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
            unset($_SESSION['success']);
        }
        ?>

        <!-- RINGKASAN PESANAN -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">📦 Ringkasan Pesanan</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>Nama Buku</th>
                                <th>Harga</th>
                                <th>Qty</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart as $item): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($item['nama']); ?></td>
                                    <td>Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                                    <td><?php echo $item['qty']; ?></td>
                                    <td>Rp <?php echo number_format($item['harga'] * $item['qty'], 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="table-secondary">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td><strong>Rp <?php echo number_format($total, 0, ',', '.'); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- FORM DATA PENGIRIMAN -->
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">📝 Data Pengiriman</h5>
            </div>
            <div class="card-body">
                <form action="proses-checkout.php" method="post">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama Penerima</label>
                            <input type="text" name="nama" class="form-control" required
                                value="<?php echo isset($_SESSION['user']['nama']) ? $_SESSION['user']['nama'] : ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="text" name="telepon" class="form-control" required
                                placeholder="Contoh: 081234567890">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-control" rows="3" required
                            placeholder="Masukkan alamat lengkap pengiriman"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="pembayaran" class="form-control" required>
                            <option value="cod">Cash on Delivery (COD) - Bayar saat barang diterima</option>
                            <option value="transfer">Transfer Bank</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between">
                        <a href="keranjang.php" class="btn btn-secondary">← Kembali ke Keranjang</a>
                        <button type="submit" class="btn btn-success">✅ Place Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>

</html>