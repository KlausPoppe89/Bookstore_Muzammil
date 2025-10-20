<?php
require "koneksi.php";

$queryKategori = mysqli_query($conn, "SELECT * FROM kategori");

if (isset($_GET['keyword'])) {
    $keyword = mysqli_real_escape_string($conn, $_GET['keyword']);
    $queryBuku = mysqli_query($conn, "SELECT * FROM buku WHERE nama LIKE '%$keyword%'");
} else if (isset($_GET['kategori'])) {
    $kategori = mysqli_real_escape_string($conn, $_GET['kategori']);
    $queryGetKategoriId = mysqli_query($conn, "SELECT id FROM kategori WHERE nama='$kategori'");

    if (mysqli_num_rows($queryGetKategoriId) > 0) {
        $kategoriId = mysqli_fetch_array($queryGetKategoriId);
        $queryBuku = mysqli_query($conn, "SELECT * FROM buku WHERE kategori_id='$kategoriId[id]'");
    } else {
        // Jika kategori tidak ditemukan, tampilkan pesan
        $queryBuku = mysqli_query($conn, "SELECT * FROM buku WHERE 1=0");
        $error_kategori = "Kategori '$kategori' tidak ditemukan";
    }
} else {
    $queryBuku = mysqli_query($conn, "SELECT * FROM buku");
}

$countData = mysqli_num_rows($queryBuku);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buku</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>
    <!-- banner -->
    <div class="container-fluid banner-buku d-flex align-items-center">
        <div class="container">
            <h1 class="text-white text-center">Katalog Buku</h1>
        </div>
    </div>
    <!-- body -->
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-3 mb-5">
                <h3>Kategori</h3>
                <ul class="list-group">
                    <?php
                    // Reset pointer hasil query kategori
                    mysqli_data_seek($queryKategori, 0);
                    while ($kategori = mysqli_fetch_array($queryKategori)) {
                    ?>
                        <a class="no-decoration" href="buku.php?kategori=<?php echo urlencode($kategori['nama']); ?>">
                            <li class="list-group-item"><?php echo $kategori['nama']; ?></li>
                        </a>
                    <?php } ?>
                </ul>
            </div>
            <div class="col-lg-9">
                <h3 class="text-center mb-3">Buku</h3>
                <?php if (isset($error_kategori)): ?>
                    <div class="alert alert-warning text-center">
                        <?php echo $error_kategori; ?>
                    </div>
                <?php endif; ?>
                <div class="row">
                    <?php
                    if ($countData < 1) {
                    ?>
                        <h4 class="text-center mt-5">Buku yang anda cari tidak tersedia</h4>
                    <?php
                    }
                    ?>
                    <?php
                    if ($countData > 0) {
                        mysqli_data_seek($queryBuku, 0);
                    }
                    while ($buku = mysqli_fetch_array($queryBuku)) {
                    ?>
                        <div class="col-md-3 mb-4">
                            <div class="card h-100">
                                <div class="image-box">
                                    <img src="image/<?php echo $buku['foto']; ?>" class="card-img-top" alt="...">
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo $buku['nama']; ?></h5>
                                    <p class="card-text text-truncate"><?php echo $buku['detail']; ?></p>
                                    <p class="card-text text-harga"><?php echo $buku['harga']; ?></p>
                                    <a href="buku-detail.php?nama=<?php echo urlencode($buku['nama']); ?>" class="btn warna2 text-white">Lihat Detail</a>
                                    <form action="proses-keranjang.php" method="post" class="d-inline">
                                        <input type="hidden" name="buku_id" value="<?php echo $buku['id']; ?>">
                                        <input type="hidden" name="action" value="add">
                                        <button type="submit" class="btn btn-success btn-sm mt-2">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <?php require "footer.php"; ?>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>

</html>