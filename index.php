<?php
if (!is_dir('result')) {
    mkdir('result');
    mkdir('result/pages');
    mkdir('result/config');
} else {
    if (!is_dir('result/pages')) {
        mkdir('result/pages');
    }
    if (!is_dir('result/config')) {
        mkdir('result/config');
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link href="assets/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="assets/css/select2-bootstrap-5-theme.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css" />
    <title>Hello, world!</title>
</head>

<body class="">
    <div class="container">
        <div class="card shadow mt-3" style="width: 35%; margin-left:auto;margin-right:auto;">
            <div class="card-header bg-primary text-white">
                <i class="bi bi-table"></i> <b>FORM</b> CRUD Generator
            </div>
            <div class="card-body">

                <?php if (!empty($_GET['p'])) {
                    $page = $_GET['p'];
                    include 'gen/' . $page . '.php';
                } else {
                    include 'gen/database.php';
                }
                ?>
            </div>
            <p class="card-footer text-center">
                <a class="btn btn-danger mx-1" target="blank" href="https://www.instagram.com/teguh.saint"><i class="bi bi-instagram"></i></a>
                <a class="btn btn-primary mx-1" target="blank" href="https://www.facebook.com/teguhsaint.qilah/"><i class="bi bi-facebook"></i></a>
                <a class="btn btn-dark mx-1" target="blank" href="https://github.com/teguhsaint"><i class="bi bi-github"></i></a>
                <a class="btn btn-danger mx-1" target="blank" href="https://www.youtube.com/channel/UCZ_wfdEEDDsFTzFi5sg1qOw"><i class="bi bi-youtube"></i></a>
            </p>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

    <script src="assets/js/select2.min.js"></script>
    <script src="assets/js/sweetalert2@11.min.js"></script>

    <script>
        $('select').select2({
            theme: 'bootstrap-5'
        });
    </script>

    <?php

    if (isset($_SESSION['berhasil']) && !empty($_SESSION['berhasil'])) {
    ?>
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Sukses',
                text: '<?= $_SESSION['berhasil'] ?>',
            })
        </script>
    <?php
        session_destroy();
        unset($_SESSION['berhasil']);
    }
    ?>
</body>

</html>