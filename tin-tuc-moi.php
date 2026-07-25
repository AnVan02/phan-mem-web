<?php
require_once 'admin/config/config.php';

$linh = isset($_GET['linh']) ? trim($_GET['linh']) : '';

$stmt = $pdo->query("SELECT a.*, acc.account_avatar
    FROM article a
    LEFT JOIN account acc ON acc.account_id = a.article_account_id
    WHERE a.article_status = 1
    ORDER BY a.article_date DESC, a.article_id DESC");
$article_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

$linh_list = $article_categories;

$page_title       = 'Tin tức & Blog - Viết Sơn Achieva';
$extra_css        = ['assets/css/tin-tuc-moi.css'];
$post_css_scripts = ['assets/js/tin-tuc-moi.js'];
require 'head.php';
?>
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
                    $mo_ta_ngan = trim(strip_tags(html_entity_decode($a['article_summary'], ENT_QUOTES, 'UTF-8')));
                    if (mb_strlen($mo_ta_ngan) > 150) {
                        $mo_ta_ngan = mb_substr($mo_ta_ngan, 0, 150) . '...';
                    }
                    $anh = asset_url(trim($a['article_image']) !== '' ? $a['article_image'] : 'assets/image/pc.webp');
                    $ngay = date('d/m/Y', strtotime($a['article_date']));
                    $slug = tao_slug($a['article_title']);
                    $ten_tac_gia = trim($a['article_author']);
                    $chu_cai_dau = $ten_tac_gia !== '' ? mb_strtoupper(mb_substr($ten_tac_gia, 0, 1, 'UTF-8'), 'UTF-8') : '?';
                    $anh_tac_gia = !empty($a['account_avatar']) ? trim($a['account_avatar']) : null;
                ?>

                    <a class="news-card" data-linh="<?php echo htmlspecialchars($a['article_linh']); ?>" href="<?php echo htmlspecialchars(asset_url('chi-tiet-tin-tuc.php')); ?>?ten-bai-viet=<?php echo $slug; ?>">
                        <div class="news-media">
                            <img src="<?php echo htmlspecialchars($anh); ?>" alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy"
                                onerror="this.onerror=null;this.src='assets/image/pc.webp';">
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
                        <div class="news-detail-meta">
                            <span class="news-author">
                                <span class="news-author-avatar">
                                    <?php if ($anh_tac_gia): ?>
                                        <img src="<?php echo htmlspecialchars($anh_tac_gia); ?>" alt="">
                                    <?php else: ?>
                                        <?php echo htmlspecialchars($chu_cai_dau); ?>
                                    <?php endif; ?>
                                </span>
                                <?php echo htmlspecialchars($ten_tac_gia); ?>
                            </span>
                            <span class="news-date"><i class="fa-solid fa-calendar-days"></i> Ngày cập nhật: <?php echo $ngay; ?></span>
                        </div>
                        <!-- <span class="news-readmore">Xem thêm</span> -->
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