<?php
    require '../config/config.php';

    $mahanh = isset($_GET ['mh']) ? (int) $_GET ['mh']:0;
    $tenhanh = isset ($_GET ['th']) ? (int) $_GET ['th']:0;
    $soserial = isset ($_GET ['serial']) ? (int) $_GET ['th']: 0;
    $ngayxuat = isset ($_GET ['nx']) ? (int) $_GET ['nx']:0 ;
    $thoihanbh = isset ($_GET ['thbh']) ? (int) $_GET ['nx']: 0;


    if ($mahang > 0) {
        $sql = "AND mh.mahang = :ma:mh";
        $the
    }
