<?php
require_once 'admin/config/config.php';

$stmt = $pdo->prepare("SELECT * FROM policy_page WHERE policy_slug = :slug AND policy_status = 1 LIMIT 1");
$stmt->execute([':slug' => 'bao-hanh']);
$trang = $stmt->fetch(PDO::FETCH_ASSOC);

$policy_title    = $trang ? $trang['policy_title'] : 'Chính sách bảo hành & đổi trả';
$policy_subtitle = $trang ? $trang['policy_subtitle'] : 'Việt Sơn Achieva cam kết mang đến trải nghiệm mua sắm an tâm và tin cậy cho khách hàng ở mọi sản phẩm linh kiện, thiết bị.';

$page_title = htmlspecialchars($policy_title) . ' - Việt Sơn Achieva';
$extra_css  = ['assets/css/chinh-sach-bao-hanh.css'];
require 'head.php';
?>
<?php include 'header.php'; ?>

<div class="policy-page-wrapper">

    <!-- Hero -->
    <section class="policy-hero">
        <div class="container policy-hero-grid">
            <div>
                <span class="policy-hero-eyebrow"><i class="fa-solid fa-shield-halved"></i> Cam kết chính hãng</span>
                <h1 class="policy-hero-title"><?php echo htmlspecialchars($policy_title); ?></h1>
                <p class="policy-hero-subtitle"><?php echo htmlspecialchars($policy_subtitle); ?></p>
            </div>
            <div class="policy-hero-visual">
                <div class="policy-hero-visual-platform"></div>
                <div class="policy-hero-visual-shield"><i class="fa-solid fa-shield-check"></i></div>
                <div class="policy-hero-visual-orbit orbit-1"><i class="fa-solid fa-rotate-left"></i></div>
                <div class="policy-hero-visual-orbit orbit-2"><i class="fa-solid fa-file-circle-check"></i></div>
            </div>
        </div>
    </section>
        <div class="container policy-hero-features-wrap">
            <div class="policy-hero-features">
                <div class="policy-feature-card">
                    <div class="policy-feature-icon"><i class="fa-solid fa-shield-check"></i></div>
                    <strong>Bảo hành chính hãng</strong>
                    <span>Sản phẩm được bảo hành theo chính sách của nhà sản xuất</span>
                </div>
                <div class="policy-feature-card">
                    <div class="policy-feature-icon"><i class="fa-solid fa-rotate-left"></i></div>
                    <strong>Đổi trả dễ dàng</strong>
                    <span>Đổi trả nhanh chóng trong vòng 7 ngày nếu đủ điều kiện</span>
                </div>
                <div class="policy-feature-card">
                    <div class="policy-feature-icon"><i class="fa-solid fa-headset"></i></div>
                    <strong>Hỗ trợ tận tâm</strong>
                    <span>Đội ngũ CSKH luôn sẵn sàng hỗ trợ 24/7</span>
                </div>
                <div class="policy-feature-card">
                    <div class="policy-feature-icon"><i class="fa-solid fa-scale-balanced"></i></div>
                    <strong>Minh bạch - Rõ ràng</strong>
                    <span>Quy trình rõ ràng, thông tin minh bạch, dễ hiểu</span>
                </div>
            </div>
        </div>
        
    <div class="policy-body container">

        <?php if ($trang): ?>
            <?php echo $trang['policy_content']; ?>
        <?php else: ?>
            <p>Nội dung chính sách đang được cập nhật.</p>
        <?php endif; ?>

    </div>

    <!-- Bottom info bar -->
    <section class="policy-info-bar">
        <div class="container policy-info-grid">
            <div class="policy-info-item">
                <i class="fa-solid fa-shield-check"></i>
                <span>100% hàng chính hãng<br>Cam kết chính hãng 100%</span>
            </div>
            <div class="policy-info-item">
                <i class="fa-solid fa-rotate-left"></i>
                <span>Đổi trả miễn phí<br>Trong 7 ngày nếu lỗi NSX</span>
            </div>
            <div class="policy-info-item">
                <i class="fa-solid fa-truck-fast"></i>
                <span>Giao hàng toàn quốc<br>Nhanh chóng, tiện lợi</span>
            </div>
            <div class="policy-info-item">
                <i class="fa-solid fa-headset"></i>
                <span>Hỗ trợ 24/7<br>Luôn sẵn sàng hỗ trợ bạn</span>
            </div>
        </div>
    </section>

</div>

<?php require 'footer.php'; ?>
