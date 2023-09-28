<?php
// ------------------tampil----------------------
// urutannya = "nama host", "username", "password", "nama database kita";
$connect = mysqli_connect("localhost", "root", "", "belajarphp") or die(mysqli_error($connect)); // cara connect ke database

function tampil($query)
{
    global $connect; // kenalin variabel connect ke dalam function tampil

    $result = mysqli_query($connect, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }
    return $rows;
}

//----------------------------- tambah ------------------------
function tambah($tambah_data)
{
    global $connect;

    $nama = htmlspecialchars($tambah_data["nama"]);
    $nrp = htmlspecialchars($tambah_data["nrp"]);
    $jurusan = htmlspecialchars($tambah_data["jurusan"]);
    $email = htmlspecialchars($tambah_data["email"]);
    // upload gambar dlu, kalau upload gambar nya sukses maka jalankan yg lainnya
    $gambar = upload();
    if (!$gambar) {
        return false;  // jika tdk ada gambar maka, script bwh nya tdk di jalankan
    }

    $data = "INSERT INTO mahasiswa VALUES ('', '$nama', '$nrp', '$jurusan', '$email', '$gambar')";
    mysqli_query($connect, $data);
    return mysqli_affected_rows($connect); // mengembalikan nilai : jika gagal bernilai 0 & jika benar bernilai 1
}

// ----------------------------upload------------------------
function upload()
{
    // ada eksetensi wajib yg harus di masukan 
    $namafile = $_FILES['gambar']['name']; // utk mngetahui nama gambar
    $ukuranfile = $_FILES['gambar']['size']; // file utk membatasi ukuran file 
    $error = $_FILES['gambar']['error']; // erorr dsni utk mngathui ada gmbr yg di upload atau tdk
    $tmpName = $_FILES['gambar']['tmp_name']; // tmpt penyimpanan smntara

    // cek apakah user memasukan gambar atau tdk, jika tdk mengupload maka kita lakukan fungsi yg bwh. kita ingin user wajib upload foto
    if ($error === 4) { // 4 dstu menjelaskan tdk ada gambar yg di opload, jika user tdk upload gambar maka keluar pesan yg bwh
        echo "   
                <script>
                    alert('Pilih gambar terlebih dahulu ya');
                    alert('Data gagal di tambahkan silahkan isi ulang ya!!')
                    document.location.href = 'boot_tambah.php';
                </script>
            "; // nb penjelasaan : knp dsni kta bkin erro = 4? kan di atas udh ada if not gambar?
        // jbwn : jd misal dstu kan error = 4, yg artinya tdk ada gambar yg dmasukan? nah kalo kita ga pake if not gmbar? itu ttp di jalankan... makanya kita harus mnggunakan if not gambar agar tdk di jalankan 
        return false; // stlh pesan alert tampil kita berherntikan jg functionya yaitu return false
    }

    //-----------------------------------------------------------------------
    // misalnya kita mau ngecek yg di upload itu gambar atau bkn karena kita mewajibkan user utk mengupload gambar
    // fungsi ini utk mncegah apabila ada user jahat yg mengupload file yg brisi virus dan bisa merusak sistem kita
    // langakah pertama kita bisa set file apa saja yg boleh utk di opload 
    $nama_file_gambar_valid = ['jpg', 'jpeg', 'png']; // ini eksetensi file yg boleh di upload selain ini tdk boleh
    // expload adlh fngsi utk memecah sbuah string mnjdi array
    $ekstensi_gambar = strtolower(pathinfo($namafile, PATHINFO_EXTENSION)); // pnjelsan knp end = misal ada nama file yg namanya akbar.hossam.jpg, nah fngsi end itu buat mengambil jpg, kalau tdk pakai end maka dia hanya mengambil akbar.hossam, jpg nya tdk di ambil
    // penjelasan dikit : knp dstu strlowe? krn misal ada file yg namanya akbar.JPG, nah dstu kan yg boleh hanya jpg yg huruf kecil, nah kita bisa memaksa data yg masuk jadi huruf kecil smua dgn menggunakan strtolower
    if (!in_array($ekstensi_gambar, $nama_file_gambar_valid)) {  // in_array brfungsi buat ngecek ada ga sbuah string di dlm sbuah array.   fungsi in_array akn mnghasilkan true jika ada dan mnggasilkan false jika tdk ada
        // di atas bacanya kalau jika filenya tdk ada sesuai syarat jpg,jpeg,png mka lakukan pesan bwh
        echo "
                <script>
                    alert('File yang anda upload bukan gambar');
                    alert('Data gagal di tambahkan silahkan isi ulang ya!!')
                    document.location.href = 'boot_tambah.php';
                </script>";
        return false;
    }

    //cek ukuran foto terlalu besar
    if ($ukuranfile > 1000000) {
        echo "
                <script>
                    alert('Ukuran size foto anda lebih dari 1mb');
                    alert('Data gagal di tambahkan silahkan isi ulang ya!!')
                    document.location.href = 'boot_tambah.php';
                </script>";
        return false;
    }

    // jika lolos pengecekan gambar siap di upload
    // misal ada nama file foto dari user 1 & user 2 namanya sma, nah itu bisa merusak foto aslinya, krn dlm sbuah database tdk boleh ada nama file yg sama, nah ini cara mengatasinya
    $namafilebaru = uniqid(); // uniqid itu akn mmbangkitkan string random angka yg akn jd nma gmbr kita, (intinya nnt nama file gambar kita isinya string angka random)
    $namafilebaru .= '.';
    $namafilebaru .= $ekstensi_gambar;
    move_uploaded_file($tmpName, 'img/' . $namafilebaru);
    return $namafilebaru; // buat apa di return nama filenya? jadi kalo gambar nya berhasil di upload isi dari $gambar adalah nama file nya sehingga gambar bisa di masukan kdlm insert into     
}

