<?php
require_once 'admin/config/config.php';

$session_id = session_id();
$loi = [];
$don_hang_thanh_cong = null;

$ma_khach_hang_dang_nhap = null;
$khach_hang_dang_nhap    = null;
if (isset($_SESSION['khach_hang_id'])) {
    $kh_stmt = $pdo->prepare("SELECT * FROM khach_hang_lien_he WHERE ma_lien_he = :id LIMIT 1");
    $kh_stmt->execute([':id' => $_SESSION['khach_hang_id']]);
    $khach_hang_dang_nhap = $kh_stmt->fetch(PDO::FETCH_ASSOC);
    if ($khach_hang_dang_nhap) {
        $ma_khach_hang_dang_nhap = (int) $khach_hang_dang_nhap['ma_lien_he'];
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'dat_hang') {
    $ten_khach_hang = trim($_POST['ten_khach_hang'] ?? '');
    $so_dien_thoai  = trim($_POST['so_dien_thoai'] ?? '');
    $dia_chi        = trim($_POST['dia_chi'] ?? '');
    $ghi_chu        = trim($_POST['ghi_chu'] ?? '');

    if ($ten_khach_hang === '') $loi[] = 'Vui lòng nhập họ và tên.';
    if ($so_dien_thoai === '') $loi[] = 'Vui lòng nhập số điện thoại.';
    if ($dia_chi === '') $loi[] = 'Vui lòng nhập địa chỉ nhận hàng.';

    $gio_hang_stmt = $pdo->prepare("SELECT gh.ma_gio_hang, gh.ma_san_pham, gh.so_luong AS so_luong_gio, sp.ten_san_pham, sp.gia_ban, sp.giam_gia, sp.so_luong AS ton_kho
        FROM gio_hang gh
        JOIN san_pham sp ON sp.ma_san_pham = gh.ma_san_pham
        WHERE gh.session_id = :sid AND sp.trang_thai = 1
        ORDER BY gh.ma_gio_hang DESC");
    $gio_hang_stmt->execute([':sid' => $session_id]);
    $gio_hang_items = $gio_hang_stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($gio_hang_items)) {
        $loi[] = 'Giỏ hàng của bạn đang trống.';
    }

    if (empty($loi)) {
        $tong_tien = 0;
        foreach ($gio_hang_items as $item) {
            $gia_ban_i      = (int) $item['gia_ban'];
            $giam_gia_i     = (int) $item['giam_gia'];
            $gia_sau_giam_i = $giam_gia_i > 0 ? (int) round($gia_ban_i * (100 - $giam_gia_i) / 100) : $gia_ban_i;
            $so_luong_dat   = min((int) $item['so_luong_gio'], (int) $item['ton_kho']);
            $tong_tien += $gia_sau_giam_i * $so_luong_dat;
        }

        $pdo->beginTransaction();
        try {
            $ins_don = $pdo->prepare("INSERT INTO don_hang (session_id, ma_khach_hang, ten_khach_hang, so_dien_thoai, dia_chi, ghi_chu, tong_tien, trang_thai)
                VALUES (:sid, :ma_kh, :ten, :sdt, :dc, :gc, :tt, 0)");
            $ins_don->execute([
                ':sid'   => $session_id,
                ':ma_kh' => $ma_khach_hang_dang_nhap,
                ':ten'   => $ten_khach_hang,
                ':sdt'   => $so_dien_thoai,
                ':dc'    => $dia_chi,
                ':gc'    => $ghi_chu !== '' ? $ghi_chu : null,
                ':tt'    => $tong_tien,
            ]);
            $ma_don_hang = (int) $pdo->lastInsertId();

            $ins_ct = $pdo->prepare("INSERT INTO don_hang_chi_tiet (ma_don_hang, ma_san_pham, ten_san_pham, so_luong, don_gia)
                VALUES (:ma_don_hang, :ma_sp, :ten_sp, :sl, :dg)");
            $upd_ton_kho = $pdo->prepare("UPDATE san_pham SET so_luong = so_luong - :sl, da_ban = da_ban + :sl WHERE ma_san_pham = :ma_sp");

            foreach ($gio_hang_items as $item) {
                $gia_ban_i      = (int) $item['gia_ban'];
                $giam_gia_i     = (int) $item['giam_gia'];
                $gia_sau_giam_i = $giam_gia_i > 0 ? (int) round($gia_ban_i * (100 - $giam_gia_i) / 100) : $gia_ban_i;
                $so_luong_dat   = min((int) $item['so_luong_gio'], (int) $item['ton_kho']);

                $ins_ct->execute([
                    ':ma_don_hang' => $ma_don_hang,
                    ':ma_sp'       => $item['ma_san_pham'],
                    ':ten_sp'      => $item['ten_san_pham'],
                    ':sl'          => $so_luong_dat,
                    ':dg'          => $gia_sau_giam_i,
                ]);
                $upd_ton_kho->execute([':sl' => $so_luong_dat, ':ma_sp' => $item['ma_san_pham']]);
            }

            $del_gio_hang = $pdo->prepare("DELETE FROM gio_hang WHERE session_id = :sid");
            $del_gio_hang->execute([':sid' => $session_id]);

            $pdo->commit();

            header('Location: gio-hang.php?dat_hang=thanhcong&ma=' . $ma_don_hang);
            exit;
        } catch (Exception $e) {
            $pdo->rollBack();
            $loi[] = 'Có lỗi xảy ra khi đặt hàng, vui lòng thử lại.';
        }
    }
}

if (isset($_GET['dat_hang']) && $_GET['dat_hang'] === 'thanhcong' && !empty($_GET['ma'])) {
    $ma_check = (int) $_GET['ma'];
    $stmt = $pdo->prepare("SELECT * FROM don_hang WHERE ma_don_hang = :id LIMIT 1");
    $stmt->execute([':id' => $ma_check]);
    $don_hang_thanh_cong = $stmt->fetch(PDO::FETCH_ASSOC);
}

$gio_hang_stmt = $pdo->prepare("SELECT gh.ma_gio_hang, gh.ma_san_pham, gh.so_luong AS so_luong_gio, sp.ten_san_pham, sp.hinh_anh, sp.gia_ban, sp.giam_gia, sp.so_luong AS ton_kho
    FROM gio_hang gh
    JOIN san_pham sp ON sp.ma_san_pham = gh.ma_san_pham
    WHERE gh.session_id = :sid AND sp.trang_thai = 1
    ORDER BY gh.ma_gio_hang DESC");
$gio_hang_stmt->execute([':sid' => $session_id]);
$gio_hang_items = $gio_hang_stmt->fetchAll(PDO::FETCH_ASSOC);

$tong_tien_gio_hang = 0;
foreach ($gio_hang_items as &$item) {
    $item['hinh_anh_dau']  = (function ($hinh_anh) {
        $images = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', (string) $hinh_anh))));
        return !empty($images) ? $images[0] : 'assets/image/pc.webp';
    })($item['hinh_anh']);
    $gia_ban_i              = (int) $item['gia_ban'];
    $giam_gia_i             = (int) $item['giam_gia'];
    $item['gia_sau_giam']   = $giam_gia_i > 0 ? (int) round($gia_ban_i * (100 - $giam_gia_i) / 100) : $gia_ban_i;
    $item['so_luong_gio']   = min((int) $item['so_luong_gio'], (int) $item['ton_kho']);
    $item['thanh_tien']     = $item['gia_sau_giam'] * $item['so_luong_gio'];
    $tong_tien_gio_hang    += $item['thanh_tien'];
}
unset($item);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/gio-hang.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <section class="cart-page">
        <div class="container">
            <div class="cart-page-header">
                <span class="cart-eyebrow">— Giỏ hàng của bạn</span>
                <h1 class="cart-title">Giỏ hàng</h1>
            </div>

            <?php if ($don_hang_thanh_cong): ?>
                <div class="cart-order-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <h3>Đặt hàng thành công!</h3>
                        <p>Mã đơn hàng <strong>#<?php echo (int) $don_hang_thanh_cong['ma_don_hang']; ?></strong> — Tổng tiền
                            <strong><?php echo number_format((int) $don_hang_thanh_cong['tong_tien'], 0, ',', '.'); ?>₫</strong>.
                            Chúng tôi sẽ liên hệ với bạn qua số điện thoại <strong><?php echo htmlspecialchars($don_hang_thanh_cong['so_dien_thoai']); ?></strong> để xác nhận đơn hàng.</p>
                        <a href="san-pham.php" class="btn-continue-shopping">Tiếp tục mua sắm <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                </div>
            <?php elseif (!empty($loi)): ?>
                <div class="cart-flash-error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <ul>
                        <?php foreach ($loi as $l): ?><li><?php echo htmlspecialchars($l); ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if (!$don_hang_thanh_cong): ?>
                <?php if (empty($gio_hang_items)): ?>
                    <div class="cart-empty">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <p>Giỏ hàng của bạn đang trống.</p>
                        <a href="san-pham.php" class="btn-continue-shopping">Xem sản phẩm <i class="fa-solid fa-arrow-right"></i></a>
                    </div>
                <?php else: ?>
                    <div class="cart-layout">
                        <div class="cart-items-col">
                            <div class="cart-items-list" id="cartItemsList">
                                <?php foreach ($gio_hang_items as $item): ?>
                                    <div class="cart-item" data-ma-gio-hang="<?php echo (int) $item['ma_gio_hang']; ?>">
                                        <div class="cart-item-media">
                                            <img src="<?php echo htmlspecialchars($item['hinh_anh_dau']); ?>" alt="<?php echo htmlspecialchars($item['ten_san_pham']); ?>" onerror="this.onerror=null;this.src='assets/image/pc.webp';">
                                        </div>
                                        <div class="cart-item-info">
                                            <h3 class="cart-item-name"><?php echo htmlspecialchars($item['ten_san_pham']); ?></h3>
                                            <span class="cart-item-unit-price"><?php echo number_format($item['gia_sau_giam'], 0, ',', '.'); ?>₫ / sản phẩm</span>
                                        </div>
                                        <div class="cart-item-qty">
                                            <div class="qty-control">
                                                <button type="button" class="qty-minus" aria-label="Giảm số lượng">-</button>
                                                <input type="number" class="qty-input" value="<?php echo (int) $item['so_luong_gio']; ?>" min="1" max="<?php echo (int) $item['ton_kho']; ?>">
                                                <button type="button" class="qty-plus" aria-label="Tăng số lượng">+</button>
                                            </div>
                                        </div>
                                        <div class="cart-item-total">
                                            <span class="cart-item-line-total"><?php echo number_format($item['thanh_tien'], 0, ',', '.'); ?>₫</span>
                                        </div>
                                        <button type="button" class="cart-item-remove" aria-label="Xoá sản phẩm">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="cart-summary-col">
                            <div class="cart-summary-box">
                                <h3>Thông tin đặt hàng</h3>
                                <div class="cart-summary-row">
                                    <span>Tổng tiền hàng</span>
                                    <strong id="cartSummaryTotal"><?php echo number_format($tong_tien_gio_hang, 0, ',', '.'); ?>₫</strong>
                                </div>

                                <?php if ($khach_hang_dang_nhap): ?>
                                    <div class="cart-account-hint">
                                        <i class="fa-solid fa-circle-user"></i> Đặt hàng với tài khoản <strong><?php echo htmlspecialchars($khach_hang_dang_nhap['customer_name']); ?></strong> — đơn hàng sẽ được lưu vào <a href="tai-khoan.php">tài khoản của bạn</a>.
                                    </div>
                                <?php else: ?>
                                    <div class="cart-account-hint">
                                        <i class="fa-solid fa-circle-info"></i> <a href="tai-khoan.php">Đăng nhập</a> để lưu lại lịch sử đơn hàng và đặt nhanh hơn lần sau.
                                    </div>
                                <?php endif; ?>
                                <form class="cart-checkout-form" method="POST" action="gio-hang.php">
                                    <input type="hidden" name="action" value="dat_hang">
                                    <div class="form-group">
                                        <label for="ten_khach_hang">Họ và tên</label>
                                        <input type="text" name="ten_khach_hang" id="ten_khach_hang" placeholder="Nhập họ và tên" value="<?php echo $khach_hang_dang_nhap ? htmlspecialchars($khach_hang_dang_nhap['customer_name']) : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="so_dien_thoai">Số điện thoại</label>
                                        <input type="text" name="so_dien_thoai" id="so_dien_thoai" placeholder="Nhập số điện thoại" value="<?php echo $khach_hang_dang_nhap ? htmlspecialchars($khach_hang_dang_nhap['customer_phone']) : ''; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <textarea name="email" id="email" placeholder="Nhập emai của bạn " required><?php echo $khach_hang_dang_nhap ? htmlspecialchars($khach_hang_dang_nhap['customer_email']) : ''; ?></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="dia_chi">Địa chỉ nhận hàng</label>
                                        <textarea name="dia_chi" id="dia_chi" rows="3" placeholder="Nhập địa chỉ nhận hàng" required><?php echo $khach_hang_dang_nhap ? htmlspecialchars($khach_hang_dang_nhap['customer_address']) : ''; ?></textarea>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="ghi_chu">Ghi chú (tuỳ chọn)</label>
                                        <textarea name="ghi_chu" id="ghi_chu" rows="2" placeholder="Ghi chú thêm cho đơn hàng"></textarea>
                                    </div>

                                    <button type="submit" class="btn-checkout">Đặt hàng <i class="fa-solid fa-arrow-right"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </section>
    <?php include 'footer.php'; ?>

    <script src="assets/js/gio-hang.js"></script>
</body>

</html>
