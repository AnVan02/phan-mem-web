<?php
if (isset($_GET['page'])) {
    $action = $_GET['page'];
} else {
    $action = '';
}

if ($action == '') {
    include("./pages/main/Gioi-Thieu-Cong-Ty-Viet-Son.php");

}elseif ($action == 'blog') {
    include("./pages/main/blog.php");
}