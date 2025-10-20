<?php
require "koneksi.php";

$nama = htmlspecialchars($_GET['nama']);
$queryBuku = mysqli_query($conn, "SELECT * FROM buku WHERE nama='$nama'");
$buku = mysqli_fetch_array($queryBuku);

$queryBukuTerkait = mysqli_query($conn, "SELECT * FROM buku WHERE kategori_id='$buku[kategori_id]' AND id!='$buku[id]' LIMIT 4");
$bukuTerkait = mysqli_fetch_array($queryBukuTerkait);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Buku</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="fontawesome/css/fontawesome.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <?php include "navbar.php"; ?>

    <div class="container-fluid py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 mb-5">
                    <img src="image/<?php echo $buku['foto']; ?>" class="w-100" alt="">
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <h1><?php echo $buku['nama']; ?></h1>
                    <p class="fs-5">
                        <?php echo $buku['detail']; ?>
                    </p>
                    <p class="text-harga">
                        <?php echo $buku['harga']; ?>
                    </p>
                    <p class="fs-5">
                        Status ketersediaan : <strong><?php echo $buku['ketersediaan_stok']; ?></strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- buku terkait -->
    <div class="container-fluid py-2 warna2">
        <div class="container">
            <h2 class="text-center text-white mb-5">Buku Terkait</h2>

            <div class="row">
                <?php
                // Reset pointer dan loop melalui hasil query
                mysqli_data_seek($queryBukuTerkait, 0); // Reset pointer jika diperlukan
                while ($data = mysqli_fetch_array($queryBukuTerkait)) {
                ?>
                    <div class="col-md-6 col-lg-3 mb-3">
                        <a href="buku-detail.php?nama=<?php echo $data['nama']; ?>">
                            <img src="image/<?php echo $data['foto']; ?>" class="img-fluid img-thumbnail produk-terkait-image" alt="">
                        </a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>


    <script src="bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="fontawesome/js/all.min.js"></script>
</body>

</html>