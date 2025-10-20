<?php
require_once 'koneksi.php';
?>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
    <div class="container">
        <a class="navbar-brand" href="index.php">Toko Buku</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMain">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item me-2">
                    <a class="nav-link" href="index.php">Home</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="buku.php">Buku</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pesanan.php">Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pesan.php">Pesan</a>
                </li>
                <li class="nav-item me-2">
                    <a class="nav-link" href="tentang-kami.php">Tentang Kami</a>
                </li>
            </ul>

            <!-- Form pencarian -->
            <form class="d-flex me-3" action="buku.php" method="get">
                <input name="keyword" class="form-control me-2" type="search" placeholder="Cari buku..."
                    value="<?php echo isset($_GET['keyword']) ? htmlspecialchars($_GET['keyword']) : ''; ?>">
                <button class="btn btn-success" type="submit">Cari</button>
            </form>

            <!-- Menu login / user -->
            <ul class="navbar-nav mb-2 mb-lg-0">
                <!-- Keranjang -->
                <li class="nav-item me-2">
                    <a class="nav-link" href="keranjang.php">
                        Keranjang
                        <span class="badge bg-danger">
                            <?php echo isset($_SESSION['cart']) ? array_sum(array_column($_SESSION['cart'], 'qty')) : 0; ?>
                        </span>
                    </a>
                </li>

                <!-- Dropdown user -->
                <?php if (is_logged()): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" href="#">
                            👤 <?php echo htmlspecialchars($_SESSION['user']['nama']); ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-danger" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
                    <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>