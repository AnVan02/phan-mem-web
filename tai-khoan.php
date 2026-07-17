<?php
require_once 'admin/config/config.php';

$da_dang_nhap = isset($_SESSION['khach_hang_id']);
$khach_hang   = null;
$don_hang_toi = [];
$san_pham_yeu_thich = [];
$lich_su_danh_gia = [];

if ($da_dang_nhap) {
    $stmt = $pdo->prepare("SELECT * FROM khach_hang_lien_he WHERE ma_lien_he = :id LIMIT 1");
    $stmt->execute([':id' => $_SESSION['khach_hang_id']]);
    $khach_hang = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$khach_hang) {
        unset($_SESSION['khach_hang_id'], $_SESSION['khach_hang_ten']);
        $da_dang_nhap = false;
    } else {
        $dh_stmt = $pdo->prepare("SELECT dh.*, (SELECT COUNT(*) FROM don_hang_chi_tiet ct WHERE ct.ma_don_hang = dh.ma_don_hang) AS so_mat_hang
            FROM don_hang dh WHERE dh.ma_khach_hang = :id ORDER BY dh.ngay_dat DESC");
        $dh_stmt->execute([':id' => $_SESSION['khach_hang_id']]);
        $don_hang_toi = $dh_stmt->fetchAll(PDO::FETCH_ASSOC);

        $yt_stmt = $pdo->prepare("SELECT sp.* FROM san_pham_yeu_thich yt JOIN san_pham sp ON yt.ma_san_pham = sp.ma_san_pham WHERE yt.ma_khach_hang = :id ORDER BY yt.ngay_them DESC");
        $yt_stmt->execute([':id' => $_SESSION['khach_hang_id']]);
        $san_pham_yeu_thich = $yt_stmt->fetchAll(PDO::FETCH_ASSOC);

        $dg_stmt = $pdo->prepare("SELECT dg.*, sp.ten_san_pham FROM danh_gia_san_pham dg JOIN san_pham sp ON dg.ma_san_pham = sp.ma_san_pham WHERE dg.ma_khach_hang = :id ORDER BY dg.ngay_danh_gia DESC");
        $dg_stmt->execute([':id' => $_SESSION['khach_hang_id']]);
        $lich_su_danh_gia = $dg_stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Số liệu cho dải thống kê đầu trang
$so_don_hang    = count($don_hang_toi);
$so_yeu_thich   = count($san_pham_yeu_thich);
$so_danh_gia    = count($lich_su_danh_gia);

// Phân trang cho danh sách "Đơn hàng của tôi"
$SO_DH_MOI_TRANG = 5;
$tong_trang_dh   = max(1, (int) ceil($so_don_hang / $SO_DH_MOI_TRANG));
$trang_dh        = isset($_GET['trang_dh']) ? (int) $_GET['trang_dh'] : 1;
if ($trang_dh < 1) $trang_dh = 1;
if ($trang_dh > $tong_trang_dh) $trang_dh = $tong_trang_dh;
$don_hang_trang  = array_slice($don_hang_toi, ($trang_dh - 1) * $SO_DH_MOI_TRANG, $SO_DH_MOI_TRANG);

function xay_url_trang_dh($trang)

{
    $params = $_GET;
    $params['trang_dh'] = $trang;
    return 'tai-khoan.php?' . http_build_query($params) . '#don-hang-toi';
}
// Chưa có bảng mã giảm giá trong CSDL, tạm để 0 - bổ sung sau khi có bảng thật.
$so_ma_giam_gia = 0;

$trang_thai_nhan = [
    0 => ['Chờ xử lý', '#6b7280'],
    1 => ['Đã xác nhận', '#2563eb'],
    2 => ['Đang giao', '#d97706'],
    3 => ['Hoàn thành', '#16a34a'],
    4 => ['Đã huỷ', '#dc2626'],
];

$thong_bao = [
    'dang_ky_thanh_cong'       => ['success', 'Đăng ký tài khoản thành công! Chào mừng bạn đến với Viết Sơn Achieva.'],
    'dang_nhap_thanh_cong'     => ['success', 'Đăng nhập thành công!'],
    'loi_dang_nhap'            => ['error', 'Email hoặc mật khẩu không chính xác.'],
    'loi_email_ton_tai'        => ['error', 'Email này đã được đăng ký, vui lòng đăng nhập.'],
    'loi_thieu_du_lieu'        => ['error', 'Vui lòng nhập đầy đủ thông tin.'],
    'loi_mat_khau_ngan'        => ['error', 'Mật khẩu phải có ít nhất 6 ký tự.'],
    'loi_mat_khau_khong_khop'  => ['error', 'Mật khẩu nhắc lại không khớp.'],
    'cap_nhat_thanh_cong'      => ['success', 'Cập nhật thông tin tài khoản thành công!'],
    'loi_cap_nhat'             => ['error', 'Có lỗi xảy ra khi cập nhật thông tin.'],
    'doi_mk_thanh_cong'        => ['success', 'Đổi mật khẩu thành công!'],
    'loi_mk_cu_sai'            => ['error', 'Mật khẩu hiện tại không chính xác.'],
    'gui_ho_tro_thanh_cong'    => ['success', 'Đã gửi yêu cầu hỗ trợ thành công. Chúng tôi sẽ phản hồi sớm nhất.'],
];
$msg = isset($_GET['msg']) && isset($thong_bao[$_GET['msg']]) ? $thong_bao[$_GET['msg']] : null;
$tab_mac_dinh = ($_GET['tab'] ?? '') === 'dang-ky' ? 'dang-ky' : 'dang-nhap';

// Helper lấy ảnh đầu tiên của sản phẩm (cột hinh_anh có thể chứa nhiều ảnh phân tách bởi , ;)
function lay_anh_dau($chuoi_anh) {
    $ds = array_values(array_filter(array_map('trim', preg_split('/[,;]+/', (string) $chuoi_anh))));
    return !empty($ds) ? $ds[0] : 'assets/image/pc.webp';
}

$page_title = ($da_dang_nhap ? 'Tài khoản của tôi' : 'Đăng nhập / Đăng ký') . ' - Viết Sơn Achieva';
$extra_css  = ['assets/css/tai-khoan.css'];
require 'head.php';
?>
    <?php include 'header.php'; ?>

    <div class="main-content layout-contact">
        <div class="page-header contact-header">
            <?php if ($da_dang_nhap): ?>
            <div class="account-banner">
                <div class="account-avatar"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($khach_hang['customer_name'], 0, 1, 'UTF-8'), 'UTF-8')); ?></div>
                <div class="account-banner-text">
                    <h1 class="page-title">Xin chào, <?php echo htmlspecialchars($khach_hang['customer_name']); ?> <span class="wave">👋</span></h1>
                    <p class="page-subtitle">Quản lý thông tin tài khoản và theo dõi đơn hàng của bạn tại đây.</p>
                </div>
            </div>
            <?php else: ?>
            <h1 class="page-title">Đăng nhập / Đăng ký tài khoản</h1>
            <p class="page-subtitle">
                Đăng nhập vào tài khoản để mua hàng thuận tiện hơn, lưu lại lịch sử đơn hàng và nhận thông tin ưu đãi từ
                cửa hàng.
            </p>
            <?php endif; ?>
        </div>

        <?php if ($msg): ?>
        <div class="contact-success <?php echo $msg[0] === 'error' ? 'is-error' : ''; ?>">
            <i class="fa-solid <?php echo $msg[0] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'; ?>"></i>
            <span><?php echo htmlspecialchars($msg[1]); ?></span>
        </div>
        <?php endif; ?>

        <?php if ($da_dang_nhap): ?>

        <!-- Dải thống kê -->
        <div class="stat-bar">
            <a href="#don-hang-toi" class="stat-item">
                <div class="stat-icon is-blue"><i class="fa-solid fa-bag-shopping"></i></div>
                <div>
                    <div class="stat-num"><?php echo $so_don_hang; ?></div>
                    <div class="stat-label">Đơn hàng</div>
                    <span class="stat-link is-blue">Xem lịch sử</span>
                </div>
            </a>
            <a href="#yeu-thich" class="stat-item">
                <div class="stat-icon is-purple"><i class="fa-solid fa-heart"></i></div>
                <div>
                    <div class="stat-num"><?php echo $so_yeu_thich; ?></div>
                    <div class="stat-label">Sản phẩm yêu thích</div>
                    <span class="stat-link is-purple">Xem danh sách</span>
                </div>
            </a>
            <a href="#danh-gia" class="stat-item">
                <div class="stat-icon is-amber"><i class="fa-solid fa-star"></i></div>
                <div>
                    <div class="stat-num"><?php echo $so_danh_gia; ?></div>
                    <div class="stat-label">Đánh giá của bạn</div>
                    <span class="stat-link is-amber">Viết đánh giá</span>
                </div>
            </a>
            <a href="#" class="stat-item" title="Tính năng mã giảm giá đang được phát triển">
                <div class="stat-icon is-sky"><i class="fa-solid fa-gift"></i></div>
                <div>
                    <div class="stat-num"><?php echo $so_ma_giam_gia; ?></div>
                    <div class="stat-label">Mã giảm giá</div>
                    <span class="stat-link is-sky">Xem mã của bạn</span>
                </div>
            </a>
        </div>

        <!-- Hàng 1: Thông tin tài khoản | Đơn hàng của tôi -->
        <div class="row-2col">
            <div class="contact-card form-card">
                <div class="card-title">
                    <i class="fa-regular fa-id-card"></i> Thông tin tài khoản
                </div>
                <p class="page-subtitle" style="margin:0 0 16px;">Quản lý thông tin cá nhân của bạn</p>

                <form class="contact-form" action="xuly-tai-khoan.php" method="POST" style="margin-bottom: 0;">
                    <input type="hidden" name="action" value="cap_nhat_thong_tin">
                    <div class="form-group">
                        <label for="update_name">Họ và tên</label>
                        <input type="text" name="customer_name" id="update_name"
                            value="<?php echo htmlspecialchars($khach_hang['customer_name']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="update_email">Email (Không thể thay đổi)</label>
                        <input type="email" value="<?php echo htmlspecialchars($khach_hang['customer_email']); ?>"
                            disabled>
                    </div>
                    <div class="form-group">
                        <label for="update_phone">Số điện thoại</label>
                        <input type="text" name="customer_phone" id="update_phone"
                            value="<?php echo htmlspecialchars($khach_hang['customer_phone']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="update_address">Địa chỉ</label>
                        <textarea name="customer_address" id="update_address" rows="3"
                            required><?php echo htmlspecialchars($khach_hang['customer_address']); ?></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Lưu thay đổi &nbsp;<i
                            class="fa-solid fa-floppy-disk"></i></button>
                </form>
            </div>

            <div class="contact-card form-card" id="don-hang-toi">
                <div class="card-title">
                    <i class="fa-solid fa-receipt"></i> Đơn hàng của tôi
                </div>
                <p class="page-subtitle" style="margin:0 0 16px;">Theo dõi và quản lý đơn hàng của bạn</p>

                <?php if (empty($don_hang_toi)): ?>
                <div class="empty-box" style="background:#eaf1fb; border:1px solid #cddcf2;">
                    <div class="empty-icon" style="background:#dce7f7; color:#1e3c72;"><i
                            class="fa-solid fa-cart-shopping"></i></div>
                    <p style="font-weight:600; margin:0 0 4px;">Bạn chưa có đơn hàng nào</p>
                    <p style="font-size:13px; color:#6b7280; margin:0 0 16px;">Khám phá sản phẩm và đặt hàng ngay để
                        trải nghiệm dịch vụ của chúng tôi!</p>
                    <a href="san-pham.php"
                        style="display:inline-block; border:1px solid #1e3c72; color:#1e3c72; padding:8px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">Mua
                        sắm ngay</a>
                </div>
                <?php else: ?>
                <div class="my-order-list">
                    <?php foreach ($don_hang_trang as $dh): ?>
                    <div class="my-order-item">
                        <div class="my-order-main">
                            <strong>Đơn #<?php echo (int) $dh['ma_don_hang']; ?></strong>
                            <span><?php echo (int) $dh['so_mat_hang']; ?> sản phẩm •
                                <?php echo date('d/m/Y H:i', strtotime($dh['ngay_dat'])); ?></span>
                        </div>
                        <div class="my-order-side">
                            <span
                                class="my-order-total"><?php echo number_format((int) $dh['tong_tien'], 0, ',', '.'); ?>₫</span>
                            <span class="my-order-status"
                                style="color:<?php echo $trang_thai_nhan[(int) $dh['trang_thai']][1]; ?>">
                                <?php echo htmlspecialchars($trang_thai_nhan[(int) $dh['trang_thai']][0]); ?>
                            </span>
                            <a href="don-hang-chi-tiet.php?id=<?php echo (int) $dh['ma_don_hang']; ?>"
                                class="my-order-view-link">Xem chi tiết <i class="fa-solid fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>

                <?php if ($tong_trang_dh > 1): ?>
                <div class="order-pagination">
                    <a class="page-nav <?php echo $trang_dh <= 1 ? 'disabled' : ''; ?>"
                        href="<?php echo xay_url_trang_dh(max(1, $trang_dh - 1)); ?>">
                        <i class="fa-solid fa-chevron-left"></i>
                    </a>
                    <?php for ($i = 1; $i <= $tong_trang_dh; $i++): ?>
                    <a class="page-num <?php echo $i === $trang_dh ? 'active' : ''; ?>"
                        href="<?php echo xay_url_trang_dh($i); ?>"><?php echo $i; ?></a>
                    <?php endfor; ?>
                    <a class="page-nav <?php echo $trang_dh >= $tong_trang_dh ? 'disabled' : ''; ?>"
                        href="<?php echo xay_url_trang_dh(min($tong_trang_dh, $trang_dh + 1)); ?>">
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>

        <!-- Hàng 2: Đổi mật khẩu | Bảo mật tài khoản -->
        <div class="row-2col">
            <div class="contact-card form-card">
                <div class="card-title">
                    <i class="fa-solid fa-lock"></i> Đổi mật khẩu
                </div>
                <p class="page-subtitle" style="margin:0 0 16px;">Bảo mật tài khoản của bạn</p>

                <form class="contact-form" action="xuly-tai-khoan.php" method="POST" style="margin-bottom: 20px;">
                    <input type="hidden" name="action" value="doi_mat_khau">
                    <div class="form-group">
                        <label for="old_password">Mật khẩu hiện tại</label>
                        <input type="password" name="mat_khau_cu" id="old_password" placeholder="Nhập mật khẩu hiện tại"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="new_password">Mật khẩu mới</label>
                        <input type="password" name="mat_khau_moi" id="new_password" placeholder="Nhập mật khẩu mới"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="new_password_confirm">Xác nhận mật khẩu mới</label>
                        <input type="password" name="mat_khau_moi_nhac_lai" id="new_password_confirm"
                            placeholder="Nhập lại mật khẩu mới">
                    </div>
                    <button type="submit" class="btn-submit" style="background-color: #4b5563;"><i
                            class="fa-solid fa-lock"></i> Cập nhật mật khẩu</button>
                </form>

                <form action="xuly-tai-khoan.php" method="POST">
                    <input type="hidden" name="action" value="dang_xuat">
                    <button type="submit" class="btn-submit btn-logout"
                        style="width:100%; background-color: #1e3c72; color: #ffd700;"><i class="fa-solid fa-right-from-bracket"></i>
                        Đăng xuất</button>
                </form>
            </div>

            <div class="contact-card form-card">
                <div class="card-title" style="color:#16a34a;">
                    <i class="fa-solid fa-shield-halved"></i> Bảo mật tài khoản
                </div>
                <p class="page-subtitle" style="margin:0 0 16px;">Giữ tài khoản của bạn luôn an toàn</p>

                <ul style="list-style:none; margin:0 0 16px; padding:0; display:flex; flex-direction:column; gap:14px;">
                    <li style="display:flex; gap:10px; align-items:flex-start;">
                        <i class="fa-solid fa-circle-check" style="color:#16a34a; margin-top:2px;"></i>
                        <div>
                            <strong style="font-size:14px; display:block;">Sử dụng mật khẩu mạnh</strong>
                            <span style="font-size:12px; color:#6b7280;">Kết hợp chữ hoa, chữ thường, số và ký tự đặc
                                biệt</span>
                        </div>
                    </li>
                    <li style="display:flex; gap:10px; align-items:flex-start;">
                        <i class="fa-solid fa-circle-check" style="color:#16a34a; margin-top:2px;"></i>
                        <div>
                            <strong style="font-size:14px; display:block;">Không chia sẻ thông tin đăng nhập</strong>
                            <span style="font-size:12px; color:#6b7280;">Không chia sẻ mật khẩu với người khác</span>
                        </div>
                    </li>
                    <li style="display:flex; gap:10px; align-items:flex-start;">
                        <i class="fa-solid fa-circle-check" style="color:#16a34a; margin-top:2px;"></i>
                        <div>
                            <strong style="font-size:14px; display:block;">Đăng xuất khi không sử dụng</strong>
                            <span style="font-size:12px; color:#6b7280;">Đăng xuất khỏi tài khoản trên thiết bị công
                                cộng</span>
                        </div>
                    </li>
                    <li style="display:flex; gap:10px; align-items:flex-start;">
                        <i class="fa-solid fa-circle-check" style="color:#16a34a; margin-top:2px;"></i>
                        <div>
                            <strong style="font-size:14px; display:block;">Cập nhật thông tin thường xuyên</strong>
                            <span style="font-size:12px; color:#6b7280;">Cập nhật thông tin cá nhân để bảo mật tài
                                khoản</span>
                        </div>
                    </li>
                </ul>
                <a href="#"
                    style="display:block; text-align:center; border:1px solid #16a34a; color:#16a34a; padding:9px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">Tìm
                    hiểu thêm về bảo mật</a>
            </div>
        </div>

        <!-- Hàng 3: Sản phẩm yêu thích | Lịch sử đánh giá -->
        <div class="row-2col">
            <div class="contact-card form-card" id="yeu-thich">
                <div class="card-title" style="color: #e11d48;">
                    <i class="fa-solid fa-heart"></i> Sản phẩm yêu thích
                </div>
                <p class="page-subtitle" style="margin:0 0 16px;">Các sản phẩm bạn đã yêu thích</p>

                <?php if (empty($san_pham_yeu_thich)): ?>
                <p class="page-subtitle" style="margin:0;">Bạn chưa lưu sản phẩm nào. <a href="san-pham.php">Khám phá
                        ngay</a></p>
                <?php else: ?>
                <div class="wishlist-list">
                    <?php foreach ($san_pham_yeu_thich as $i => $sp):
                                $sp_hinh = lay_anh_dau($sp['hinh_anh'] ?? '');
                                $sp_gia  = (int) ($sp['gia_ban'] ?? 0);
                            ?>
                    <div class="wishlist-row wishlist-extra-item" data-id="<?php echo (int) $sp['ma_san_pham']; ?>"
                        style="<?php echo $i >= 3 ? 'display:none;' : ''; ?>">
                        <a href="chi-tiet-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>"
                            style="display:flex; align-items:center; gap:12px; text-decoration:none; color:inherit; flex:1; min-width:0;">
                            <img src="<?php echo htmlspecialchars($sp_hinh); ?>"
                                alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>">
                            <div style="flex:1; min-width:0;">
                                <p
                                    style="font-size:14px; font-weight:600; margin:0 0 2px; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    <?php echo htmlspecialchars($sp['ten_san_pham']); ?></p>
                                <strong
                                    style="font-size:14px; color:#1e3c72;"><?php echo $sp_gia <= 0 ? 'Liên hệ' : number_format($sp_gia, 0, ',', '.') . '₫'; ?></strong>
                            </div>
                        </a>
                        <button type="button" class="btn-remove-wishlist"
                            data-id="<?php echo (int) $sp['ma_san_pham']; ?>"
                            style="background:none; border:none; color:#e11d48; font-size:16px; cursor:pointer; flex-shrink:0;"
                            title="Bỏ yêu thích">
                            <i class="fa-solid fa-heart"></i>
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (count($san_pham_yeu_thich) > 3): ?>
                <button type="button" id="btnToggleWishlist"
                    style="display:block; width:100%; text-align:center; border:1px solid #e5e7eb; background:#fff; color:#374151; padding:9px; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer; margin-top:6px;">
                    Xem tất cả sản phẩm yêu thích <i class="fa-solid fa-arrow-right"></i>
                </button>
                <?php endif; ?>
                <script>
                document.querySelectorAll('.btn-remove-wishlist').forEach(btn => {
                    btn.addEventListener('click', function(e) {
                        e.preventDefault();
                        if (!confirm('Xoá khỏi danh sách yêu thích?')) return;
                        const ma_sp = this.dataset.id;
                        const item = this.closest('.wishlist-row');
                        const formData = new FormData();
                        formData.append('action', 'remove');
                        formData.append('ma_san_pham', ma_sp);

                        fetch('yeu-thich-ajax.php', {
                                method: 'POST',
                                body: formData
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    item.remove();
                                    if (document.querySelectorAll('.wishlist-row').length === 0) {
                                        document.querySelector('.wishlist-list').innerHTML =
                                            '<p class="page-subtitle" style="margin:0;">Bạn chưa lưu sản phẩm nào.</p>';
                                    }
                                } else {
                                    alert(data.message);
                                }
                            });
                    });
                });
                const btnToggleWishlist = document.getElementById('btnToggleWishlist');
                if (btnToggleWishlist) {
                    let expanded = false;
                    btnToggleWishlist.addEventListener('click', function() {
                        expanded = !expanded;
                        document.querySelectorAll('.wishlist-extra-item').forEach(el => {
                            el.style.display = expanded ? 'flex' : (el === document.querySelectorAll(
                                '.wishlist-extra-item')[0] ? 'flex' : el.style.display);
                        });
                        document.querySelectorAll('.wishlist-extra-item').forEach((el, idx) => {
                            el.style.display = expanded || idx < 3 ? 'flex' : 'none';
                        });
                        this.innerHTML = expanded ?
                            'Thu gọn <i class="fa-solid fa-arrow-up"></i>' :
                            'Xem tất cả sản phẩm yêu thích <i class="fa-solid fa-arrow-right"></i>';
                    });
                }
                </script>
                <?php endif; ?>
            </div>

            <div class="contact-card form-card" id="danh-gia">
                <div class="card-title" style="color: #ca8a04;">
                    <i class="fa-solid fa-star"></i> Lịch sử đánh giá
                </div>
                <p class="page-subtitle" style="margin:0 0 16px;">Các đánh giá bạn đã viết</p>

                <?php if (empty($lich_su_danh_gia)): ?>
                <div class="empty-box" style="background:#fffbeb; border:1px solid #fde68a;">
                    <div class="empty-icon" style="background:#fef3c7; color:#ca8a04;"><i
                            class="fa-solid fa-comment-dots"></i></div>
                    <p style="font-weight:600; margin:0 0 4px;">Bạn chưa viết đánh giá nào</p>
                    <p style="font-size:13px; color:#6b7280; margin:0 0 16px;">Chia sẻ trải nghiệm của bạn với sản phẩm
                        đã mua</p>
                    <a href="san-pham.php"
                        style="display:inline-block; border:1px solid #ca8a04; color:#ca8a04; padding:8px 18px; border-radius:8px; font-size:13px; font-weight:600; text-decoration:none;">Viết
                        đánh giá ngay</a>
                </div>
                <?php else: ?>
                <div class="review-history-list">
                    <?php foreach ($lich_su_danh_gia as $dg): ?>
                    <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                            <a href="chi-tiet-san-pham.php?id=<?php echo (int) $dg['ma_san_pham']; ?>"
                                style="font-weight: 600; color: #2563eb; text-decoration: none; font-size: 15px;">
                                <?php echo htmlspecialchars($dg['ten_san_pham']); ?>
                            </a>
                            <span
                                style="color: #6b7280; font-size: 13px;"><?php echo date('d/m/Y', strtotime($dg['ngay_danh_gia'])); ?></span>
                        </div>
                        <div style="color: #fbbf24; margin-bottom: 8px; font-size: 13px;">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fa-<?php echo $i <= (int)$dg['so_sao'] ? 'solid' : 'regular'; ?> fa-star"></i>
                            <?php endfor; ?>
                        </div>
                        <p style="margin: 0; font-size: 14px; color: #374151; line-height: 1.5;">
                            <?php echo nl2br(htmlspecialchars($dg['noi_dung'])); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Hàng 4: Gửi yêu cầu hỗ trợ | Thông tin liên hệ + bản đồ -->
        <div class="row-2col">
            <div class="contact-card form-card">
                <div class="card-title" style="color: #0ea5e9;">
                    <i class="fa-solid fa-headset"></i> Gửi yêu cầu hỗ trợ
                </div>
                <p class="page-subtitle" style="margin:0 0 16px;">Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>

                <form class="contact-form" action="xuly-tai-khoan.php" method="POST" style="margin-bottom: 0;">
                    <input type="hidden" name="action" value="gui_ho_tro">
                    <div class="form-group">
                        <label for="support_subject">Chủ đề</label>
                        <select name="chu_de" id="support_subject"
                            style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit;">
                            <option value="Bảo hành sản phẩm">Bảo hành sản phẩm</option>
                            <option value="Đổi trả">Đổi trả</option>
                            <option value="Hỗ trợ kỹ thuật">Hỗ trợ kỹ thuật</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="support_content">Nội dung chi tiết</label>
                        <textarea name="noi_dung" id="support_content" rows="4"
                            placeholder="Mô tả chi tiết vấn đề của bạn..." required></textarea>
                    </div>
                    <button type="submit" class="btn-submit" style="background-color: #0ea5e9;"><i
                            class="fa-solid fa-paper-plane"></i> Gửi yêu cầu</button>
                </form>
            </div>

            <div class="contact-right-col" style="display:flex; flex-direction:column; gap:20px;">
                <div class="contact-card info-card">
                    <div class="card-title">
                        <i class="fa-regular fa-calendar-lines" style="color: #2563EB;"></i> Thông tin liên hệ
                    </div>
                    <p class="page-subtitle" style="margin:0 0 16px;">Chúng tôi luôn sẵn sàng hỗ trợ bạn</p>

                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="info-content">
                                <div class="info-label">Địa chỉ</div>
                                <div class="info-text">150Ter Bùi Thị Xuân, Phường Bến Thành,<br>Quận 1, TP.HCM</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-regular fa-envelope"></i></div>
                            <div class="info-content">
                                <div class="info-label">Email hỗ trợ</div>
                                <div class="info-text">support@vietsontdc.com</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                            <div class="info-content">
                                <div class="info-label">Hotline</div>
                                <div class="info-text">(028) 3929 3770<br>(028) 3929 3765<br>8:00 - 21:00 (T2 - CN)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-card map-card" style="padding:0; overflow:hidden;">
                    <iframe
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5496399842823!2d106.68491307573582!3d10.769150259326477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f55d6772055%3A0xc89a20fe4db883fa!2zQ8O0bmcgdHkgQ-G7lSBQaOG6p24gVGluIEjhu41jIFZp4bq_dCBTxqFu!5e0!3m2!1svi!2s!4v1772254314094!5m2!1svi!2s"
                        width="100%" height="230" style="border:0; display:block;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>

        <!-- Dải icon uy tín -->
        <div class="trust-badges">
            <div>
                <i class="fa-solid fa-shield-halved" style="color:#16a34a; font-size:20px;"></i>
                <p style="font-weight:600; font-size:13px; margin:8px 0 2px;">Sản phẩm chính hãng</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">100% chính hãng</p>
            </div>
            <div>
                <i class="fa-solid fa-rotate-left" style="color:#2563eb; font-size:20px;"></i>
                <p style="font-weight:600; font-size:13px; margin:8px 0 2px;">1 đổi 1 trong 30 ngày</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">Nếu sản phẩm lỗi</p>
            </div>
            <div>
                <i class="fa-solid fa-shield-halved" style="color:#ca8a04; font-size:20px;"></i>
                <p style="font-weight:600; font-size:13px; margin:8px 0 2px;">Bảo hành chính hãng</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">Từ 3 - 5 năm</p>
            </div>
            <div>
                <i class="fa-solid fa-truck-fast" style="color:#e11d48; font-size:20px;"></i>
                <p style="font-weight:600; font-size:13px; margin:8px 0 2px;">Giao hàng toàn quốc</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">Kiểm tra trước khi thanh toán</p>
            </div>
            <div>
                <i class="fa-solid fa-headset" style="color:#0ea5e9; font-size:20px;"></i>
                <p style="font-weight:600; font-size:13px; margin:8px 0 2px;">Hỗ trợ 24/7</p>
                <p style="font-size:11px; color:#6b7280; margin:0;">Tư vấn tận tâm</p>
            </div>
        </div>

        <?php else: ?>

        <div class="contact-grid">
            <div class="contact-card form-card">
                <div class="auth-tabs">
                    <button type="button"
                        class="auth-tab-btn <?php echo $tab_mac_dinh === 'dang-nhap' ? 'active' : ''; ?>"
                        data-tab="dang-nhap">Đăng nhập</button>
                    <button type="button"
                        class="auth-tab-btn <?php echo $tab_mac_dinh === 'dang-ky' ? 'active' : ''; ?>"
                        data-tab="dang-ky">Đăng ký</button>
                </div>

                <div class="auth-tab-panel <?php echo $tab_mac_dinh === 'dang-nhap' ? 'active' : ''; ?>"
                    id="panel-dang-nhap">
                    <form class="contact-form" action="xuly-tai-khoan.php" method="POST">
                        <input type="hidden" name="action" value="dang_nhap">
                        <div class="form-group">
                            <label for="login_email">Email</label>
                            <input type="email" name="customer_email" id="login_email" placeholder="Email đăng nhập"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="login_password">Mật khẩu</label>
                            <input type="password" name="mat_khau" id="login_password" placeholder="Mật khẩu" required>
                        </div>
                        <button type="submit" class="btn-submit">Đăng nhập &nbsp;<i
                                class="fa-solid fa-arrow-right-to-bracket"></i></button>
                    </form>
                </div>

                <div class="auth-tab-panel <?php echo $tab_mac_dinh === 'dang-ky' ? 'active' : ''; ?>"
                    id="panel-dang-ky">
                    <form class="contact-form" action="xuly-tai-khoan.php" method="POST">
                        <input type="hidden" name="action" value="dang_ky">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="customer_name">Họ và tên</label>
                                <input type="text" name="customer_name" id="customer_name" placeholder="Tên khách hàng"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="customer_email">Email</label>
                                <input type="email" name="customer_email" id="customer_email" placeholder="Email"
                                    required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="customer_phone">Số điện thoại</label>
                            <input type="text" name="customer_phone" id="customer_phone"
                                placeholder="Số điện thoại của bạn" required>
                        </div>
                        <div class="form-group">
                            <label for="customer_address">Địa chỉ</label>
                            <textarea name="customer_address" id="customer_address" rows="3"
                                placeholder="Địa chỉ nhận hàng" required></textarea>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="mat_khau">Mật khẩu</label>
                                <input type="password" name="mat_khau" id="mat_khau" placeholder="Ít nhất 6 ký tự"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="mat_khau_nhac_lai">Nhắc lại mật khẩu</label>
                                <input type="password" name="mat_khau_nhac_lai" id="mat_khau_nhac_lai"
                                    placeholder="Nhập lại mật khẩu" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-submit">Đăng ký &nbsp;<i
                                class="fa-regular fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>

            <div class="contact-right-col">
                <div class="contact-card info-card">
                    <div class="card-title">
                        <i class="fa-regular fa-calendar-lines" style="color: #2563EB;"></i> Thông tin liên hệ
                    </div>

                    <div class="info-list">
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-location-dot"></i></div>
                            <div class="info-content">
                                <div class="info-label">Địa chỉ</div>
                                <div class="info-text">150Ter Bùi Thị Xuân, Phường Bến Thành,<br>Quận 1, TP.HCM</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-regular fa-envelope"></i></div>
                            <div class="info-content">
                                <div class="info-label">Email hỗ trợ</div>
                                <div class="info-text">support@vietsontdc.com</div>
                            </div>
                        </div>
                        <div class="info-item">
                            <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                            <div class="info-content">
                                <div class="info-label">Hotline</div>
                                <div class="info-text">(028) 3929 3770<br>(028) 3929 3765</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="contact-card map-card">
                    <div class="map-container" style="padding: 0; background: transparent;">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5496399842823!2d106.68491307573582!3d10.769150259326477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f55d6772055%3A0xc89a20fe4db883fa!2zQ8O0bmcgdHkgQ-G7lSBQaOG6p24gVGluIEjhu41jIFZp4bq_dCBTxqFu!5e0!3m2!1svi!2s!4v1772254314094!5m2!1svi!2s"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>

        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>

    <script>
    document.querySelectorAll('.auth-tab-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.auth-tab-btn').forEach(function(b) {
                b.classList.remove('active');
            });
            document.querySelectorAll('.auth-tab-panel').forEach(function(p) {
                p.classList.remove('active');
            });
            btn.classList.add('active');
            document.getElementById('panel-' + btn.getAttribute('data-tab')).classList.add('active');
        });
    });
    </script>
</body>

</html>