<?php
http_response_code(404);

$page_title = 'Không tìm thấy trang - Viết Sơn Achieva';
$meta_robots = 'noindex, nofollow';
$extra_css = ['assets/css/404.css'];
require 'head.php';

$requested_path = parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH) ?: '/';
?>
<main class="error-page">
    <div class="error-page__inner">
        <div class="error-page__visual" aria-hidden="true">
            <span class="error-page__number">4</span>
            <span class="error-page__disk"><i class="fa-solid fa-magnifying-glass"></i></span>
            <span class="error-page__number">4</span>
        </div>

        <p class="error-page__eyebrow">Mã lỗi 404</p>
        <h1>Trang này đã đi lạc</h1>
        <p class="error-page__message">
            Xin lỗi, chúng tôi không tìm thấy nội dung bạn đang tìm kiếm. Có thể đường dẫn đã thay đổi hoặc trang này không còn tồn tại.
        </p>

        <form class="error-page__search" action="<?php echo htmlspecialchars(asset_url('tim-kiem.php')); ?>" method="get" role="search">
            <label class="sr-only" for="errorSearch">Tìm kiếm sản phẩm</label>
            <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
            <input id="errorSearch" type="search" name="q" placeholder="Bạn đang tìm sản phẩm nào?" autocomplete="off">
            <button type="submit">Tìm kiếm</button>
        </form>

        <div class="error-page__actions">
            <a class="error-page__home" href="<?php echo htmlspecialchars(asset_url('index.php')); ?>">
                <i class="fa-solid fa-house" aria-hidden="true"></i>
                Về trang chủ
            </a>
            <button class="error-page__back" type="button" onclick="history.back()">
                <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                Quay lại trang trước
            </button>
        </div>

        <nav class="error-page__suggestions" aria-label="Trang được đề xuất">
            <span>Có thể bạn đang tìm:</span>
            <a href="<?php echo htmlspecialchars(asset_url('san-pham.php')); ?>">Sản phẩm</a>
            <a href="<?php echo htmlspecialchars(asset_url('tin-tuc-moi.php')); ?>">Tin tức</a>
            <a href="<?php echo htmlspecialchars(asset_url('bao-hanh.php')); ?>">Tra cứu bảo hành</a>
        </nav>

        <p class="error-page__path">Đường dẫn không tồn tại: <code><?php echo htmlspecialchars($requested_path); ?></code></p>
    </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>