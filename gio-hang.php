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

        $ma_giam_gia_code = trim($_POST['ma_giam_gia'] ?? '');
        $phan_tram_giam_gia = 0;
        if (!empty($ma_giam_gia_code)) {
            $check_voucher = $pdo->prepare("SELECT phan_tram_giam FROM ma_giam_gia WHERE code = :code AND trang_thai = 1 LIMIT 1");
            $check_voucher->execute([':code' => $ma_giam_gia_code]);
            $giam = $check_voucher->fetchColumn();
            if ($giam !== false) {
                $phan_tram_giam_gia = (int) $giam;
            }
        }
        
        if ($phan_tram_giam_gia > 0) {
            $tong_tien = $tong_tien - ($tong_tien * $phan_tram_giam_gia / 100);
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
        return asset_url(!empty($images) ? $images[0] : 'assets/image/pc.webp');
    })($item['hinh_anh']);
    $gia_ban_i              = (int) $item['gia_ban'];
    $giam_gia_i             = (int) $item['giam_gia'];
    $item['gia_sau_giam']   = $giam_gia_i > 0 ? (int) round($gia_ban_i * (100 - $giam_gia_i) / 100) : $gia_ban_i;
    $item['so_luong_gio']   = min((int) $item['so_luong_gio'], (int) $item['ton_kho']);
    $item['thanh_tien']     = $item['gia_sau_giam'] * $item['so_luong_gio'];
    $tong_tien_gio_hang    += $item['thanh_tien'];
}
unset($item);

