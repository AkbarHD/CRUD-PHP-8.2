<?php
require 'boot_funct.php';

if (isset($_POST["register"])) {

    if (register($_POST) > 0) {
        echo "
                <script>
                    alert('user baru berhasil ditambahkan');
                    document.location.href = 'boot_login.php';
                    
                </script>
            ";
    } else {
        echo mysqli_error($connect); // jika gagal login tampilkan pesan kesalahan 
    }
}

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Noto+Sans+Tangsa:wght@500;600;700&family=Roboto:ital,wght@0,300;0,400;0,500;0,700;0,900;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>
<style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Montserrat', sans-serif;
    }

    .tengah {
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
    }

    .shadow {
        box-shadow: 5px 15px 5px black;
    }

    .bordered {
        border-top: none;
        border-left: none;
        border-right: none;
        border-bottom: 1px solid black;
        outline: none;
        margin-bottom: 10px;
        width: 80% !important;
    }

    ::-webkit-input-placeholder {
        font-weight: 300;
        font-size: 15px;
    }

    .tipis {
        font-weight: 300;
        font-size: 12px !important;
        margin-top: 4px;
        text-align: center !important;
    }

    a {
        font-size: 13px;
        font-weight: 500;
    }
</style>

<body>
    <div class="container-fluid">
        <div class="container d-flex justify-content-center align-items-center" style="height: 100vh;"> <!-- biar ke tengah di kasi tinggi 100vh -->
            <div class="row shadow">

                <div class="col-6">
                    <img src="img/gunung.jpg" class="img-fluid" style="height: 400px; width: 500px; "> <!-- gambar harus di seting col & heigh, width -->
                </div>

                <div class="col-6 mt-5">
                    <form action="" method="post">
                        <h5 class="text-center">Create account</h5>

                        <div class="col text-center">
                            <input type="text" name="username" placeholder="username" class="bordered p-2" autocomplete="off" require>
                        </div>

                        <div class="col text-center">
                            <input type="password" name="pass" placeholder="password" class="bordered p-2" autocomplete="off" require>
                        </div>

                        <div class="col text-center">
                            <input type="password" name="pass2" placeholder="confirm password" class="bordered p-2" autocomplete="off" require>
                        </div>

                        <div class="col text-center">
                            <button type="submit" name="register" class="btn btn-secondary w-75 mt-4" >Sign up</button>
                        </div>
                        
                        <div class="col d-flex mt-3 offset-2">
                            <p class="tipis mx-1">Already have an account? </p>
                            <a href="boot_login.php" class="text-decoration-none text-black">Log in here</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
</body>

</html>