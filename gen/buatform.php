<?php

include 'result/config/koneksi.php';

$sql = "SHOW TABLES FROM $dbname";
$result = mysqli_query($koneksi, $sql);
$isifile = ''; ?>

<form method="POST" action="">
    <label class="form-label">Pilih Tabel</label>
    <div class="mb-3 ">
        <select class="form-select mb-3" id="checksource" name="tabel">
            <?php
            $totaltb = '';
            foreach ($result as $key) {
                $totaltb++;
            ?>
                <option><?= $key["Tables_in_$dbname"] ?></option>
            <?php   } ?>
        </select>

        <small>Total Tabel Tersedia<?= $totaltb ?></small>
    </div>
    <label class="form-label">Nama File</label>
    <input autocomplete="off" required class="form-control mb-3" placeholder="( tanpa .php )" type="text" name="namafile" id="">

    <label class="form-label">Pilih Jenis Crud</label>
    <div class="mb-3 ">
        <select class="form-select " name="jenis">
            <option value="tables">Table List</option>
            <option value="adddata">Add Data</option>
            <option value="editdata">Edit Data</option>
            <option value="deletedata">Delete Data</option>
        </select>
    </div>

    <div class="mb-3 ">
        <input autocomplete="off" required type="submit" class="btn btn-primary mb-3" name="btn_simpan" value="simpan" id="">
    </div>
    <div class="mb-3 text-center">
        <a href="index.php?p=database" class="link-primary w-100" id="">Back </a>
    </div>
</form>

<?php
session_start();

