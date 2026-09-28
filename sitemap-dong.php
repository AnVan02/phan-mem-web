<?php
require_once 'admin/config/config.php';

header('Content-Type: application/xml; charset=utf-8');

// ── Base URL cố định theo domain thật ──
$scheme   = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$base_url = $scheme . '://' . $_SERVER['HTTP_HOST'];

// ────────────────────────────────────────────────────────────
// Helper: xuất một thẻ <url> đầy đủ
// ────────────────────────────────────────────────────────────
function xml_url($base_url, $loc, $lastmod = null, $changefreq = 'monthly', $priority = '0.5')
{
    $full_url = rtrim($base_url, '/') . ($loc !== '' ? '/' . ltrim($loc, '/') : '');
    echo "  <url>\n";
    echo '    <loc>' . htmlspecialchars($full_url, ENT_XML1 | ENT_QUOTES, 'UTF-8') . "</loc>\n";
    if ($lastmod) {
        echo '    <lastmod>' . htmlspecialchars($lastmod, ENT_XML1) . "</lastmod>\n";
    }
    echo "    <changefreq>$changefreq</changefreq>\n";
    echo "    <priority>$priority</priority>\n";
    echo "  </url>\n";
}

$today = date('Y-m-d');

echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

// ════════════════════════════════════════════════════════════
// 1. TRANG TĨNH CHÍNH
// ════════════════════════════════════════════════════════════
$trang_tinh = [
    ['loc' => '',                    'changefreq' => 'daily',   'priority' => '1.0'],
    ['loc' => 'san-pham',            'changefreq' => 'daily',   'priority' => '0.9'],
    ['loc' => 'danh-muc',            'changefreq' => 'daily',   'priority' => '0.8'],
    ['loc' => 'mo-ta-linh-kien',     'changefreq' => 'weekly',  'priority' => '0.7'],
    ['loc' => 'tin-tuc-moi',         'changefreq' => 'daily',   'priority' => '0.8'],
    ['loc' => 'thuong-hieu',         'changefreq' => 'weekly',  'priority' => '0.6'],
    ['loc' => 'tim-kiem',            'changefreq' => 'weekly',  'priority' => '0.5'],
    ['loc' => 'bao-hanh',            'changefreq' => 'monthly', 'priority' => '0.6'],
    ['loc' => 've-chung-toi',        'changefreq' => 'monthly', 'priority' => '0.5'],
    ['loc' => 'cam-ket-khach-hang',  'changefreq' => 'monthly', 'priority' => '0.5'],
    ['loc' => 'chinh-sach',          'changefreq' => 'monthly', 'priority' => '0.4'],
    ['loc' => 'chinh-sach-bao-hanh', 'changefreq' => 'monthly', 'priority' => '0.4'],
    ['loc' => 'chinh-sach-bao-mat',  'changefreq' => 'monthly', 'priority' => '0.4'],
    ['loc' => 'chinh-sach-cookie',   'changefreq' => 'monthly', 'priority' => '0.3'],
    ['loc' => 'dieu-khoan',          'changefreq' => 'monthly', 'priority' => '0.3'],
];

foreach ($trang_tinh as $t) {
    xml_url($base_url, $t['loc'], $today, $t['changefreq'], $t['priority']);
}

// ════════════════════════════════════════════════════════════
// 2. SẢN PHẨM ĐỘNG (lấy từ DB)
// ════════════════════════════════════════════════════════════
try {
    $sp_stmt = $pdo->query(
        "SELECT ten_san_pham, ngay_cap_nhat
         FROM san_pham
         WHERE trang_thai = 1
         ORDER BY ngay_cap_nhat DESC"
    );
    while ($sp = $sp_stmt->fetch(PDO::FETCH_ASSOC)) {
        $loc     = 'chi-tiet-san-pham/' . tao_slug($sp['ten_san_pham']);
        $lastmod = !empty($sp['ngay_cap_nhat'])
            ? date('Y-m-d', strtotime($sp['ngay_cap_nhat']))
            : $today;
        xml_url($base_url, $loc, $lastmod, 'weekly', '0.8');
    }
} catch (PDOException $e) {
    // Không để crash sitemap khi lỗi DB
}

// ════════════════════════════════════════════════════════════
// 3. BÀI VIẾT / TIN TỨC ĐỘNG (lấy từ DB)
// ════════════════════════════════════════════════════════════
try {
    $bv_stmt = $pdo->query(
        "SELECT article_title, article_date
         FROM article
         WHERE article_status = 1
         ORDER BY article_date DESC"
    );
    while ($bv = $bv_stmt->fetch(PDO::FETCH_ASSOC)) {
        $loc     = 'chi-tiet-tin-tuc/' . tao_slug($bv['article_title']);
        $lastmod = !empty($bv['article_date'])
            ? date('Y-m-d', strtotime($bv['article_date']))
            : $today;
        xml_url($base_url, $loc, $lastmod, 'monthly', '0.6');
    }
} catch (PDOException $e) {
    // Không để crash sitemap khi lỗi DB
}

echo '</urlset>' . "\n";
