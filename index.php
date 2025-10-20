<?php
require "koneksi.php";
$queryBuku = mysqli_query($conn, "SELECT id, nama, harga, foto, detail FROM buku LIMIT 6");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Buku | Home</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>

    <!-- banner -->
    <div class="container-fluid banner d-flex align-items-center">
        <div class="container text-center text-white tulisan-banner">
            <h1>Selamat Datang di Toko Buku</h1>
            <h5>Jelajahi dunia pengetahuan melalui koleksi buku terbaik kami</h5>
        </div>
    </div>

    <!-- highlighted kategori -->
    <div class="container-fluid py-5">
        <div class="container text-center">
            <h3>Kategori Terlaris</h3>

            <div class="row mt-5">
                <div class="col-md-4 mb-4">
                    <div class="highlighted-kategori kategori-buku d-flex justify-content-center align-items-center">
                        <h4 class="text-white"><a class="no-decoration" href="buku.php?kategori=Novel+%26amp%3B+Fiksi">Novel & Fiksi</a></h4>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="highlighted-kategori kategori-buku2 d-flex justify-content-center align-items-center">
                        <h4 class="text-white"><a class="no-decoration" href="buku.php?kategori=Bisnis+%26amp%3B+Ekonomi">Bisnis & Ekonomi</a></h4>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="highlighted-kategori kategori-buku3 d-flex justify-content-center align-items-center">
                        <h4 class="text-white"><a class="no-decoration" href="buku.php?kategori=Sejarah+%26amp%3B+Budaya">Sejarah & Budaya</a></h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- buku -->
    <div class="container-fluid py-5">
        <div class="container text-center">
            <h3>Buku</h3>

            <div class="row mt-5">
                <?php while ($data = mysqli_fetch_array($queryBuku)) { ?>
                    <div class="col-sm-6 col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="image-box">
                                <img src="image/<?php echo $data['foto']; ?>" class="card-img-top" alt="...">
                            </div>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $data['nama']; ?></h5>
                                <p class="card-text text-truncate"><?php echo $data['detail']; ?></p>
                                <p class="card-text text-harga"><?php echo $data['harga']; ?></p>
                                <a href="buku-detail.php?nama=<?php echo $data['nama']; ?>" class="btn warna2 text-white">Lihat Detail</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <a class="btn btn-outline-primary mt-3" href="buku.php">See More</a>
        </div>
    </div>
    <?php require "footer.php"; ?>
    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>

</html>