// ----------------------------------------Delete------------------------------
function delete($hapus_data)
{
    global $connect;
    // tmbhn biar enak pas hps datanya maka kita jg ikut mnghpus fotonya agar tdk bnyk makan penyimpanan 
    $file = mysqli_fetch_assoc(mysqli_query($connect, "SELECT *FROM mahasiswa WHERE id = $hapus_data")); // yambahan dari coment yt
    unlink('img/' . $file["gambar"]); // yambahan dari coment yt
    $hapus = "DELETE FROM mahasiswa WHERE id = $hapus_data";
    mysqli_query($connect, $hapus);
    return mysqli_affected_rows($connect);
}

//-------------------------------- edit ---------------------
function edit($edit_data)
{
    global $connect;

    $id = $edit_data["id"]; // dsni tambakah id, krn kita ingin mengedit from sesuai dgn id, krn jika tdk dngn id maka semua data akan ganti
    $nama = htmlspecialchars($edit_data["nama"]);
    $nrp = htmlspecialchars($edit_data["nrp"]);
    $jurusan = htmlspecialchars($edit_data["jurusan"]);
    $email = htmlspecialchars($edit_data["email"]);
    $gambarlama = htmlspecialchars($edit_data["gambarlama"]);

    // cek apakah user pilih gambar baru atau tidak 
    if ($_FILES['gambar']['error'] == 4) { // jika user tdk melakan upload gambar baru maka ya gambar lama valuenya 
        $gambar = $gambarlama;
    } else { // jika user mencet upload gambar maka jalankan fungsi upload
        $gambar = upload();
    }


    $edit = "UPDATE mahasiswa SET
                    nama = '$nama',
                    nrp = '$nrp',
                    jurusan = '$jurusan',
                    email = '$email',
                    gambar = '$gambar'
                    WHERE id = $id";
    mysqli_query($connect, $edit);
    return mysqli_affected_rows($connect); // mengembalikan nilai : jika gagal bernilai 0 & jika benar bernilai 1
}

// ----------------------------seacrh-----------------------
function cari($cari_data)
{
    $cari = "SELECT * FROM mahasiswa
                    WHERE
                    nama LIKE '%$cari_data%' OR
                    nrp LIKE '%$cari_data%' OR
                    jurusan LIKE '%$cari_data%' OR
                    email LIKE '%$cari_data%'
                ";
    // like brfungsi utk fitur seacrh ketika kita mncari data yang hanya tau nama dpn ny saja
    // dan % berfungsi utk kita bisa jg mencari data dgn mengetik nama belakangan saja
    return tampil($cari); // mengembalikan nilai tampil dan $cari
}

// -------------------------- register -----------------------
function register($data)
{
    global $connect;

    $username = strtolower(stripslashes($data["username"])); // stripslashes brfngsi utk ketika user mnuliskan username dgn backslas itu akan dihilangkan 
    $password = mysqli_real_escape_string($connect, $data["pass"]);  // kalau mau menggunakan mysqli_real_escape, hrs mnggunakan 2 parameter yaitu $connect & $parameter pd function tersbut
    $password2 = mysqli_real_escape_string($connect, $data["pass2"]);

    // utk mengatasi string kosong
    if (empty($username) || empty($password)) {
        echo "
                <script>
                    alert('Form tidak boleh kosong!');
                </script>
            ";
        return false;
    }

    // utk mengtasi string yg berisi spasi
    if (preg_match_all('/\s/', $username)) {
        echo "<script>
                    alert('tidak boleh ada spasi saat melakuan registrasi');
              </script>";
        return false;
    }

    // cek username sdh ada or blm yg ada di sama dengn di dlm database 
    $result = mysqli_query($connect, "SELECT username FROM user WHERE username = '$username' "); // ini bacanya : cari database username dari tabel user yg namanya username 

    // kita masukan & cocokin sm yg ada didatabase
    if (mysqli_fetch_assoc($result)) {
        echo "
                <script>
                    alert('maaf username ini sudah terdaftar, coba ganti nama username lain')
                </script>";
        return false; // hentikan program
    }

    // cek passwordnya sama atau tidak
    if ($password !== $password2) {
        echo "
                <script>
                    alert('password tidak sesuai');
                </script>
            ";
        return false;
    }


    // enkripsi password
    $password_enkripsi = password_hash($password, PASSWORD_DEFAULT);

    // Tambahkan user baru ke database
    mysqli_query($connect, "INSERT INTO user VALUES ('', '$username', '$password_enkripsi')");

    return mysqli_affected_rows($connect);
}
