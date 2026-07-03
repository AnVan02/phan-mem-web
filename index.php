<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Viết Sơn - Achiva</title>
    <!-- logo icon  -->
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg"/>
    <!-- Thẻ SEO Meta chuẩn Google -->
    <meta name="description" content="<?php echo $seo_description; ?>">
    <meta name="robots" content="index, follow">
    <!--google search console -->



    <!-- start google font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">


    <!-- phần mục js  -->
    <script src="assets/js/banner.js"></script>
    <script src="assets/js/header.js"></script>

    <!-- phần mục css  -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/banner.css">
    <link rel="stylesheet" href="assets/css/danh-muc.css">
    <link rel="stylesheet" href="assets/css/doi-tac.css">
    <link rel="stylesheet" href="assets/css/cam-ket-khach-hang.css">
    <link rel="stylesheet" href="assets/css/footer.css">

</head>

<body>
    <?php

        include 'header.php'; 
        include 'banner.php';
        // 
        include 'danh-muc.php';
        // include 'doi-tac.php';
        include 'cam-ket-khach-hang.php';
        // include 'thuong-hieu.php';






        include 'footer.php';

    ?>


</body>

</html>