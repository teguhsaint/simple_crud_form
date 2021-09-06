<form method="POST" action="">

    <div class="mb-1">
        <label for="">Nama File</label>
        <input type="text" autocomplete="off" readonly name="namafile" value="koneksi" class="form-control" id="">
    </div>
    <div class="mb-1">
        <label for="">Host</label>
        <input type="text" autocomplete="off" name="host" value="localhost" class="form-control" id="">
    </div>
    <div class="mb-1">
        <label for="">Username</label>
        <input type="text" autocomplete="off" value="root" name="username" class="form-control" id="">
    </div>
    <div class="mb-1">
        <label for="">Password</label>
        <input type="text" autocomplete="off" name="passwords" class="form-control" id="">
    </div>

    <div class="mb-3">
        <label for="">DB Name</label>
        <input type="text" autocomplete="off" name="dbname" class="form-control" id="">
    </div>
    <div class="mb-1">
        <input type="submit" value="Create And Next" name="btn_simpan" class="btn btn-primary w-100" id="">
    </div>

</form>


<?php
session_start();
error_reporting(0);
if (isset($_POST['btn_simpan'])) {

    $direktori = 'result/config';
    $namafile = str_replace(" ", "_", $_POST['namafile']) . ".php";
    $buatfile = fopen($direktori . '/' . $namafile, "w+");
    if ($buatfile == false) {
        die("Tidak bisa membuat file, karena permission direktori tidak mengizinkan");
    } else {

        if ($_POST['passwords'] == NULL) {
            $kosong = '';
            $isifile = '<?php 
            $hostname =\'' . $_POST['host'] . '\'; 
            $username =\'' . $_POST['username'] . '\'; 
            $passwords =\'' . $kosong . '\';  
            $dbname =\'' . $_POST['dbname'] . '\'; 
            $koneksi = mysqli_connect($hostname, $username, $passwords, $dbname);
            ?>';
        } else {
            $isifile = '<?php $hostname =\'' . $_POST['host'] . '\'; 
            $username =\'' . $_POST['username'] . '\'; 
            $passwords =\'' . $_POST['passwords'] . ' \';  
            $dbname =\'' . $_POST['dbname'] . '\'; 
            $koneksi = mysqli_connect($hostname, $username, $passwords, $dbname);
            ?>';
        }
        fwrite($buatfile, "$isifile");
    }
    fclose($buatfile);

    $_SESSION['berhasil'] = 'disimpan di result/config/' . $namafile;
    header("location:index.php?p=buatform");
}


?>