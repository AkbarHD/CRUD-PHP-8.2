<?php
session_start();
// cek apakah user udh login atau blm? jika blm maka kita arahkan ke form login suru dia masuk lewat form login
if (!isset($_SESSION["login"])) {
  header('Location: boot_login.php');
  exit;
}

require 'boot_funct.php'; // karean kita requeqri boot_funct maka kita bisa ambil semua data yang ada di boot_funct

// konfigurasi pagination
$jumlahDataPerhalaman = 3; // kita pengen ada brp data awal yang ingin ditampilkan
$result = mysqli_query($connect, "SELECT * FROM mahasiswa"); // nah lalu kita hitung semua data yg ada di db ada berapa
$jumlahData = mysqli_num_rows($result);

$jumlahHalaman = ceil($jumlahData / $jumlahDataPerhalaman); // ceil utk membulatkan ke atas

// menentukan halaman, kita sedang di halaman berapa
if (isset($_GET["halaman"])) { // jika halaman nya di set 
  $halamanAktif = $_GET["halaman"];
} else {
  $halamanAktif = 1;
}
// var_dump($halamanAktif);
// ini utk mengatur jika dia di halaman 1 maka data yang akan di tampilkan dari indeks ke brp? contoh, misal dia di halaman 2, dan data perhalaman 2 brarti data yang di tampilakan adalah indeks ke 2
$awalData = ($jumlahDataPerhalaman * $halamanAktif) - $jumlahDataPerhalaman;


$mahasiswa = tampil("SELECT * FROM mahasiswa LIMIT $awalData, $jumlahDataPerhalaman");



// ketika tombol kirim di pencet kita akan timpa dgn data mahasiswa
if (isset($_POST["kirim"])) {
  // ambil apapun ya di ketik oleh user masukan ke function cari
  //  $mahasiswa ini harus sama yang di atas bekas query utk menampilkan semua data karena utk menimpa
  $mahasiswa = cari($_POST["keyword"]);
}
?>

<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bootstrap demo</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Noto+Sans+Tangsa:wght@500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">
</head>
<style>
  body {
    font-family: 'DM Serif Display', serif;
  }

  img {
    width: 80px;
    height: 60px;
    border-radius: 50%;
  }

  .kecil {
    font-size: 13px !important;
  }
</style>

<body>
  <div class="container-fluid">
    <div class="container">

      <!-- header -->
      <div class="row py-2">
        <div class="col-6 offset-3"> <!-- 6 + 3 + 3 = 12 -->
          <h3 class="text-start">Daftar Nama Yang Lolos Seleksi SNBT 2023</h3>
        </div>

        <div class="col-3 text-end">
          <a class="btn btn-secondary " href="logout.php">Log-out</a>
        </div>
      </div>
      <!-- header -->

      <!-- tombol tambah data & search -->
      <div class="row pt-5">

        <div class="col-5">
          <a href="boot_tambah.php" class="btn btn-primary rounded-3 mb-2"><i class="bi bi-plus"></i>Tambah Data</a>
        </div>

        <div class="col-4">
          <?php for ($i = 1; $i <= $jumlahHalaman; $i++) : ?>

            <?php if ($i == $halamanAktif) : ?>
              <a href="?halaman=<?= $i; ?>" class="btn btn-danger"> <?= $i; ?> </a>
            <?php else : ?>
              <a href="?halaman=<?= $i; ?>" class="me-2 text-decoration-none text-dark"> <?= $i; ?> </a>
            <?php endif; ?>

          <?php endfor;  ?>
        </div>

        <div class="col-3">
          <form action="" method="post">
            <div class="input-group mb-3">
              <input type="text" class="form-control border-1 border-dark" placeholder="Search..." name="keyword" autofocus>
              <button class="btn btn-warning text-white" type="submit" name="kirim">search</button>
            </div>
          </form>
        </div>



      </div>


      <!-- tombol tambah data & search -->

      <!-- table -->
      <table class="table table-hover table-striped">
        <thead class="table-dark text-white text-center kecil">
          <th>No</th>
          <th>Aksi</th>
          <th>Gambar</th>
          <th>Nama</th>
          <th>NRP</th>
          <th>Email</th>
          <th>Jurusan</th>
        </thead>
        <?php $i = 1 + $awalData; ?>
        <?php foreach ($mahasiswa as $mhs) : ?>
          <tbody class="table-bordered bg-light kecil">
            <td><?= $i; ?></td>
            <td>
              <!-- knp dstu id? krn kita ingin menghapus sesuai id -->
              <a href="boot_edit.php?id=<?= $mhs["id"]; ?>" class="btn btn-success kecil"><i class="bi bi-pen-fill"></i> Edit</a>
              <a href="boot_delete.php?id=<?= $mhs["id"]; ?>" onclick="return confirm('ingin menghapus data?');" class="btn btn-danger kecil"><i class="bi bi-trash3"></i> Delete</a>
            </td>
            <td><img src="img/<?= $mhs["gambar"]; ?>"></td>
            <td><?= $mhs["nama"]; ?></td>
            <td><?= $mhs["nrp"]; ?></td>
            <td><?= $mhs["jurusan"]; ?></td>
            <td><?= $mhs["email"]; ?></td>
          </tbody>
          <?php $i++ ?>
        <?php endforeach; ?>
      </table>
      <!-- tabble -->

    </div>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>