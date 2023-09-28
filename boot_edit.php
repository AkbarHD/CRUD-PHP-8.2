<?php
session_start();
// cek apakah user udh login atau blm? jika blm maka kita arahkan ke form login suru dia masuk lewat form login
if (!isset($_SESSION["login"])) {
    header('Location: boot_login.php');
    exit;
}


require "boot_funct.php";
$id = $_GET["id"];
$mhs = tampil("SELECT * FROM mahasiswa WHERE id = $id")[0]; // ini knp 0? krn ada lapisan terluan yg indeksnya 0. dan variabel mhs ini berfngsi utk menampilan datan nama,nrp,jurusan dll ssuai id

if (isset($_POST["kirim"])) {
    if (edit($_POST) > 0) {
        echo "
            <script>
                alert('data berhasil diubah');
                document.location.href = 'boot_db.php';
            </script>
        ";
    } else {
        echo "
            <script>
                alert('data gagal diubah');
                document.location.href = 'boot_db.php';
            </script>
        ";
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Noto+Sans+Tangsa:wght@500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<style>
    body {
        font-family: 'DM Serif Display', serif;
    }

    img {
        width: 60px;
        height: 40px;
    }
</style>

<body>
    <div class="container-fluid " style="background-color: #22223b; width:100%; height:100vh; ">
        <div class="container">
            <div class="row">
                <div class="col-6 mt-5">
                    <img src="img/Wavy_Tech-28_Single-10.jpg" style="width: 400px; height: 400px;">
                </div>
                <div class="col-6 mt-5">
                    <form action="" method="post" enctype="multipart/form-data">
                        <h3 class="text-center mb-4 text-white">Ubah Data</h3>
                        <input type="hidden" name="id" value="<?= $mhs["id"] ?>"> 
                        <input type="hidden" name="gambarlama" value="<?= $mhs["gambar"] ?>"> <!-- ini type hidden ngambil dari database -->
                        <input class="form-control mb-3" type="text" name="nama" placeholder="nama" value="<?= $mhs["nama"] ?>">
                        <input class="form-control mb-3" type="text" name="nrp" placeholder="nrp" value="<?= $mhs["nrp"] ?>">
                        <input class="form-control mb-3" type="text" name="email" placeholder="email" value="<?= $mhs["email"] ?>">
                        <input class="form-control mb-3" type="text" name="jurusan" placeholder="jurusan" value="<?= $mhs["jurusan"] ?>">
                        <div class="d-flex">
                            <img class="mx-1" src="img/<?= $mhs["gambar"]; ?>"> <!-- ini utk ngasi tau gambar sebelum di edit -->
                            <input class="form-control mx-1" type="file" name="gambar" placeholder="gambar">
                        </div>
                        <button type="submit" name="kirim" class="btn btn-warning mt-3 w-100">Ubah Data</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>