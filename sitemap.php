<?php
require_once 'admin/config/config.php';

header('Content-Type: application/xml; charset=utf-8');

$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base_url = rtrim($scheme . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/');

function xml_url($base_url, $loc, $lastmod, $changefreq, $priority)
{
    echo '  <url>' . "\n";
    echo '    <loc>' . htmlspecialchars($base_url . '/' . $loc, ENT_QUOTES) . '</loc>' . "\n";
    if ($lastmod !== null) {
        echo '    <lastmod>' . htmlspecialchars($lastmod, ENT_QUOTES) . '</lastmod>' . "\n";
    }
    echo '    <changefreq>' . $changefreq . '</changefreq>' . "\n";
    echo '    <priority>' . $priority . '</priority>' . "\n";
    echo '  </url>' . "\n";
}

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// Trang tĩnh cố định
$trang_tinh = [
    ['loc' => 'index.php', 'changefreq' => 'daily', 'priority' => '1.0'],
    ['loc' => 'san-pham.php', 'changefreq' => 'daily', 'priority' => '0.9'],
    ['loc' => 'mo-ta-linh-kien.php', 'changefreq' => 'weekly', 'priority' => '0.7'],
    ['loc' => 'tin-tuc-moi.php', 'changefreq' => 'daily', 'priority' => '0.8'],
    ['loc' => 'bao-hanh.php', 'changefreq' => 'monthly', 'priority' => '0.5'],
];
foreach ($trang_tinh as $t) {
    xml_url($base_url, $t['loc'], null, $t['changefreq'], $t['priority']);
}

// Sản phẩm đang hoạt động - tự lấy từ database, có sản phẩm mới là xuất hiện ngay
$sp_stmt = $pdo->query("SELECT ma_san_pham, ten_san_pham FROM san_pham WHERE trang_thai = 1");
while ($sp = $sp_stmt->fetch(PDO::FETCH_ASSOC)) {
    $loc = 'chi-tiet-san-pham.php?id=' . (int) $sp['ma_san_pham'] . '&ten-san-pham=' . tao_slug($sp['ten_san_pham']);
    xml_url($base_url, $loc, null, 'weekly', '0.8');
}

// Bài viết đang hiển thị - tự lấy từ database, có bài mới là xuất hiện ngay
$bv_stmt = $pdo->query("SELECT article_title, article_date FROM article WHERE article_status = 1");
while ($bv = $bv_stmt->fetch(PDO::FETCH_ASSOC)) {
    $loc     = 'chi-tiet-tin-tuc.php?ten-bai-viet=' . tao_slug($bv['article_title']);
    $lastmod = $bv['article_date'] ? date('Y-m-d', strtotime($bv['article_date'])) : null;
    xml_url($base_url, $loc, $lastmod, 'monthly', '0.6');
}

echo '</urlset>' . "\n";