$page_title = 'Giỏ hàng - Viết Sơn Achieva';
$extra_css  = ['assets/css/gio-hang.css'];
require 'head.php';
?>
    <?php include 'header.php'; ?>

    <section class="cart-page">
        <div class="container">

            <?php if ($don_hang_thanh_cong): ?>
                <div class="cart-order-success">
                    <i class="fa-solid fa-circle-check"></i>
                    <div>
                        <h3>Đặt hàng thành công!</h3>
                        <p>Mã đơn hàng <strong>#<?php echo (int) $don_hang_thanh_cong['ma_don_hang']; ?></strong> — Tổng tiền
                            <strong><?php echo number_format((int) $don_hang_thanh_cong['tong_tien'], 0, ',', '.'); ?>₫</strong>.
                            Chúng tôi sẽ liên hệ với bạn qua số điện thoại <strong><?php echo htmlspecialchars($don_hang_thanh_cong['so_dien_thoai']); ?></strong> để xác nhận đơn hàng.</p>
                        <a href="<?php echo htmlspecialchars(asset_url('san-pham.php')); ?>" class="btn-continue-shopping">Tiếp tục mua sắm <i class="fa-solid fa-arrow-right"></i></a>
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
                    <!-- ===== EMPTY CART ===== -->
                    <nav class="cart-breadcrumb" aria-label="Breadcrumb">
                        <a href="<?php echo htmlspecialchars(asset_url('index.php')); ?>" class="cart-breadcrumb-home" aria-label="Trang chủ">
                            <i class="fa-solid fa-house"></i>
                        </a>
                        <span class="cart-breadcrumb-sep"><i class="fa-solid fa-chevron-right"></i></span>
                        <span class="cart-breadcrumb-current">Giỏ hàng của bạn</span>
                    </nav>

                    <div class="cart-page-title-wrap">
                        <h1 class="cart-page-title">Giỏ hàng</h1>
                        <div class="cart-page-title-line"></div>
                    </div>

                    <div class="cart-empty-card">
                        <!-- Illustration -->
                        <div class="cart-empty-illustration">
                            <svg viewBox="0 0 340 280" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                                <circle cx="160" cy="155" r="110" fill="#EEF2FF"/>
                                <text x="52" y="72" font-size="16" fill="#CBD5E1" font-weight="bold">&#10005;</text>
                                <text x="285" y="88" font-size="14" fill="#CBD5E1" font-weight="bold">&#10005;</text>
                                <text x="38" y="188" font-size="12" fill="#CBD5E1" font-weight="bold">&#10005;</text>
                                <path d="M80 238 Q68 222 75 210 Q88 220 80 238Z" fill="#86EFAC"/>
                                <path d="M80 238 Q75 218 85 210 Q90 225 80 238Z" fill="#4ADE80"/>
                                <line x1="80" y1="238" x2="80" y2="215" stroke="#4ADE80" stroke-width="1.5"/>
                                <path d="M245 242 Q260 228 252 216 Q240 226 245 242Z" fill="#86EFAC"/>
                                <path d="M245 242 Q248 222 238 218 Q234 232 245 242Z" fill="#4ADE80"/>
                                <line x1="245" y1="242" x2="245" y2="219" stroke="#4ADE80" stroke-width="1.5"/>
                                <polygon points="110,220 118,234 102,234" fill="none" stroke="#CBD5E1" stroke-width="1.5"/>
                                <polygon points="222,62 229,74 215,74" fill="none" stroke="#CBD5E1" stroke-width="1.5"/>
                                <rect x="95" y="120" width="130" height="90" rx="8" fill="#94A3B8"/>
                                <rect x="100" y="125" width="120" height="80" rx="6" fill="#B0BEC5"/>
                                <line x1="120" y1="125" x2="120" y2="205" stroke="#90A4AE" stroke-width="1.5"/>
                                <line x1="140" y1="125" x2="140" y2="205" stroke="#90A4AE" stroke-width="1.5"/>
                                <line x1="160" y1="125" x2="160" y2="205" stroke="#90A4AE" stroke-width="1.5"/>
                                <line x1="180" y1="125" x2="180" y2="205" stroke="#90A4AE" stroke-width="1.5"/>
                                <line x1="200" y1="125" x2="200" y2="205" stroke="#90A4AE" stroke-width="1.5"/>
                                <line x1="100" y1="145" x2="220" y2="145" stroke="#90A4AE" stroke-width="1.5"/>
                                <line x1="100" y1="165" x2="220" y2="165" stroke="#90A4AE" stroke-width="1.5"/>
                                <line x1="100" y1="185" x2="220" y2="185" stroke="#90A4AE" stroke-width="1.5"/>
                                <path d="M80 110 Q80 95 95 95 L110 95" stroke="#607D8B" stroke-width="8" fill="none" stroke-linecap="round"/>
                                <rect x="76" y="108" width="8" height="16" rx="4" fill="#607D8B"/>
                                <circle cx="120" cy="218" r="12" fill="#607D8B"/>
                                <circle cx="120" cy="218" r="6" fill="#455A64"/>
                                <circle cx="200" cy="218" r="12" fill="#607D8B"/>
                                <circle cx="200" cy="218" r="6" fill="#455A64"/>
                                <rect x="108" y="207" width="104" height="6" rx="3" fill="#607D8B"/>
                                <circle cx="195" cy="105" r="28" fill="#3B82F6"/>
                                <circle cx="187" cy="99" r="3" fill="white"/>
                                <circle cx="203" cy="99" r="3" fill="white"/>
                                <path d="M186 110 Q195 104 204 110" stroke="white" stroke-width="2.5" fill="none" stroke-linecap="round"/>
                                <path d="M178 120 L170 130 L185 122Z" fill="#3B82F6"/>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="cart-empty-content">
                            <div class="cart-empty-icon-wrap">
                                <i class="fa-solid fa-bag-shopping"></i>
                            </div>
                            <h2 class="cart-empty-title">Giỏ hàng của bạn đang trống</h2>
                            <p class="cart-empty-desc">Có vẻ như bạn chưa thêm sản phẩm nào vào giỏ hàng.<br>Hãy khám phá các sản phẩm tuyệt vời của chúng tôi!</p>
                            <a href="<?php echo htmlspecialchars(asset_url('san-pham.php')); ?>" class="cart-empty-btn">
                                <i class="fa-solid fa-bag-shopping"></i>
                                Khám phá sản phẩm
                                <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Trust badges -->
                    <div class="cart-trust-badges">
                        <div class="cart-trust-item">
                            <div class="cart-trust-icon cart-trust-icon--green">
                                <i class="fa-solid fa-shield-halved"></i>
                            </div>
                            <div class="cart-trust-text">
                                <strong>Sản phẩm chính hãng</strong>
                                <span>Đảm bảo 100% chính hãng</span>
                            </div>
                        </div>
                        <div class="cart-trust-item">
                            <div class="cart-trust-icon cart-trust-icon--purple">
                                <i class="fa-solid fa-truck-fast"></i>
                            </div>
                            <div class="cart-trust-text">
                                <strong>Giao hàng nhanh chóng</strong>
                                <span>Giao hàng tận nơi toàn quốc</span>
                            </div>
                        </div>
                        <div class="cart-trust-item">
                            <div class="cart-trust-icon cart-trust-icon--orange">
                                <i class="fa-solid fa-headset"></i>
                            </div>
                            <div class="cart-trust-text">
                                <strong>Hỗ trợ tận tâm</strong>
                                <span>Hỗ trợ 24/7, giải đáp nhanh chóng</span>
                            </div>
                        </div>
                    </div>

                <?php else: ?>
                    <div class="cart-layout">
                        <div class="cart-items-col">
                            <div class="cart-items-list" id="cartItemsList">
                                <?php foreach ($gio_hang_items as $item): ?>
                                    <div class="cart-item" data-ma-gio-hang="<?php echo (int) $item['ma_gio_hang']; ?>">
                                        <div class="cart-item-media">
                                            <img src="<?php echo htmlspecialchars($item['hinh_anh_dau']); ?>" alt="<?php echo htmlspecialchars($item['ten_san_pham']); ?>" onerror="this.onerror=null;this.src='<?php echo htmlspecialchars(asset_url('assets/image/pc.webp')); ?>';">
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
                                        <i class="fa-solid fa-circle-user"></i> Đặt hàng với tài khoản <strong><?php echo htmlspecialchars($khach_hang_dang_nhap['customer_name']); ?></strong> — đơn hàng sẽ được lưu vào <a href="<?php echo htmlspecialchars(asset_url('tai-khoan.php')); ?>">tài khoản của bạn</a>.
                                    </div>
                                <?php else: ?>
                                    <div class="cart-account-hint">
                                        <i class="fa-solid fa-circle-info"></i> <a href="<?php echo htmlspecialchars(asset_url('tai-khoan.php')); ?>">Đăng nhập</a> để lưu lại lịch sử đơn hàng và đặt nhanh hơn lần sau.
                                    </div>
                                <?php endif; ?>
                                <form class="cart-checkout-form" method="POST" action="<?php echo htmlspecialchars(asset_url('gio-hang.php')); ?>">
                                    <input type="hidden" name="action" value="dat_hang">
                                    <div class="form-group" style="position: relative; display: flex; gap: 10px;">
                                        <input type="text" name="ma_giam_gia" id="ma_giam_gia" placeholder="Nhập mã giảm giá (nếu có)" style="flex: 1; text-transform: uppercase;">
                                        <button type="button" id="btnApplyDiscount" style="padding: 10px 15px; background: #1f2937; color: white; border: none; border-radius: 6px; cursor: pointer;">Ap dụng</button>
                                    </div>
                                    <div id="discountMessage" style="margin-bottom: 15px; font-size: 15px;"></div>
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
                                        <textarea name="email" id="email" placeholder="Nhập email của bạn" required><?php echo $khach_hang_dang_nhap ? htmlspecialchars($khach_hang_dang_nhap['customer_email']) : ''; ?></textarea>
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

    <script src="<?php echo htmlspecialchars(asset_url('assets/js/gio-hang.js')); ?>"></script>
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const btnApply = document.getElementById('btnApplyDiscount');
        if (btnApply) {
            btnApply.addEventListener('click', () => {
                const code = document.getElementById('ma_giam_gia').value.trim();
                const msgBox = document.getElementById('discountMessage');
                if (!code) {
                    msgBox.innerHTML = '<span style="color: #dc2626;">Vui lòng nhập mã giảm giá</span>';
                    return;
                }
                
                const formData = new FormData();
                formData.append('code', code);
                
                fetch('giam-gia-ajax.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        msgBox.innerHTML = `<span style="color: #16a34a;"><i class="fa-solid fa-check"></i> ${data.message}</span>`;
                        // Update UI total
                        const currentTotal = <?php echo (int) $tong_tien_gio_hang; ?>;
                        const newTotal = currentTotal - (currentTotal * data.phan_tram_giam / 100);
                        document.getElementById('cartSummaryTotal').innerText = new Intl.NumberFormat('vi-VN').format(newTotal) + '₫';
                    } else {
                        msgBox.innerHTML = `<span style="color: #dc2626;"><i class="fa-solid fa-triangle-exclamation"></i> ${data.message}</span>`;
                        document.getElementById('cartSummaryTotal').innerText = new Intl.NumberFormat('vi-VN').format(<?php echo (int) $tong_tien_gio_hang; ?>) + '₫';
                    }
                })
                .catch(err => console.error(err));
            });
        }
    });
    </script>
</body>
</html>
