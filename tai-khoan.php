<?php
require_once 'admin/config/config.php';

$da_dang_nhap = isset($_SESSION['khach_hang_id']);
$khach_hang   = null;
$don_hang_toi = [];

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
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $da_dang_nhap ? 'Tài khoản của tôi' : 'Đăng nhập / Đăng ký'; ?> - Viết Sơn Achieva</title>
    <link rel="shortcut icon" href="assets/images/icon/logo VS_icon.jpg">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="assets/js/header.js"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="assets/css/header.css">
    <link rel="stylesheet" href="assets/css/footer.css">
    <link rel="stylesheet" href="assets/css/tai-khoan.css">
</head>

<body>
    <?php include 'header.php'; ?>

    <div class="main-content layout-contact">
        <div class="page-header contact-header">
            <?php if ($da_dang_nhap): ?>
                <h1 class="page-title">Xin chào, <?php echo htmlspecialchars($khach_hang['customer_name']); ?></h1>
                <p class="page-subtitle">Quản lý thông tin tài khoản và theo dõi đơn hàng của bạn tại đây.</p>
            <?php else: ?>
                <h1 class="page-title">Đăng nhập / Đăng ký tài khoản</h1>
                <p class="page-subtitle">
                    Đăng nhập vào tài khoản để mua hàng thuận tiện hơn, lưu lại lịch sử đơn hàng và nhận thông tin ưu đãi từ cửa hàng.
                </p>
            <?php endif; ?>
        </div>

        <?php if ($msg): ?>
            <div class="contact-success <?php echo $msg[0] === 'error' ? 'is-error' : ''; ?>">
                <i class="fa-solid <?php echo $msg[0] === 'error' ? 'fa-circle-exclamation' : 'fa-circle-check'; ?>"></i>
                <span><?php echo htmlspecialchars($msg[1]); ?></span>
            </div>
        <?php endif; ?>

        <div class="contact-grid">
            <!-- Left Column -->
            <?php if ($da_dang_nhap): ?>
                <div class="contact-card form-card">
                    <div class="card-title">
                        <i class="fa-regular fa-id-card"></i> Thông tin tài khoản
                    </div>
                    
                    <form class="contact-form" action="xuly-tai-khoan.php" method="POST" style="margin-bottom: 20px;">
                        <input type="hidden" name="action" value="cap_nhat_thong_tin">
                        <div class="form-group">
                            <label for="update_name">Họ và tên</label>
                            <input type="text" name="customer_name" id="update_name" value="<?php echo htmlspecialchars($khach_hang['customer_name']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_email">Email (Không thể thay đổi)</label>
                            <input type="email" value="<?php echo htmlspecialchars($khach_hang['customer_email']); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label for="update_phone">Số điện thoại</label>
                            <input type="text" name="customer_phone" id="update_phone" value="<?php echo htmlspecialchars($khach_hang['customer_phone']); ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="update_address">Địa chỉ</label>
                            <textarea name="customer_address" id="update_address" rows="3" required><?php echo htmlspecialchars($khach_hang['customer_address']); ?></textarea>
                        </div>
                        <button type="submit" class="btn-submit">Lưu thay đổi &nbsp;<i class="fa-solid fa-floppy-disk"></i></button>
                    </form>

                    <div class="card-title" style="margin-top: 30px;">
                        <i class="fa-solid fa-lock"></i> Đổi mật khẩu
                    </div>
                    <form class="contact-form" action="xuly-tai-khoan.php" method="POST" style="margin-bottom: 20px;">
                        <input type="hidden" name="action" value="doi_mat_khau">
                        <div class="form-group">
                            <label for="old_password">Mật khẩu hiện tại</label>
                            <input type="password" name="mat_khau_cu" id="old_password" required>
                        </div>
                        <div class="form-group">
                            <label for="new_password">Mật khẩu mới</label>
                            <input type="password" name="mat_khau_moi" id="new_password" required>
                        </div>
                        <button type="submit" class="btn-submit" style="background-color: #4b5563;">Cập nhật mật khẩu</button>
                    </form>

                    <form action="xuly-tai-khoan.php" method="POST">
                        <input type="hidden" name="action" value="dang_xuat">
                        <button type="submit" class="btn-submit btn-logout" style="width:100%; margin-top:20px; background-color: #dc2626;"><i class="fa-solid fa-right-from-bracket"></i> Đăng xuất</button>
                    </form>
                </div>

                <div class="contact-card form-card">
                    <div class="card-title">
                        <i class="fa-solid fa-receipt"></i> Đơn hàng của tôi
                    </div>
                    <?php if (empty($don_hang_toi)): ?>
                        <p class="page-subtitle" style="margin:0;">Bạn chưa có đơn hàng nào. <a href="san-pham.php">Mua sắm ngay</a></p>
                    <?php else: ?>
                        <div class="my-order-list">
                            <?php foreach ($don_hang_toi as $dh): ?>
                                <div class="my-order-item">
                                    <div class="my-order-main">
                                        <strong>Đơn #<?php echo (int) $dh['ma_don_hang']; ?></strong>
                                        <span><?php echo (int) $dh['so_mat_hang']; ?> sản phẩm • <?php echo date('d/m/Y H:i', strtotime($dh['ngay_dat'])); ?></span>
                                    </div>
                                    <div class="my-order-side">
                                        <span class="my-order-total"><?php echo number_format((int) $dh['tong_tien'], 0, ',', '.'); ?>₫</span>
                                        <span class="my-order-status" style="color:<?php echo $trang_thai_nhan[(int) $dh['trang_thai']][1]; ?>">
                                            <?php echo htmlspecialchars($trang_thai_nhan[(int) $dh['trang_thai']][0]); ?>
                                        </span>
                                        <a href="don-hang-chi-tiet.php?id=<?php echo (int) $dh['ma_don_hang']; ?>" class="my-order-view-link">Xem chi tiết <i class="fa-solid fa-chevron-right"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="contact-card form-card">
                    <div class="card-title" style="color: #e11d48;">
                        <i class="fa-solid fa-heart"></i> Sản phẩm yêu thích
                    </div>
                    <?php if (empty($san_pham_yeu_thich)): ?>
                        <p class="page-subtitle" style="margin:0;">Bạn chưa lưu sản phẩm nào. <a href="san-pham.php">Khám phá ngay</a></p>
                    <?php else: ?>
                        <div class="wishlist-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 15px; margin-top: 15px;">
                            <?php foreach ($san_pham_yeu_thich as $sp): ?>
                                <div class="wishlist-item" style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; text-align: center; position: relative;">
                                    <a href="chi-tiet-san-pham.php?id=<?php echo (int) $sp['ma_san_pham']; ?>" style="text-decoration: none; color: inherit;">
                                        <img src="<?php echo htmlspecialchars($sp['hinh_anh_dau'] ?? 'assets/images/placeholder.png'); ?>" alt="<?php echo htmlspecialchars($sp['ten_san_pham']); ?>" style="width: 100%; height: 120px; object-fit: contain; margin-bottom: 10px;">
                                        <h4 style="font-size: 14px; margin: 0 0 5px 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo htmlspecialchars($sp['ten_san_pham']); ?></h4>
                                        <strong style="color: #dc2626; font-size: 14px;"><?php echo number_format((int) $sp['gia_ban'], 0, ',', '.'); ?>₫</strong>
                                    </a>
                                    <button type="button" class="btn-remove-wishlist" data-id="<?php echo (int) $sp['ma_san_pham']; ?>" style="position: absolute; top: 5px; right: 5px; background: rgba(255,255,255,0.8); border: none; color: #6b7280; border-radius: 50%; width: 25px; height: 25px; cursor: pointer;" title="Xoá">
                                        <i class="fa-solid fa-xmark"></i>
                                    </button>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <script>
                        document.querySelectorAll('.btn-remove-wishlist').forEach(btn => {
                            btn.addEventListener('click', function() {
                                if(!confirm('Xoá khỏi danh sách yêu thích?')) return;
                                const ma_sp = this.dataset.id;
                                const item = this.closest('.wishlist-item');
                                const formData = new FormData();
                                formData.append('action', 'remove');
                                formData.append('ma_san_pham', ma_sp);
                                
                                fetch('yeu-thich-ajax.php', {
                                    method: 'POST',
                                    body: formData
                                })
                                .then(res => res.json())
                                .then(data => {
                                    if(data.success) {
                                        item.remove();
                                        // Optional: if empty, show empty message
                                        if(document.querySelectorAll('.wishlist-item').length === 0) {
                                            document.querySelector('.wishlist-grid').innerHTML = '<p class="page-subtitle" style="margin:0;">Bạn chưa lưu sản phẩm nào.</p>';
                                        }
                                    } else {
                                        alert(data.message);
                                    }
                                });
                            });
                        });
                        </script>
                    <?php endif; ?>
                </div>

                <div class="contact-card form-card">
                    <div class="card-title" style="color: #ca8a04;">
                        <i class="fa-solid fa-star"></i> Lịch sử đánh giá
                    </div>
                    <?php if (empty($lich_su_danh_gia)): ?>
                        <p class="page-subtitle" style="margin:0;">Bạn chưa viết đánh giá nào.</p>
                    <?php else: ?>
                        <div class="review-history-list" style="margin-top: 15px;">
                            <?php foreach ($lich_su_danh_gia as $dg): ?>
                                <div style="border: 1px solid #e5e7eb; border-radius: 8px; padding: 15px; margin-bottom: 15px;">
                                    <div style="display: flex; justify-content: space-between; margin-bottom: 8px;">
                                        <a href="chi-tiet-san-pham.php?id=<?php echo (int) $dg['ma_san_pham']; ?>" style="font-weight: 600; color: #2563eb; text-decoration: none; font-size: 15px;">
                                            <?php echo htmlspecialchars($dg['ten_san_pham']); ?>
                                        </a>
                                        <span style="color: #6b7280; font-size: 13px;"><?php echo date('d/m/Y', strtotime($dg['ngay_danh_gia'])); ?></span>
                                    </div>
                                    <div style="color: #fbbf24; margin-bottom: 8px; font-size: 13px;">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <i class="fa-<?php echo $i <= (int)$dg['so_sao'] ? 'solid' : 'regular'; ?> fa-star"></i>
                                        <?php endfor; ?>
                                    </div>
                                    <p style="margin: 0; font-size: 14px; color: #374151; line-height: 1.5;"><?php echo nl2br(htmlspecialchars($dg['noi_dung'])); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="contact-card form-card" style="margin-top: 20px;">
                    <div class="card-title" style="color: #0ea5e9;">
                        <i class="fa-solid fa-headset"></i> Gửi yêu cầu hỗ trợ
                    </div>
                    <form class="contact-form" action="xuly-tai-khoan.php" method="POST" style="margin-bottom: 0;">
                        <input type="hidden" name="action" value="gui_ho_tro">
                        <div class="form-group">
                            <label for="support_subject">Chủ đề</label>
                            <select name="chu_de" id="support_subject" style="width: 100%; padding: 10px; border: 1px solid #d1d5db; border-radius: 6px; font-family: inherit;">
                                <option value="Bảo hành sản phẩm">Bảo hành sản phẩm</option>
                                <option value="Đổi trả">Đổi trả</option>
                                <option value="Hỗ trợ kỹ thuật">Hỗ trợ kỹ thuật</option>
                                <option value="Khác">Khác</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="support_content">Nội dung chi tiết</label>
                            <textarea name="noi_dung" id="support_content" rows="4" placeholder="Mô tả chi tiết vấn đề của bạn..." required></textarea>
                        </div>
                        <button type="submit" class="btn-submit" style="background-color: #0ea5e9;"><i class="fa-solid fa-paper-plane"></i> Gửi yêu cầu</button>
                    </form>
                </div>
            <?php else: ?>
                <div class="contact-card form-card">
                    <div class="auth-tabs">
                        <button type="button" class="auth-tab-btn <?php echo $tab_mac_dinh === 'dang-nhap' ? 'active' : ''; ?>" data-tab="dang-nhap">Đăng nhập</button>
                        <button type="button" class="auth-tab-btn <?php echo $tab_mac_dinh === 'dang-ky' ? 'active' : ''; ?>" data-tab="dang-ky">Đăng ký</button>
                    </div>

                    <div class="auth-tab-panel <?php echo $tab_mac_dinh === 'dang-nhap' ? 'active' : ''; ?>" id="panel-dang-nhap">
                        <form class="contact-form" action="xuly-tai-khoan.php" method="POST">
                            <input type="hidden" name="action" value="dang_nhap">
                            <div class="form-group">
                                <label for="login_email">Email</label>
                                <input type="email" name="customer_email" id="login_email" placeholder="Email đăng nhập" required>
                            </div>
                            <div class="form-group">
                                <label for="login_password">Mật khẩu</label>
                                <input type="password" name="mat_khau" id="login_password" placeholder="Mật khẩu" required>
                            </div>
                            <button type="submit" class="btn-submit">Đăng nhập &nbsp;<i class="fa-solid fa-arrow-right-to-bracket"></i></button>
                        </form>
                    </div>

                    <div class="auth-tab-panel <?php echo $tab_mac_dinh === 'dang-ky' ? 'active' : ''; ?>" id="panel-dang-ky">
                        <form class="contact-form" action="xuly-tai-khoan.php" method="POST">
                            <input type="hidden" name="action" value="dang_ky">
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="customer_name">Họ và tên</label>
                                    <input type="text" name="customer_name" id="customer_name" placeholder="Tên khách hàng" required>
                                </div>
                                <div class="form-group">
                                    <label for="customer_email">Email</label>
                                    <input type="email" name="customer_email" id="customer_email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="customer_phone">Số điện thoại</label>
                                <input type="text" name="customer_phone" id="customer_phone" placeholder="Số điện thoại của bạn" required>
                            </div>
                            <div class="form-group">
                                <label for="customer_address">Địa chỉ</label>
                                <textarea name="customer_address" id="customer_address" rows="3" placeholder="Địa chỉ nhận hàng" required></textarea>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="mat_khau">Mật khẩu</label>
                                    <input type="password" name="mat_khau" id="mat_khau" placeholder="Ít nhất 6 ký tự" required>
                                </div>
                                <div class="form-group">
                                    <label for="mat_khau_nhac_lai">Nhắc lại mật khẩu</label>
                                    <input type="password" name="mat_khau_nhac_lai" id="mat_khau_nhac_lai" placeholder="Nhập lại mật khẩu" required>
                                </div>
                            </div>
                            <button type="submit" class="btn-submit">Đăng ký &nbsp;<i class="fa-regular fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Right Column -->
            <div class="contact-right-col">
                <!-- Contact Info -->
                <div class="contact-card info-card">
                    <div class="card-title">
                        <i class="fa-regular fa-calendar-lines" style="color: #2563EB;"></i> Thông tin liên hệ
                    </div>

                    <div class="info-list">
                        <!-- Dia chi -->
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Địa chỉ</div>
                                <div class="info-text">150Ter Bùi Thị Xuân, Phường Bến Thành,<br>Quận 1, TP.HCM</div>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Email hỗ trợ</div>
                                <div class="info-text">support@vietsontdc.com</div>
                            </div>
                        </div>

                        <!-- Hotline -->
                        <div class="info-item">
                            <div class="info-icon">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <div class="info-content">
                                <div class="info-label">Hotline</div>
                                <div class="info-text">(028) 3929 3770<br>(028) 3929 3765</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Map Card -->
                <div class="contact-card map-card">
                    <div class="map-container" style="padding: 0; background: transparent;">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5496399842823!2d106.68491307573582!3d10.769150259326477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f55d6772055%3A0xc89a20fe4db883fa!2zQ8O0bmcgdHkgQ-G7lSBQaOG6p24gVGluIEjhu41jIFZp4bq_dCBTxqFu!5e0!3m2!1svi!2s!4v1772254314094!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php include 'footer.php'; ?>

    <script>
        document.querySelectorAll('.auth-tab-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                document.querySelectorAll('.auth-tab-btn').forEach(function (b) { b.classList.remove('active'); });
                document.querySelectorAll('.auth-tab-panel').forEach(function (p) { p.classList.remove('active'); });
                btn.classList.add('active');
                document.getElementById('panel-' + btn.getAttribute('data-tab')).classList.add('active');
            });
        });
    </script>
</body>

</html>
