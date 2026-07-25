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

    // Sản phẩm liên quan tới bài viết: chỉ hiển thị nếu chủ đề/thẻ bài viết trùng
    // với thương hiệu, danh mục hoặc tên sản phẩm đang có trong cửa hàng
    $chuan_hoa = function ($s) {
        return mb_strtolower(trim((string) $s), 'UTF-8');
    };

    $tu_khoa_bai_viet = array_merge(
        [$bai_viet['article_linh']],
        explode(',', $bai_viet['tab_baiviet'])
    );
    $tu_khoa_bai_viet = array_values(array_unique(array_filter(array_map($chuan_hoa, $tu_khoa_bai_viet))));

    $related_products = [];
    if (!empty($tu_khoa_bai_viet)) {
        $sp_stmt = $pdo->query("SELECT sp.*, th.ten_thuong_hieu, dm.ten_danh_muc
                FROM san_pham sp
                LEFT JOIN thuong_hieu th ON sp.ma_thuong_hieu = th.ma_thuong_hieu
                LEFT JOIN danh_muc dm ON sp.ma_danh_muc = dm.ma_danh_muc
                WHERE sp.trang_thai = 1");
        while ($sp_row = $sp_stmt->fetch(PDO::FETCH_ASSOC)) {
            $ten_th = $chuan_hoa($sp_row['ten_thuong_hieu'] ?? '');
             $ten_th = $chuan_hoa($sp_row['mo_ta'] ?? '');
            $ten_dm = $chuan_hoa($sp_row['ten_danh_muc'] ?? '');
            $ten_sp = $chuan_hoa($sp_row['ten_san_pham']);

            $khop = false;
            foreach ($tu_khoa_bai_viet as $kw) {
                if ($kw === '') {
                    continue;
                }
                if (($ten_th !== '' && $kw === $ten_th) || ($ten_dm !== '' && $kw === $ten_dm)) {
                    $khop = true;
                    break;
                }
                if (mb_strlen($kw, 'UTF-8') >= 3 && (mb_strpos($ten_sp, $kw) !== false || mb_strpos($kw, $ten_sp) !== false)) {
                    $khop = true;
                    break;
                }
            }
            if ($khop) {
                $related_products[] = $sp_row;
            }
        }
        usort($related_products, function ($a, $b) {
            return (int) $b['da_ban'] <=> (int) $a['da_ban'];
        });
        $related_products = array_slice($related_products, 0, 5);
    }

    $ten_tac_gia = trim($bai_viet['article_author']);
    $chu_cai_dau = $ten_tac_gia !== '' ? mb_strtoupper(mb_substr($ten_tac_gia, 0, 1, 'UTF-8'), 'UTF-8') : '?';

    $anh_tac_gia = null;
    if (!empty($bai_viet['article_account_id'])) {
        $tk_stmt = $pdo->prepare("SELECT account_avatar FROM account WHERE account_id = :id LIMIT 1");
        $tk_stmt->execute([':id' => $bai_viet['article_account_id']]);
        $anh_tac_gia_raw = $tk_stmt->fetchColumn();
        $anh_tac_gia = $anh_tac_gia_raw !== false && trim((string) $anh_tac_gia_raw) !== '' ? trim($anh_tac_gia_raw) : null;
    }

    // Xây mục lục từ các thẻ h2-h4 trong nội dung bài viết + gắn id để nhảy neo
    $muc_luc = [];
    $noi_dung_bai_viet = $bai_viet['article_content'] ?? '';
    if (trim($noi_dung_bai_viet) !== '') {
        $slug_da_dung = [];
        $noi_dung_bai_viet = preg_replace_callback(
            '/<(h[234])([^>]*)>(.*?)<\/\1>/is',
            function ($m) use (&$muc_luc, &$slug_da_dung) {
                $tag   = $m[1];
                $attrs = $m[2];
                $inner = $m[3];
                $text  = trim(html_entity_decode(strip_tags($inner), ENT_QUOTES, 'UTF-8'));
                if ($text === '') {
                    return $m[0];
                }
                $slug = tao_slug($text);
                $goc  = $slug;
                $i    = 2;
                while (in_array($slug, $slug_da_dung, true)) {
                    $slug = $goc . '-' . $i;
                    $i++;
                }
                $slug_da_dung[] = $slug;
                $attrs = preg_replace('/\sid=("|\')[^"\']*\1/i', '', $attrs);
                $muc_luc[] = ['text' => $text, 'slug' => $slug, 'level' => (int) substr($tag, 1)];
                return '<' . $tag . $attrs . ' id="' . $slug . '">' . $inner . '</' . $tag . '>';
            },
            $noi_dung_bai_viet
        );
        $bai_viet['article_content'] = $noi_dung_bai_viet;
    }
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
                <a href="<?php echo htmlspecialchars(asset_url('tin-tuc-moi.php')); ?>" class="btn-back">← Quay lại Tin tức & Blog</a>
            </div>
        </div>
    </section>
<?php else:
    $anh = asset_url(trim($bai_viet['article_image']) !== '' ? $bai_viet['article_image'] : 'assets/image/pc.webp');
    $ngay = date('d/m/Y', strtotime($bai_viet['article_date']));
?>
    <section class="news-detail">
        <div class="container">
            <nav class="news-breadcrumb">
                <a href="<?php echo htmlspecialchars(asset_url('index.php')); ?>" class="breadcrumb-home"><i class="fa-solid fa-house"></i></a>
                <span class="breadcrumb-sep">»</span>
                <a href="<?php echo htmlspecialchars(asset_url('tin-tuc-moi.php')); ?>">Tin tức & Blog</a>
                <?php if (!empty($bai_viet['article_linh'])): ?>
                    <span class="breadcrumb-sep">»</span>
                    <a href="<?php echo htmlspecialchars(asset_url('tin-tuc-moi.php')); ?>?linh=<?php echo urlencode($bai_viet['article_linh']); ?>"><?php echo htmlspecialchars($bai_viet['article_linh']); ?></a>
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

                <?php
                    $tom_tat = trim($bai_viet['article_summary'] ?? '');
                    // Tóm tắt cũ chỉ là văn bản thường (chưa qua trình soạn thảo): giữ lại xuống dòng thành đoạn văn
                    if ($tom_tat !== '' && strpos($tom_tat, '<') === false) {
                        $tom_tat_doan = array_values(array_filter(array_map('trim', preg_split('/\r\n|\r|\n/', $tom_tat))));
                        $tom_tat = '<p>' . implode('</p><p>', array_map('htmlspecialchars', $tom_tat_doan)) . '</p>';
                    }
                ?>
                <?php if ($tom_tat !== ''): ?>
                    <div class="news-detail-summary"><?php echo $tom_tat; ?></div>
                <?php endif; ?>

                <?php if (!empty($muc_luc)): ?>
                    <nav class="news-toc" aria-label="Mục lục bài viết">
                        <button type="button" class="news-toc-toggle" aria-expanded="true">
                            <span><i class="fa-solid fa-list-ul"></i> Mục lục</span>
                            <span class="news-toc-toggle-right">
                                <span class="news-toc-toggle-label">Ẩn</span>
                                <i class="fa-solid fa-chevron-down news-toc-caret"></i>
                            </span>
                        </button>
                        <ol class="news-toc-list">
                            <?php foreach ($muc_luc as $item): ?>
                                <li class="news-toc-level-<?php echo (int) $item['level']; ?>">
                                    <a href="#<?php echo htmlspecialchars($item['slug']); ?>"><?php echo htmlspecialchars($item['text']); ?></a>
                                </li>
                            <?php endforeach; ?>
                        </ol>
                    </nav>
                <?php endif; ?>

                <div class="news-detail-content">
                    <?php echo $bai_viet['article_content']; ?>
                </div>
                <?php if (!empty(trim($bai_viet['article_video']))): ?>
                    <div class="news-detail-video">
                        <iframe src="<?php echo htmlspecialchars($bai_viet['article_video']); ?>" title="<?php echo htmlspecialchars($bai_viet['article_title']); ?>" allowfullscreen></iframe>
                    </div>
                <?php endif; ?>
            </article>

            <?php if (count($related_products) > 0): ?>
                <div class="news-related-products">
                    <div class="related-inline-header">
                        <h2><i class="fa-solid fa-layer-group"></i> Sản phẩm đang được quan tâm nhiều tại Viết Sơn Achieva</h2>
                        <a href="<?php echo htmlspecialchars(asset_url('san-pham.php')); ?>" class="related-inline-more">Xem tất cả <i class="fa-solid fa-angle-right"></i></a>
                    </div>
                    <div class="product-grid-small">
                        <?php foreach ($related_products as $rp):
                            $r_gia_ban      = (int) $rp['gia_ban'];
                            $r_giam_gia     = (int) $rp['giam_gia'];
                            $r_gia_sau_giam = $r_giam_gia > 0 ? (int) round($r_gia_ban * (100 - $r_giam_gia) / 100) : $r_gia_ban;
                            $r_anh_list     = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', $rp['hinh_anh']))));
                            $r_hinh_anh     = asset_url(!empty($r_anh_list) ? $r_anh_list[0] : 'assets/image/pc.webp');
                        ?>
                            <a class="product-card-small" href="<?php echo tao_url_san_pham($rp['ma_san_pham'], $rp['ten_san_pham']); ?>">
                                <?php if ($r_giam_gia > 0): ?><span class="product-badge">-<?php echo $r_giam_gia; ?>%</span><?php endif; ?>
                                <div class="product-media">
                                    <img src="<?php echo htmlspecialchars($r_hinh_anh); ?>" alt="<?php echo htmlspecialchars($rp['ten_san_pham']); ?>" loading="lazy"
                                        onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                                </div>
                                <div class="product-body">
                                    <?php if (!empty($rp['ten_thuong_hieu'])): ?>
                                        <span class="product-brand"><?php echo htmlspecialchars(trim($rp['ten_thuong_hieu'])); ?></span>
                                    <?php endif; ?>
                                    
                                    <h3 class="product-name"><?php echo htmlspecialchars($rp['ten_san_pham']); ?></h3>
                                    <div class="product-price-row">
                                        <?php if ($r_gia_ban <= 0): ?>
                                            <span class="product-price">Liên hệ</span>
                                        <?php else: ?>
                                            <span class="product-price"><?php echo number_format($r_gia_sau_giam, 0, ',', '.'); ?>₫</span>
                                            <?php if ($r_giam_gia > 0): ?>
                                                <span class="product-price-old"><?php echo number_format($r_gia_ban, 0, ',', '.'); ?>₫</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <span class="product-card-small-cta">Xem chi tiết <i class="fa-solid fa-arrow-right"></i></span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if (count($related_list) > 0): ?>
                    <div class="news-see-more">
                        <p class="news-see-more-title">Xem thêm:</p>
                        <ul>
                            <?php foreach (array_slice($related_list, 0, 5) as $rl): ?>
                                <li>
                                    <a href="<?php echo htmlspecialchars(asset_url('chi-tiet-tin-tuc.php')); ?>?ten-bai-viet=<?php echo tao_slug($rl['article_title']); ?>">
                                        <?php echo htmlspecialchars($rl['article_title']); ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- tab bài viêt -->
            <?php
            $tags = array_values(array_filter(array_map('trim', explode(',', $bai_viet['tab_baiviet']))));
            ?>
            <?php if (!empty($tags)): ?>
                <footer class="tab_baiviet">
                    <p class="tab-title">Thẻ bài viết</p>
                    <div class="tag-list">
                        <?php foreach ($tags as $tag): ?>
                            <a href="../tintuc/tag/<?php echo urlencode($tag); ?>" class="article_link" rel="tag"> <?php echo htmlspecialchars($tag); ?></a>
                        <?php endforeach; ?>
                    </div>
                </footer>
            <?php endif; ?>

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
                            <a class="news-card" href="<?php echo htmlspecialchars(asset_url('chi-tiet-tin-tuc.php')); ?>?ten-bai-viet=<?php echo $art_slug; ?>">
                                <div class="news-media">
                                    <img src="<?php echo htmlspecialchars($art_anh); ?>" alt="<?php echo htmlspecialchars($a['article_title']); ?>" loading="lazy"
                                        onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                                </div>
                                <div class="news-meta">
                                    <?php if (!empty($a['article_linh'])): ?>
                                        <span class="news-tag"><?php echo htmlspecialchars(trim($a['article_linh'])); ?></span> 
                                    <?php endif; ?>
                                    <?php echo $art_ngay; ?>
                                </div>
                                <h3 class="news-name"><?php echo htmlspecialchars($a['article_title']); ?></h3>
                                <?php if ($mo_ta_ngan !== ''): ?>
                                    <p class="news-desc"><?php echo htmlspecialchars($mo_ta_ngan); ?></p>
                                <?php endif; ?>
                                <!-- <span class="news-readmore">Xem thêm</span> -->
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
<?php endif; ?>

<script>
    document.querySelectorAll('.news-toc-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var toc = btn.closest('.news-toc');
            toc.classList.toggle('collapsed');
            var dangAn = toc.classList.contains('collapsed');
            btn.setAttribute('aria-expanded', dangAn ? 'false' : 'true');
            var label = btn.querySelector('.news-toc-toggle-label');
            if (label) {
                label.textContent = dangAn ? 'Hiện' : 'Ẩn';
            }
        });
    });
</script>

<?php include 'footer.php'; ?>
</body>

</html>