<?php
require_once 'admin/config/config.php';

if (!isset($_SESSION['khach_hang_id'])) {
    header('Location: tai-khoan.php');
    exit;
}

$ma_don_hang = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM don_hang WHERE ma_don_hang = :id AND ma_khach_hang = :ma_kh LIMIT 1");
$stmt->execute([':id' => $ma_don_hang, ':ma_kh' => $_SESSION['khach_hang_id']]);
$don_hang = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$don_hang) {
    header('Location: tai-khoan.php');
    exit;
}

$ct_stmt = $pdo->prepare("SELECT dhct.*, sp.hinh_anh
    FROM don_hang_chi_tiet dhct
    LEFT JOIN san_pham sp ON sp.ma_san_pham = dhct.ma_san_pham
    WHERE dhct.ma_don_hang = :id ORDER BY dhct.ma_chi_tiet ASC");
$ct_stmt->execute([':id' => $ma_don_hang]);
$chi_tiet_list = $ct_stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($chi_tiet_list as &$ct) {
    $images = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', (string) $ct['hinh_anh']))));
    $ct['hinh_anh_dau'] = !empty($images) ? $images[0] : 'assets/image/pc.webp';
    $ct['thanh_tien']   = (int) $ct['don_gia'] * (int) $ct['so_luong'];
}
unset($ct);

$trang_thai_nhan = [
    0 => ['Chờ xử lý', '#6b7280'],
    1 => ['Đã xác nhận', '#2563eb'],
    2 => ['Đang giao', '#d97706'],
    3 => ['Hoàn thành', '#16a34a'],
    4 => ['Đã huỷ', '#dc2626'],
];

$page_title = 'Đơn hàng #' . (int) $don_hang['ma_don_hang'] . ' - Viết Sơn Achieva';
$extra_css  = ['assets/css/gio-hang.css', 'assets/css/tai-khoan.css'];
require 'head.php';
?>
    <?php include 'header.php'; ?>

    <section class="cart-page">
        <div class="container">
            <div class="cart-page-header">
                <span class="cart-eyebrow"><a href="tai-khoan.php">← Đơn hàng của tôi</a></span>
                <h1 class="cart-title">Đơn hàng #<?php echo (int) $don_hang['ma_don_hang']; ?></h1>
                <span class="my-order-status order-detail-status"
                    style="color:<?php echo $trang_thai_nhan[(int) $don_hang['trang_thai']][1]; ?>">
                    <?php echo htmlspecialchars($trang_thai_nhan[(int) $don_hang['trang_thai']][0]); ?>
                </span>
            </div>

            <div class="cart-layout">
                <div class="cart-items-col">
                    <div class="cart-items-list">
                        <?php foreach ($chi_tiet_list as $ct): ?>
                        <div class="cart-item">
                            <div class="cart-item-media">
                                <img src="<?php echo htmlspecialchars($ct['hinh_anh_dau']); ?>"
                                    alt="<?php echo htmlspecialchars($ct['ten_san_pham']); ?>"
                                    onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                            </div>
                            <div class="cart-item-info">
                                <h3 class="cart-item-name"><?php echo htmlspecialchars($ct['ten_san_pham']); ?></h3>
                                <span
                                    class="cart-item-unit-price"><?php echo number_format((int) $ct['don_gia'], 0, ',', '.'); ?>₫
                                    / sản phẩm</span>
                            </div>
                            <div class="cart-item-qty">
                                <span>Số lượng: <strong><?php echo (int) $ct['so_luong']; ?></strong></span>
                            </div>
                            <div class="cart-item-total">
                                <span
                                    class="cart-item-line-total"><?php echo number_format($ct['thanh_tien'], 0, ',', '.'); ?>₫</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="cart-summary-col">
                    <div class="cart-summary-box">
                        <h3>Thông tin đơn hàng</h3>
                        <div class="cart-summary-row">
                            <span>Tổng tiền hàng</span>
                            <strong><?php echo number_format((int) $don_hang['tong_tien'], 0, ',', '.'); ?>₫</strong>
                        </div>

                        <div class="account-info-list" style="margin-top:6px;">
                            <div class="account-info-row"><span>Người
                                    nhận</span><strong><?php echo htmlspecialchars($don_hang['ten_khach_hang']); ?></strong>
                            </div>
                            <div class="account-info-row"><span>Số điện
                                    thoại</span><strong><?php echo htmlspecialchars($don_hang['so_dien_thoai']); ?></strong>
                            </div>
                            <div class="account-info-row"><span>Địa
                                    chỉ</span><strong><?php echo htmlspecialchars($don_hang['dia_chi']); ?></strong>
                            </div>
                            <?php if (!empty($don_hang['ghi_chu'])): ?>
                            <div class="account-info-row"><span>Ghi
                                    chú</span><strong><?php echo htmlspecialchars($don_hang['ghi_chu']); ?></strong>
                            </div>
                            <?php endif; ?>
                            <div class="account-info-row"><span>Ngày
                                    đặt</span><strong><?php echo date('d/m/Y H:i', strtotime($don_hang['ngay_dat'])); ?></strong>
                            </div>
                        </div>

                        <a href="san-pham.php" class="btn-continue-shopping"
                            style="width:100%; justify-content:center; margin-top:16px;">Tiếp tục mua sắm <i
                                class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'footer.php'; ?>
</body>

</html>