if (isset($_POST['btn_simpan'])) {

    if (isset($_POST['jenis'])) {
        $jenisfile = $_POST['jenis'];

        if ($jenisfile == 'adddata') {
            $tablename = $_POST['tabel'];
            $sql = "DESCRIBE $tablename";
            $result = mysqli_query($koneksi, $sql);
            $isifile .= '<form method="post" action="" class="row">';
            foreach ($result as $key) {
                if (str_contains($key['Type'], 'int(')) {
                    $isifile .= '<div class="col-sm-4 form-group mb-3">
                <label class="form-label">' . ucwords(str_replace('_', ' ', $key['Field'])) . '</label>
                <input type="number" class="form-control" name="' . $key['Field'] . '" placeholder="' . ucwords(str_replace('_', ' ', $key['Field'])) . '"></div>';
                } elseif (str_contains($key['Type'], 'varchar(')) {
                    $isifile .= '<div class="col-sm-4 form-group mb-3">
                <label class="form-label">' . ucwords(str_replace('_', ' ', $key['Field'])) . '</label>
                <input type="text" class="form-control" name="' . $key['Field'] . '" placeholder="' . ucwords(str_replace('_', ' ', $key['Field'])) . '"></div>';
                } elseif (str_contains($key['Type'], 'datetime') or str_contains($key['Type'], 'date')) {
                    $isifile .= '<div class="col-sm-4 form-group mb-3">
                <label class="form-label">' . ucwords(str_replace('_', ' ', $key['Field'])) . '</label>
                <input type="date" class="form-control" name="' . $key['Field'] . '" placeholder="' . ucwords(str_replace('_', ' ', $key['Field'])) . '"></div>';
                } elseif (str_contains($key['Type'], 'long')) {
                    $isifile .= '<div class="col-sm-4 form-group mb-3">
                <label class="form-label">' . ucwords(str_replace('_', ' ', $key['Field'])) . '</label>
                <textarea class="form-control" name="' . $key['Field'] . '" placeholder="' . ucwords(str_replace('_', ' ', $key['Field'])) . '"></textarea></div>';
                }
            }
            $isifile .= '<button type="submit" name="simpan" class="btn btn-primary">Simpan</button>';
            $isifile .= '</form>';

            $sql = "DESCRIBE $tablename";
            $result = mysqli_query($koneksi, $sql);
            $jumlahkolom = '';
            while ($a  = mysqli_fetch_row($result)) {
                $jumlahkolom++;
            }
            echo $jumlahkolom;

            $inset = '';
            $isifile .= '
            <?php
            if (isset($_POST[\'simpan\'])) {
            ';
            $jumlahsaatini = '';
            foreach ($result as $key) {
                $jumlahsaatini++;
                if ($jumlahsaatini < $jumlahkolom) {
                    $isifile .= '
                    $' . $key['Field'] . '= $_POST[\'' . $key['Field'] . '\'] ;';
                    $inset .=  ' \'$' . $key['Field'] . '\', ';
                } else {
                    $isifile .= '
                    $' . $key['Field'] . '= $_POST[\'' . $key['Field'] . '\'] ;';
                    $inset .= ' \'$' . $key['Field'] . '\'';
                }
            }
            $isifile .= '
            $sql = "INSERT INTO ' . $tablename . ' VALUES(' . $inset . ' )";';
            $isifile .= '
            $run_sql = mysqli_query($koneksi,$sql);
            }
            ?>
            ';
            $namafile = str_replace(" ", "_", $_POST['namafile']) . ".php";
            $buatfile = fopen('result/pages/' . $namafile, "w+");
            fwrite($buatfile, "$isifile");
            fclose($buatfile);
            $_SESSION['berhasil'] = 'disimpan di result/pages/' . $namafile;
        }

        if ($jenisfile == 'tables') {
            $tablename = $_POST['tabel'];
            $sql = "DESCRIBE $tablename";
            $result = mysqli_query($koneksi, $sql);
            $isifile .= '<table class="table table-bordered">';
            $isifile .= '<thead>';
            foreach ($result as $key) {
                $isifile .= '<th>' . $key['Field'] . '</th>';
            }
            $isifile .= '<th>Aksi</th>';
            $isifile .= '</thead>';
            $isifile .= '<tbody>';
            $isifile .= '
            <?php 
            include "config/koneksi.php";
            $sql = "SELECT * FROM ' . $tablename . '" ;
            $result = mysqli_query($koneksi,$sql) ;
            foreach ($result as $key) { ?>
            <tr>';
            foreach ($result as $key) {
                $isifile .= '<td><?= $key[\'' . $key['Field'] . '\'] ?></td>';
            }

            $isifile .= '<td><a href="#">Edit</a>&nbsp;&nbsp; <a href="#">Hapus</a> </td>';
            $isifile .=  '</tr>
            <?php } ?>';
            $isifile .= '</tbody>';
            $isifile .= '</table>';
            $namafile = str_replace(" ", "_", $_POST['namafile']) . ".php";
            $buatfile = fopen('result/pages/' . $namafile, "w+");
            fwrite($buatfile, "$isifile");
            fclose($buatfile);
            $_SESSION['berhasil'] = 'disimpan di result/pages/' . $namafile;
        }

        if ($jenisfile == 'editdata') {
            $headerTable = '';
            $tablename = $_POST['tabel'];
            $sql = "DESCRIBE $tablename";
            $resultedit = mysqli_query($koneksi, $sql);
            $isifile .= '<form method="post" action="" class="row" >
            ';
            $namaprimari = '';
            foreach ($resultedit as $keyField) {
                $headerTable = $keyField['Field'];

                if (str_contains($keyField['Key'], "PRI")) {
                    $namaprimari = $keyField['Field'];
                    $isifile .= '
                    <?php 
                    include "config/koneksi.php";
                    $carikey = $_GET[\'carikey\'];
                     $query = "SELECT * FROM ' . $tablename . ' WHERE ' . $keyField['Field'] . ' = \'$carikey \'"; 
                     $runsql = mysqli_query($koneksi,$query);
                     foreach ($runsql as $key) {
                     ?>';

                    $isifile .= '
                    <input type="hidden" class="form-control" name="' . $keyField['Field'] . '" value="<?= $key[\'' . $headerTable . '\'] ?>" placeholder="' . ucwords(str_replace('_', ' ', $keyField['Field'])) . '">';
                } else {
                    $isifile .= '
                    <div class="form-group mb-2">
                    <label class="form-label">' . ucwords(str_replace('_', ' ', $keyField['Field'])) . '</label>
                    <input type="text" class="form-control" name="' . $keyField['Field'] . '" value="<?= $key[\'' . $headerTable . '\'] ?>" placeholder="' . ucwords(str_replace('_', ' ', $keyField['Field'])) . '"></div>
                    <?php  ?>
                    ';
                }
            }
            $isifile .= '<button type="submit" name="update" class="btn btn-primary">Update</button>';
            $isifile .= '</form>';

            $inset = '';
            $isifile .= '
            <?php }
            if (isset($_POST[\'update\'])) {
            ';

            $jumlahkoloms = '';
            foreach ($resultedit as $jk) {
                $jumlahkoloms++;
            }

            $jumlahsaatinis = 0;
            foreach ($resultedit as $key) {
                $jumlahsaatinis++;
                if ($jumlahsaatinis < $jumlahkoloms) {
                    $isifile .= '
                    $' . $key['Field'] . '= $_POST[\'' . $key['Field'] . '\'] ;';
                    $inset .=   str_replace("$", "",  $key['Field']) . '= \'$' . $key['Field'] . '\', ';
                } else {
                    $isifile .= '
                    $' . $key['Field'] . '= $_POST[\'' . $key['Field'] . '\'] ;';
                    $inset .=   str_replace("$", "",  $key['Field']) . '=\'$' . $key['Field'] . '\'';
                }
            }
            $isifile .= '
            $sql = "UPDATE ' . $tablename . ' SET ' . $inset . ' WHERE ' . $namaprimari . '  =  $carikey";';
            $isifile .= '
            $run_sql = mysqli_query($koneksi,$sql);';

            $isifile .= ' } ?>';


            $namafile = str_replace(" ", "_", $_POST['namafile']) . ".php";
            $buatfile = fopen('result/pages/' . $namafile, "w+");
            fwrite($buatfile, "$isifile");
            fclose($buatfile);
            $_SESSION['berhasil'] = 'disimpan di result/pages/' . $namafile;
        }

        if ($jenisfile == 'deletedata') {
            $headerTable = '';
            $tablename = $_POST['tabel'];
            $sql = "DESCRIBE $tablename";
            $resultedit = mysqli_query($koneksi, $sql);

            foreach ($resultedit as $keyField) {

                $headerTable = $keyField['Field'];
                if (str_contains($keyField['Key'], "PRI")) {
                    $isifile .= '
                    <?php 
                    include "config/koneksi.php";
                    $carikey = $_GET[\'carikey\'];
                     $query = "DELETE FROM ' . $tablename . ' WHERE ' . $keyField['Field'] . ' = \'$carikey \'"; 
                     $runsql = mysqli_query($koneksi,$query);

                     ?>';
                }
            }
            $namafile = str_replace(" ", "_", $_POST['namafile']) . ".php";
            $buatfile = fopen('result/pages/' . $namafile, "w+");
            fwrite($buatfile, "$isifile");
            fclose($buatfile);
            $_SESSION['berhasil'] = 'disimpan di result/pages/' . $namafile;
        }
    }
}

?>