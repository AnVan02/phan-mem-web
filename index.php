<?php
$page_title      = 'Viết Sơn - Achiva';
$html_lang       = 'en';
$meta_robots     = 'index, follow';
$pre_css_scripts = ['assets/js/banner.js'];
$extra_css       = [
    'assets/css/banner.css',
    'assets/css/danh-muc.css',
    'assets/css/doi-tac.css',
    'assets/css/cam-ket-khach-hang.css',
];
require 'head.php';
?>
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