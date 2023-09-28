<?php
session_start();
require "boot_funct.php";
// cek apakah user udh login atau blm? jika blm maka kita arahkan ke form login suru dia masuk lewat form login
if (!isset($_SESSION["login"])) {
    header('Location: boot_login.php');
    exit;
}

    $id = $_GET["id"];
     if (delete($id) > 0) {
        echo "
            <script>
                alert('Data berhasil di hapus');
                document.location.href = 'boot_db.php';
            </script>                                       
            ";
    }else {
        echo "
            <script>
                alert('Data gagal di hapus');
                document.location.href = 'boot_db.php';
            </script>
        ";
}
