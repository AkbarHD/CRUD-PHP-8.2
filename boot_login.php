<?php
session_start();
require 'boot_funct.php';

//kita ingin cek ada ga coikie nya
if (isset($_COOKIE['id']) && isset($_COOKIE['key'])) {
    $id = $_COOKIE['id'];
    $key = $_COOKIE['key'];

    // ambil usernmae berdasarkan id
    $result = mysqli_query($connect, "SELECT username FROM user WHERE id = $id");
    $row = mysqli_fetch_assoc($result);
    // bandingkan apakah key = username, itu sama dgn username yg ada di database
    if ($key === hash('sha256', $row['username'])) {
        $_SESSION['login'] = true;
    }
}

// cek jika user sudah login maka kita paksa ke halaman boot_db
if (isset($_SESSION["login"])) {
    header('Location: boot_db.php');
    exit;
}




if (isset($_POST["login"])) {
    $username_login = $_POST["username"];
    $password_login = $_POST["pass"];

    // bacanya : cek masuk ke database, cari tabel user isinya usernamenya sama atau tdk dgn username yg di input melalui form
    $result = mysqli_query($connect, "SELECT * FROM user WHERE username = '$username_login'");

    // mysqli_num_rows itu utk mengembalikan nilai 1 dan 0
    // cek apakah apakah ketemu 1 baris? jika ketemu maka akan mengembalikan nilai 1
    if (mysqli_num_rows($result) === 1) {

        // cek password
        $row = mysqli_fetch_assoc($result); // nah di dlm $row menampung id, usernmae dan password
        if (password_verify($password_login, $row["password"])) { // knp dstu password : karena passowrd dari database
            // set session
            $_SESSION["login"] = true;  // bikin set session yg key nya login : artinya dia udh pernah login atau blm

            // cek remember me
            if (isset($_POST['remember'])) {
                // buat cookcie
                setcookie('id', $row['id'], time() + 70); // jd ini kita ngambil satu" dari data si user. nb = kalo bisa namanya jgn id agar tdk ketahuan oleh hacker
                setcookie('key', hash('sha256', $row['username']), time() + 70); // usernamenya kita enkripsi dgn menggunakan algo sha256
            }
        }
        header('Location: boot_db.php');
        exit;
    } else {
        $error = true;
    }
}
// jika benar dia masuk ke if dan jika salah dia masuk ke $error



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Noto+Sans+Tangsa:wght@500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
</head>
<style>
    .banner {
        /* background-color: #0093E9; */
        background-image: linear-gradient(160deg, #0093E9 0%, #80D0C7 100%);
        width: 100%;
        height: 100vh;
    }

    .login-page {
        background-color: white;
        border-radius: 13px;
        width: 380px;
        height: 410px;
    }

    input {
        border-right: none;
        border-left: none;
        border-top: none;
        border-bottom: 1px solid grey;
        padding: 10px;
        outline: none;
        margin-bottom: 15px;
        width: 100% !important;
        /* agar bordernya lebar ke smaping */
    }

    p {
        font-size: 13px;
    }

    a {
        color: #0093E9;
        text-decoration: none;
    }

    label {
        width: 100%;
        /* margin-left: 100px; */
    }

    .me {
        margin-left: -25px;
    }
</style>

<body>
    <div class="container-fluid banner">
        <div class="container d-flex justify-content-center align-items-center" style="height: 100vh; ">

            <div class="row">
                <div class="col-4 login-page ">
                    <form action="" method="post">
                        <h1 class="text-center mt-3">Login</h1>
                        <hr>
                        <div class="col mx-4    ">
                            <input type="text" name="username" placeholder="username" autocomplete="off">
                        </div>
                        <div class="col mx-4">
                            <input type="password" name="pass" placeholder="password" autocomplete="off">
                        </div>
                        <?php if (isset($error)) : ?>
                            <p class="text-center text-danger">Password / username anda salah!</p>
                        <?php endif; ?>
                        <div class="col text-center mt-4">
                            <button type="submit" name="login" id="btn" class="btn btn-primary w-75 rounded-3">Login</button>
                        </div>
                        <!-- dsni kita menggunakan class row & col agar elemetnya ke samping  -->
                        <div class="row mt-2 mx-0"> <!-- mx0 agar geser ke kanan dikit -->
                            <div class="col-2">
                                <input type="checkbox" class="mt-2" name="remember" id="remember">
                            </div>
                            <div class="col-6">
                                <label for="remember" class="me">remember me</label> <!-- class me utk geser ke kiri -25 -->
                            </div>
                        </div>

                        <div class="col text-center mt-4">
                            <p>belum punya akun? <a href="boot_regis.php">registrasi</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>