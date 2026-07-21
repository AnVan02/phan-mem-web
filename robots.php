<?php
header('Content-Type: text/plain; charset=utf-8');
$scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
// Lấy thư mục hiện tại để phục vụ cho cả localhost chạy thư mục con và production chạy tên miền riêng
$base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
$base_url = rtrim($base_url, '/\\');
?>
User-agent: *
Allow: /

# Trang quản trị & khu vực riêng tư
Disallow: /admin/
Disallow: /tai-khoan.php
Disallow: /gio-hang.php
Disallow: /don-hang-chi-tiet.php

# File nội bộ / include / không phải trang để crawl
Disallow: /head.php
Disallow: /header.php
Disallow: /footer.php
Disallow: /banner.php
Disallow: /blog.php
Disallow: /main.php
Disallow: /san-pham-old.php
Disallow: /mailer.php

# Script xử lý AJAX / form (không phải nội dung để index)
Disallow: /*-ajax.php$
Disallow: /xuly-*.php$

# Script migrate / debug / setup database
Disallow: /migrate-*.php$
Disallow: /alter-*.php$
Disallow: /describe-*.php$
Disallow: /create-core-tables.php
Disallow: /import-db.php
Disallow: /show-tables.php

# Thư mục dữ liệu / kịch bản nội bộ, không dành cho public
Disallow: /database/
Disallow: /scratch/
Disallow: /_shot/

# Sitemap tự động cập nhật đúng tên miền hiện tại
Sitemap: <?php echo $base_url; ?>/sitemap.php
