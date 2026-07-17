<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fallback mbstring nếu server chưa bật extension
if (!function_exists('mb_strtolower')) {
    function mb_strtolower($str, $encoding = 'UTF-8') {
        return strtolower($str);
    }
}
if (!function_exists('mb_strtoupper')) {
    function mb_strtoupper($str, $encoding = 'UTF-8') {
        return strtoupper($str);
    }
}
if (!function_exists('mb_strlen')) {
    function mb_strlen($str, $encoding = 'UTF-8') {
        return strlen($str);
    }
}
if (!function_exists('mb_convert_encoding')) {
    function mb_convert_encoding($str, $to, $from = 'UTF-8') {
        return $str;
    }
}

if (!function_exists('tao_slug')) {
    function tao_slug($str) {
        $str = mb_strtolower(trim($str), 'UTF-8');
        $map = [
            'à'=>'a','á'=>'a','ạ'=>'a','ả'=>'a','ã'=>'a','â'=>'a','ầ'=>'a','ấ'=>'a','ậ'=>'a','ẩ'=>'a','ẫ'=>'a','ă'=>'a','ằ'=>'a','ắ'=>'a','ặ'=>'a','ẳ'=>'a','ẵ'=>'a',
            'è'=>'e','é'=>'e','ẹ'=>'e','ẻ'=>'e','ẽ'=>'e','ê'=>'e','ề'=>'e','ế'=>'e','ệ'=>'e','ể'=>'e','ễ'=>'e',
            'ì'=>'i','í'=>'i','ị'=>'i','ỉ'=>'i','ĩ'=>'i',
            'ò'=>'o','ó'=>'o','ọ'=>'o','ỏ'=>'o','õ'=>'o','ô'=>'o','ồ'=>'o','ố'=>'o','ộ'=>'o','ổ'=>'o','ỗ'=>'o','ơ'=>'o','ờ'=>'o','ớ'=>'o','ợ'=>'o','ở'=>'o','ỡ'=>'o',
            'ù'=>'u','ú'=>'u','ụ'=>'u','ủ'=>'u','ũ'=>'u','ư'=>'u','ừ'=>'u','ứ'=>'u','ự'=>'u','ử'=>'u','ữ'=>'u',
            'ỳ'=>'y','ý'=>'y','ỵ'=>'y','ỷ'=>'y','ỹ'=>'y',
            'đ'=>'d',
        ];
        $str = strtr($str, $map);
        $str = preg_replace('/[^a-z0-9]+/u', '-', $str);
        $str = trim($str, '-');
        return $str !== '' ? $str : 'san-pham';
    }
}

$article_categories = [
    'Công nghệ',
    'Giáo dục',
    'AI ',
    'Linh kiện máy tính ',
    'INTEL',
    'AMD',
    'GSKILL',
];

$host = "localhost";
$dbname = "vietson-achieva";
$username = "root";
$password = "";
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Lỗi DB: " . $e->getMessage());
}

// ==== Vai trò tài khoản quản trị (cột account_type trong bảng account) ====
// 0 = Quản trị viên   : toàn quyền, kể cả quản lý tài khoản admin khác
// 1 = Quản lý nội dung: Sản phẩm, Tin tức, Banner (không thấy Đơn hàng)
// 2 = Quản lý đơn hàng: Dashboard, Đơn hàng, Bảo hành (không quản lý nội dung)
if (!defined('VAI_TRO_QUAN_TRI')) {
    define('VAI_TRO_QUAN_TRI', 0);
    define('VAI_TRO_NOI_DUNG', 1);
    define('VAI_TRO_DON_HANG', 2);
}

$DS_VAI_TRO = [
    VAI_TRO_QUAN_TRI => 'Quản trị viên',
    VAI_TRO_NOI_DUNG => 'Quản lý nội dung',
    VAI_TRO_DON_HANG => 'Quản lý đơn hàng',
];

if (!function_exists('yeu_cau_dang_nhap')) {
    /**
     * Bắt buộc đăng nhập, tuỳ chọn giới hạn theo vai trò (account_type).
     * Gọi ngay sau khi require config.php ở đầu mỗi trang admin (trừ dang-nhap.php).
     *
     * @param array  $vai_tro_duoc_phep   Mảng account_type được phép vào trang; để trống = mọi vai trò đã đăng nhập đều vào được.
     * @param string $duong_dan_dang_nhap Đường dẫn tương đối tới dang-nhap.php tính từ file đang gọi hàm này.
     */
    function yeu_cau_dang_nhap($vai_tro_duoc_phep = [], $duong_dan_dang_nhap = 'dang-nhap.php')
    {
        if (!isset($_SESSION['account_id_admin'])) {
            header('Location: ' . $duong_dan_dang_nhap);
            exit;
        }
        if (!empty($vai_tro_duoc_phep) && !in_array((int) ($_SESSION['account_type'] ?? -1), $vai_tro_duoc_phep, true)) {
            http_response_code(403);
            die('<div style="font-family:sans-serif;padding:40px;text-align:center;">
                <h2>403 - Không có quyền truy cập</h2>
                <p>Tài khoản của bạn không được phép xem trang này.</p>
                <p><a href="' . htmlspecialchars(dirname($duong_dan_dang_nhap) === '.' ? 'dashboad.php' : dirname($duong_dan_dang_nhap) . '/dashboad.php') . '">← Về Dashboard</a></p>
            </div>');
        }
    }
}