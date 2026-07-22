<?php
require_once 'admin/config/config.php';

$slug = isset($_GET['slug']) ? trim($_GET['slug']) : '';

$trang = null;
if ($slug !== '') {
    $stmt = $pdo->prepare("SELECT * FROM policy_page WHERE policy_slug = :slug AND policy_status = 1 LIMIT 1");
    $stmt->execute([':slug' => $slug]);
    $trang = $stmt->fetch(PDO::FETCH_ASSOC);
}

$page_title = ($trang ? htmlspecialchars($trang['policy_title']) : 'Không tìm thấy trang') . ' - Việt Sơn Achieva';
$extra_css  = ['assets/css/chinh-sach-bao-hanh.css'];
require 'head.php';
?>
<?php include 'header.php'; ?>

<div class="policy-page-wrapper">

    <?php if (!$trang): ?>
        <section class="policy-hero">
            <div class="container policy-hero-grid">
                <div>
                    <span class="policy-hero-eyebrow"><i class="fa-solid fa-circle-info"></i> Chính sách</span>
                    <h1 class="policy-hero-title">Không tìm thấy trang</h1>
                    <p class="policy-hero-subtitle">Trang chính sách bạn tìm không tồn tại hoặc hiện đang được ẩn.</p>
                </div>
            </div>
        </section>
        <div class="policy-body container">
            <a href="index.php" class="policy-cta-btn"><i class="fa-solid fa-house"></i> Về trang chủ</a>
        </div>
    <?php else: ?>
        <section class="policy-hero">
            <div class="container policy-hero-grid">
                <div>
                    <span class="policy-hero-eyebrow"><i class="fa-solid fa-shield-halved"></i> Chính sách</span>
                    <h1 class="policy-hero-title"><?php echo htmlspecialchars($trang['policy_title']); ?></h1>
                    <?php if (trim($trang['policy_subtitle']) !== ''): ?>
                        <p class="policy-hero-subtitle"><?php echo htmlspecialchars($trang['policy_subtitle']); ?></p>
                    <?php endif; ?>
                </div>
                <?php if (trim($trang['policy_image']) !== ''): ?>
                    <div class="policy-hero-visual">
                        <img src="<?php echo htmlspecialchars($trang['policy_image']); ?>" alt="<?php echo htmlspecialchars($trang['policy_title']); ?>" style="max-width:100%;border-radius:16px;">
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <div class="policy-body container">
            <?php echo $trang['policy_content']; ?>
        </div>
    <?php endif; ?>

</div>

<?php require 'footer.php'; ?>
