<?php
    require_once 'admin/config/config.php';

    $ten_bai_viet = isset($_GET['ten-bai-viet']) ? trim($_GET['ten-bai-viet']) : '';

    $bai_viet = null;
    if ($ten_bai_viet !== '') {
        $stmt = $pdo->query("SELECT * FROM article WHERE article_status = 1");
        while ($a = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (tao_slug($a['article_title']) === $ten_bai_viet) {
                $bai_viet = $a;
                break;
            }
        }
    }
    

    $page_title = $bai_viet ? htmlspecialchars($bai_viet['article_title']) . ' - Viết Sơn Achieva' : 'Chi tiết tin tức - Viết Sơn Achieva';

    if ($bai_viet) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $base_url = $scheme . '://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']);
        $canonical_url = rtrim($base_url, '/') . '/chi-tiet-tin-tuc.php?ten-bai-viet=' . tao_slug($bai_viet['article_title']);

        $related_stmt = $pdo->prepare("SELECT * FROM article
            WHERE article_status = 1 AND article_linh = :linh AND article_id != :id
            ORDER BY article_date DESC
            LIMIT 4");
        $related_stmt->execute([':linh' => $bai_viet['article_linh'], ':id' => $bai_viet['article_id']]);
        $related_list = $related_stmt->fetchAll(PDO::FETCH_ASSOC);

        $ten_tac_gia = trim($bai_viet['article_author']);
        $chu_cai_dau = $ten_tac_gia !== '' ? mb_strtoupper(mb_substr($ten_tac_gia, 0, 1, 'UTF-8'), 'UTF-8') : '?';
    }

    $extra_css = ['assets/css/tin-tuc-moi.css', 'assets/css/chi-tiet-tin-tuc.css'];
    require 'head.php';
?>
<?php include 'header.php'; ?>

<?php if (!$bai_viet): ?>
    <section class="news-detail">
        <div class="container">
            <div class="news-empty">
                <i class="fa-solid fa-newspaper"></i>
                <p>Không tìm thấy bài viết bạn yêu cầu.</p>
                <a href="tin-tuc-moi.php" class="btn-back">← Quay lại Tin tức & Blog</a>
            </div>
        </div>
    </section>
<?php else:
    $anh = trim($bai_viet['article_image']) !== '' ? $bai_viet['article_image'] : 'assets/image/pc.webp';
    $ngay = date('d/m/Y', strtotime($bai_viet['article_date']));
?>
    <section class="news-detail">
        <div class="container">
            <nav class="news-breadcrumb">
                <a href="index.php" class="breadcrumb-home"><i class="fa-solid fa-house"></i></a>
                <span class="breadcrumb-sep">»</span>
                <a href="tin-tuc-moi.php">Tin tức & Blog</a>
                <?php if (!empty($bai_viet['article_linh'])): ?>
                    <span class="breadcrumb-sep">»</span>
                    <a href="tin-tuc-moi.php?linh=<?php echo urlencode($bai_viet['article_linh']); ?>"><?php echo htmlspecialchars($bai_viet['article_linh']); ?></a>
                <?php endif; ?>
                <span class="breadcrumb-sep">»</span>
                <span class="breadcrumb-current"><?php echo htmlspecialchars($bai_viet['article_title']); ?></span>
            </nav>
            <

            <div class="news-detail-hero">
                <img class="news-detail-hero-img" src="<?php echo htmlspecialchars($anh); ?>" alt="<?php echo htmlspecialchars($bai_viet['article_title']); ?>">
            </div>

            <article class="news-detail-main">
                <?php if (!empty($bai_viet['article_linh'])): ?>
                    <span class="news-tag"><?php echo htmlspecialchars($bai_viet['article_linh']); ?></span>
                <?php endif; ?>

                <h1 class="news-detail-title"><?php echo htmlspecialchars($bai_viet['article_title']); ?></h1>

                <div class="news-detail-meta">
                    <span class="news-author">
                        <span class="news-author-avatar"><?php echo htmlspecialchars($chu_cai_dau); ?></span>
                        <?php echo htmlspecialchars($ten_tac_gia); ?>
                    </span>
                    <span class="news-date"><i class="fa-solid fa-calendar-days"></i> Ngày cập nhật: <?php echo $ngay; ?></span>
                </div>

                <div class="news-detail-content">
                    <?php echo $bai_viet['article_content']; ?>
                </div>
                <?php if (!empty(trim($bai_viet['article_video']))): ?>
                    <div class="news-detail-video">
                        <iframe src="<?php echo htmlspecialchars($bai_viet['article_video']); ?>" title="<?php echo htmlspecialchars($bai_viet['article_title']); ?>" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
            </article>


            <!-- tab bài viêt -->
            <footer class="tab_baiviet">
                    <p class="tab-title">Thẻ bài viết</p>
                    <div class="tag-list">
                        <?php
                            $tags = explode(',', $bai_viet['tab_baiviet']);
                            foreach ($tags as $tag){
                                $tag = trim($tag);
                                if(!empty($tag)) {
                                    echo '<a href="../tintuc/tag/'.urlencode($tag) . '"
                                            class="article_link" rel="tag"> '.htmlspecialchars($tag).'</a>';

                                }
                            }
                        ?>

                    </div>
            </footer>            

                <?php if (count($related_list) > 0): ?>
                    <div class="news-related">
                        <h2>Bài viết liên quan</h2>
                        <div class="news-grid">
                            <?php foreach ($related_list as $a):
                                $art_anh  = trim($a['article_image']) !== '' ? $a['article_image'] : 'assets/image/pc.webp';
                                $art_ngay = date('d/m/Y', strtotime($a['article_date']));
                                $art_slug = tao_slug($a['article_title']);

                                $mo_ta_ngan = trim(strip_tags(html_entity_decode($a['article_summary'] ?? '', ENT_QUOTES, 'UTF-8')));
                                if (mb_strlen($mo_ta_ngan) > 150) {
                                    $mo_ta_ngan = mb_substr($mo_ta_ngan, 0, 150) . '...';
                                }
                            ?>
                                <a class="news-card" href="chi-tiet-tin-tuc.php?ten-bai-viet=<?php echo $art_slug; ?>">
                                    <div class="news-media">
                                        <img src="<?php echo htmlspecialchars($art_anh); ?>" alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy"
                                            onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                                    </div>
                                    <div class="news-meta">
                                        <?php if (!empty($a['article_linh'])): ?>
                                            <span class="news-tag"><?php echo htmlspecialchars(trim($a['article_linh'])); ?></span> •
                                        <?php endif; ?>
                                        <?php echo $art_ngay; ?>
                                    </div>
                                    <h3 class="news-name"><?php echo htmlspecialchars($a['article_title']); ?></h3>
                                    <?php if ($mo_ta_ngan !== ''): ?>
                                        <p class="news-desc"><?php echo htmlspecialchars($mo_ta_ngan); ?></p>
                                    <?php endif; ?>
                                    <span class="news-readmore">Xem thêm</span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

    <?php include 'footer.php'; ?>
</body> 

</html>
