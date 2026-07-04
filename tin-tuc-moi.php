<?php
    require_once 'admin/config/config.php';

    $linh = isset($_GET['linh']) ? trim($_GET['linh']) : '';

    $stmt = $pdo->query("SELECT * FROM article WHERE article_status = 1 ORDER BY article_date DESC");
    $article_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $linh_list = $article_categories;
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tin tức & Blog - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/tin-tuc-moi.css">
    <script src="assets/js/tin-tuc-moi.js" defer></script>
</head>

<body>
<?php include 'header.php'; ?>

    <section class="news-page">
        <div class="container">
            <div class="news-page-header">
                <h1 class="news-title">Tin Tức & Blog</h1>
            </div>

            <div class="news-filter">
                <button type="button" class="news-tab <?php echo $linh === '' ? 'active' : ''; ?>" data-linh="">Tất cả bài đăng</button>

                <?php foreach ($linh_list as $l): ?>
                    <button type="button" class="news-tab <?php echo $linh === $l ? 'active' : ''; ?>" data-linh="<?php echo htmlspecialchars($l); ?>">
                        <?php echo htmlspecialchars($l); ?>
                    </button>
                <?php endforeach; ?>
            </div>

            <?php if (count($article_list) === 0): ?>
                <div class="news-empty">
                    <i class="fa-solid fa-newspaper"></i>
                    <p>Hiện chưa có bài viết nào.</p>
                </div>  
            <?php else: ?>
                <div class="news-grid">
                    <?php foreach ($article_list as $a):
                        $mo_ta_ngan = trim(strip_tags($a['article_summary']));
                        if (mb_strlen($mo_ta_ngan) > 150) {
                            $mo_ta_ngan = mb_substr($mo_ta_ngan, 0, 150) . '...';
                        }
                        $anh = trim($a['article_image']) !== '' ? $a['article_image'] : 'assets/image/pc.webp';
                        $ngay = date('d/m/Y', strtotime($a['article_date']));
                        $slug = tao_slug($a['article_title']);
                    ?>

                        <a class="news-card" data-linh="<?php echo htmlspecialchars($a['article_linh']); ?>" href="chi-tiet-tin-tuc.php?ten-bai-viet=<?php echo $slug; ?>">
                            <div class="news-media">
                                <img src="<?php echo htmlspecialchars($anh); ?>" alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy">
                            </div>
                            <div class="news-meta">
                                <?php if (!empty($a['article_linh'])): ?>
                                    <span class="news-tag"><?php echo htmlspecialchars($a['article_linh']); ?></span> •
                                <?php endif; ?>
                                <?php echo $ngay; ?>
                            </div>
                            <h3 class="news-name"><?php echo htmlspecialchars($a['article_title']); ?></h3>
                            <?php if ($mo_ta_ngan !== ''): ?>
                                <p class="news-desc"><?php echo htmlspecialchars($mo_ta_ngan); ?></p>
                            <?php endif; ?>
                            <span class="news-readmore">Xem thêm</span>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="news-empty news-empty-filtered" style="display:none;">
                    <i class="fa-solid fa-newspaper"></i>
                    <p>Không có bài viết nào trong chuyên mục này.</p>
                </div>
                <nav class="news-pagination"></nav>
            <?php endif; ?>
        </div>
    </section>
    

    <?php include 'footer.php'; ?>
</body>

</html>
