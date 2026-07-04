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
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <?php if ($bai_viet): ?><link rel="canonical" href="<?php echo htmlspecialchars($canonical_url); ?>"><?php endif; ?>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg"/>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/tin-tuc-moi.css">
    <link rel="stylesheet" href="assets/css/chi-tiet-tin-tuc.css">
</head>

<body>
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

                <?php if (!empty(trim($bai_viet['article_video']))): ?>
                    <div class="news-detail-video">
                        <iframe src="<?php echo htmlspecialchars($bai_viet['article_video']); ?>" title="<?php echo htmlspecialchars($bai_viet['article_title']); ?>" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>

                <div class="news-detail-content">
                    <?php echo $bai_viet['article_content']; ?>
                </div>
            </article>

            <?php if (!empty($related_list)): ?>
                <div class="news-related">
                    <h2>Bài viết liên quan</h2>
                    <div class="news-grid">
                        <?php foreach ($related_list as $a):
                            $r_mo_ta = trim(strip_tags($a['article_summary']));
                            if (mb_strlen($r_mo_ta) > 150) {
                                $r_mo_ta = mb_substr($r_mo_ta, 0, 150) . '...';
                            }
                            $r_anh = trim($a['article_image']) !== '' ? $a['article_image'] : 'assets/image/pc.webp';
                            $r_ngay = date('d/m/Y', strtotime($a['article_date']));
                            $r_slug = tao_slug($a['article_title']);
                        ?>
                            <a class="news-card" href="chi-tiet-tin-tuc.php?ten-bai-viet=<?php echo $r_slug; ?>">
                                <div class="news-media">
                                    <img src="<?php echo htmlspecialchars($r_anh); ?>" alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy">
                                </div>
                                <div class="news-meta">
                                    <?php if (!empty($a['article_linh'])): ?>
                                        <span class="news-tag"><?php echo htmlspecialchars($a['article_linh']); ?></span> •
                                    <?php endif; ?>
                                    <?php echo $r_ngay; ?>
                                </div>
                                <h3 class="news-name"><?php echo htmlspecialchars($a['article_title']); ?></h3>
                                <?php if ($r_mo_ta !== ''): ?>
                                    <p class="news-desc"><?php echo htmlspecialchars($r_mo_ta); ?></p>
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
