<?php
require_once 'admin/config/config.php';

$stmt = $pdo->prepare("SELECT * FROM policy_page WHERE policy_slug = :slug AND policy_status = 1 LIMIT 1");
$stmt->execute([':slug' => 've-chung-toi']);
$trang = $stmt->fetch(PDO::FETCH_ASSOC);

$page_title = 'Về chúng tôi - Việt Sơn Achieva';
$extra_css  = ['assets/css/ve-chung-toi.css'];
require 'head.php';
?>
<?php include 'header.php'; ?>

<div class="about-page-wrapper">

    <!-- Hero -->
    <section class="about-hero">
        <div class="container about-hero-inner">
            <span class="about-hero-eyebrow">GIỚI THIỆU</span>
            <h1 class="about-hero-title">VỀ CHÚNG TÔI</h1>
            <p class="about-hero-subtitle">Hành trình gần 40 năm kiến tạo giá trị và khẳng định vị thế dẫn đầu trong
                ngành Công nghệ Thông tin tại Việt Nam.</p>
            <div class="about-hero-arrows">
                <i class="fa-solid fa-play"></i>
                <i class="fa-solid fa-play"></i>
                <i class="fa-solid fa-play"></i>
            </div>
        </div>
        <span class="about-hero-year">1990</span>
    </section>

    <div class="container">

        <?php if ($trang): ?>
            <?php echo $trang['policy_content']; ?>
        <?php else: ?>
            <p>Nội dung đang được cập nhật.</p>
        <?php endif; ?>

    </div>

</div>

<?php require 'footer.php'; ?>
