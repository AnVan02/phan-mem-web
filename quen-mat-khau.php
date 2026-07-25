<?php
require_once 'admin/config/config.php';

if (isset($_SESSION['khach_hang_id'])) {
    header('Location: ' . asset_url('tai-khoan.php'));
    exit;
}

$thong_bao = [
    'loi_thieu_email' => ['error', 'Vui lòng nhập địa chỉ email.'],
    'loi_token_het_han' => ['error', 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng gửi lại yêu cầu.'],
    'da_gui_email' => ['success', 'Nếu email tồn tại trong hệ thống, chúng tôi đã gửi liên kết đặt lại mật khẩu. Vui lòng kiểm tra hộp thư (kể cả mục spam).'],
];
$msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;

$page_title = 'Quên mật khẩu - Viết Sơn Achieva';
$extra_css  = ['assets/css/tai-khoan.css'];
require 'head.php';
?>
    <?php include 'header.php'; ?>

    <div class="main-content layout-contact">
        <div class="page-header contact-header">
            <h1 class="page-title">Quên mật khẩu</h1>
            <p class="page-subtitle">Nhập email đã đăng ký, chúng tôi sẽ gửi cho bạn liên kết để đặt lại mật khẩu.</p>
        </div>

        <?php if ($msg): ?>
        <div class="contact-success <?php echo $msg[0] === 'error' ? 'is-error' : ''; ?>" style="max-width:480px; margin:0 auto 24px;">
            <i class="fa-solid <?php echo $msg[0] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'; ?>"></i>
            <span><?php echo htmlspecialchars($msg[1]); ?></span>
        </div>
        <?php endif; ?>

        <div class="contact-card form-card" style="max-width:480px; margin:0 auto;">
            <form class="contact-form" action="<?php echo htmlspecialchars(asset_url('xuly-tai-khoan.php')); ?>" method="POST">
                <input type="hidden" name="action" value="quen_mat_khau">
                <div class="form-group">
                    <label for="forgot_email">Email</label>
                    <input type="email" name="customer_email" id="forgot_email" placeholder="Nhập email đã đăng ký" required>
                </div>
                <button type="submit" class="btn-submit btn-block">Gửi liên kết đặt lại &nbsp;<i class="fa-solid fa-paper-plane"></i></button>
            </form>
            <p class="page-subtitle" style="margin-top:18px;"><a href="<?php echo htmlspecialchars(asset_url('tai-khoan.php')); ?>">← Quay lại đăng nhập</a></p>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>

</html>
