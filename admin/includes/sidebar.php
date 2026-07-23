<?php
    // $ADMIN_ROOT: đường dẫn tương đối tới thư mục admin/, khai báo trước khi include file này.
    // $active_page: 'dashboard' | 'tin-tuc' | 'san-pham' | 'don-hang' | 'banner' | 'tai-khoan'
    // $active_sub (khi $active_page = 'tin-tuc' hoặc 'san-pham'): 'danh-sach' | 'them' | 'danh-muc'
    if (!isset($ADMIN_ROOT)) $ADMIN_ROOT = '';
    if (!isset($active_page)) $active_page = '';
    if (!isset($active_sub)) $active_sub = '';

    $vai_tro_hien_tai = (int) ($_SESSION['account_type'] ?? -1);
    $duoc_xem_noi_dung = in_array($vai_tro_hien_tai, [VAI_TRO_QUAN_TRI, VAI_TRO_NOI_DUNG], true);
    $duoc_xem_don_hang = in_array($vai_tro_hien_tai, [VAI_TRO_QUAN_TRI, VAI_TRO_DON_HANG], true);
    $la_quan_tri       = $vai_tro_hien_tai === VAI_TRO_QUAN_TRI;

    $so_ho_tro_chua_xu_ly = 0;
    if ($duoc_xem_don_hang && isset($pdo)) {
        try {
            $so_ho_tro_chua_xu_ly = (int) $pdo->query("SELECT COUNT(*) FROM ho_tro_khach_hang WHERE trang_thai = 0")->fetchColumn();
        } catch (PDOException $e) {
            $so_ho_tro_chua_xu_ly = 0;
        }
    }
?>
<div class="admin-topbar">
    <div class="admin-topbar-left">
        <button type="button" class="admin-topbar-toggle" id="sidebarToggle" aria-label="Ẩn/hiện menu">
            <i class="fa-solid fa-bars"></i>
        </button>
        <span class="admin-topbar-logo">Viết Sơn Achieva</span>
    </div>

    <div class="admin-topbar-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Tìm kiếm...">
    </div>

    <?php if (!empty($_SESSION['account_name']) || !empty($_SESSION['login'])):
        $ten_hien_thi  = $_SESSION['account_name'] ?? $_SESSION['login'];
        $ten_vai_tro   = $DS_VAI_TRO[$vai_tro_hien_tai] ?? '';
    ?>
        <div class="admin-account-wrap admin-topbar-account-wrap">
            <button type="button" class="admin-topbar-account admin-account-toggle">
                <span>Hi, <?php echo htmlspecialchars($ten_hien_thi); ?><?php echo $ten_vai_tro !== '' ? ' <small>(' . htmlspecialchars($ten_vai_tro) . ')</small>' : ''; ?></span>
                <div class="admin-account-avatar">
                    <img src="https://img.icons8.com/bubbles/100/manager.png" alt="manager"/>
                </div>
                <i class="fa-solid fa-chevron-down admin-account-caret"></i>
            </button>
            <div class="admin-account-menu" style="display:none;">
                <?php if ($la_quan_tri): ?>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_taikhoan/tai-khoan.php" class="admin-account-menu-item">
                    <i class="fa-solid fa-users-gear"></i> Hệ thống tài khoản
                </a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_nhatky/nhat-ky.php" class="admin-account-menu-item">
                    <i class="fa-solid fa-clock-rotate-left"></i> Nhật ký hoạt động
                </a>
                <?php endif; ?>
                <a href="<?php echo $ADMIN_ROOT; ?>dang-xuat.php" class="admin-account-menu-item">
                    <i class="fa-solid fa-right-from-bracket"></i> Đăng xuất
                </a>
            </div>
        </div>
    <?php else: ?>
        <a href="<?php echo $ADMIN_ROOT; ?>dang-nhap.php" class="admin-topbar-account">
            <span>Đăng nhập</span>
        </a>
    <?php endif; ?>
</div>
<aside class="admin-sidebar">
    <div class="admin-sidebar-logo">
        <img src="<?php echo $ADMIN_ROOT; ?>../assets/image/Logo ACVS/ACVS.png" alt="Logo">
        <!-- <span>Viết Sơn Achieva</span> -->
    </div>

    <nav class="admin-nav">
        <a href="<?php echo $ADMIN_ROOT; ?>dashboad.php" class="admin-nav-item <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>">
            <img width="30" height="30" src="https://img.icons8.com/fluency/48/dashboard-layout.png" alt="dashboard-layout"/> Dashboard
        </a>

        <?php if ($duoc_xem_noi_dung): ?>
        <div class="admin-nav-group">
            <button type="button" class="admin-nav-item admin-nav-toggle <?php echo $active_page === 'tin-tuc' ? 'active open' : ''; ?>">
               <img width="30" height="30" src="https://img.icons8.com/3d-fluency/94/blog.png" alt="blog"/> Tin tức
                <i class="fa-solid fa-chevron-down submenu-caret"></i>
            </button>
            <div class="admin-submenu" <?php echo $active_page === 'tin-tuc' ? '' : 'style="display:none;"'; ?>>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_baiviet/danh-sach-bai-viet.php" class="admin-subnav-item <?php echo $active_sub === 'danh-sach' ? 'active' : ''; ?>">Danh sách bài viết</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_baiviet/them.php" class="admin-subnav-item <?php echo $active_sub === 'them' ? 'active' : ''; ?>">Thêm bài viết mới</a>
            </div>
        </div>

        <div class="admin-nav-group">
            <button type="button" class="admin-nav-item admin-nav-toggle <?php echo $active_page === 'san-pham' ? 'active open' : ''; ?>">
               <img width="35" height="35" src="https://img.icons8.com/isometric/50/product.png" alt="product"/> Sản phẩm
                <i class="fa-solid fa-chevron-down submenu-caret"></i>
            </button>
            <div class="admin-submenu" <?php echo $active_page === 'san-pham' ? '' : 'style="display:none;"'; ?>>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_sanpham/danh-sach-san-pham.php" class="admin-subnav-item <?php echo $active_sub === 'danh-sach' ? 'active' : ''; ?>">Danh sách sản phẩm</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_sanpham/them-san-pham.php" class="admin-subnav-item <?php echo $active_sub === 'them' ? 'active' : ''; ?>">Thêm sản phẩm mới</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_thuoctinh/danh-sach.php?type=danh-muc" class="admin-subnav-item <?php echo $active_sub === 'danh-muc' ? 'active' : ''; ?>">Thêm linh kiện</a>
            </div>
        </div>

        <a href="<?php echo $ADMIN_ROOT; ?>quanly/banner.php" class="admin-nav-item <?php echo $active_page === 'banner' ? 'active' : ''; ?>">
            <img width="25" height="25" src="https://img.icons8.com/fluency/48/old-shop.png" alt="guarantee--v1"/>Banner Sản Phẩm
        </a>

        <div class="admin-nav-group">
            <button type="button" class="admin-nav-item admin-nav-toggle <?php echo $active_page === 'chinh-sach' ? 'active open' : ''; ?>">
               <img width="30" height="30" src="https://img.icons8.com/fluency/48/rules.png" alt="rules"/> Chính sách
                <i class="fa-solid fa-chevron-down submenu-caret"></i>
            </button>
            <div class="admin-submenu" <?php echo $active_page === 'chinh-sach' ? '' : 'style="display:none;"'; ?>>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_chinhsach/danh-sach.php" class="admin-subnav-item <?php echo $active_sub === 'danh-sach' ? 'active' : ''; ?>">Danh sách trang</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_chinhsach/them.php" class="admin-subnav-item <?php echo $active_sub === 'them' ? 'active' : ''; ?>">Thêm trang mới</a>
            </div>
        </div>
        <?php endif; ?>

        <?php if ($duoc_xem_don_hang): ?>
        <div class="admin-nav-group">
            <button type="button" class="admin-nav-item admin-nav-toggle <?php echo $active_page === 'khach-hang' ? 'active open' : ''; ?>">
                <img width="30" height="30" src="https://img.icons8.com/external-flaticons-lineal-color-flat-icons/64/external-clients-media-agency-flaticons-lineal-color-flat-icons.png" alt="blog"/>Khách hàng
                <i class="fa-solid fa-chevron-down submenu-caret"></i>
            </button>
            <div class="admin-submenu" <?php echo $active_page === 'khach-hang' ? '': 'style="display:none;"';?>>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly/don-hang.php" class="admin-subnav-item <?php echo $active_page === 'don-hang' ? 'active' : '' ;?>">Đơn hàng</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_taikhoan/danh-sach.php" class="admin-subnav-item <?php echo $active_page === 'khach-hang' && $active_sub === 'danh-sach' ? 'active':'';?>">Danh sách khách hàng</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_taikhoan/ho-tro.php" class="admin-subnav-item <?php echo $active_page === 'khach-hang' && $active_sub === 'ho-tro' ? 'active':'';?>">
                    Yêu cầu hỗ trợ
                    <?php if ($so_ho_tro_chua_xu_ly > 0): ?>
                        <span style="background:#dc2626; color:#fff; font-size:11px; font-weight:700; border-radius:999px; padding:1px 7px; margin-left:6px;"><?php echo $so_ho_tro_chua_xu_ly; ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>

        <?php if ($la_quan_tri): ?>
         <div class="admin-nav-group">
            <button type="button" class="admin-nav-item admin-nav-toggle <?php echo $active_page === 'tai-khoan' ? 'active open' : ''; ?>">
                <img width="30" height="30" src="https://img.icons8.com/stickers/100/admin-settings-male.png" alt="blog"/>Admin
                <i class="fa-solid fa-chevron-down submenu-caret"></i>
            </button>
            <div class="admin-submenu" <?php echo $active_page === 'tai-khoan' ? '': 'style="display:none;"';?>>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_taikhoan/tai-khoan.php" class="admin-subnav-item <?php echo $active_page === 'tai-khoan' ? 'active':'';?>">Danh sách admin</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_nhatky/danh-gia-san-pham.php" class="admin-subnav-item <?php echo $active_page = 'danh-gia-san-pham' ? 'active':'';?>">Đánh giá sản phẩm </a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_nhatky/nhat-ky.php" class="admin-subnav-item">Nhật ký hoạt động</a>
            </div>
        </div>
        <?php endif; ?>


        <a href="<?php echo $ADMIN_ROOT; ?>quanly_baohanh/bao-hanh.php" target="_blank" class="admin-nav-item">
            <img width="25" height="25" src="https://img.icons8.com/dusk/64/guarantee--v1.png" alt="guarantee--v1"/>Bảo hành
        </a>
        <?php endif; ?>

        <?php if ($la_quan_tri): ?>
   
   
        <?php endif; ?> 
        
        <a href="<?php echo $ADMIN_ROOT; ?>../index.php" target="_blank" class="admin-nav-item">
           <img width="25" height="25" src="https://img.icons8.com/dusk/64/domain.png" alt="domain"/> Trang chủ website
        </a>
    </nav>

</aside>

<script>
    document.querySelectorAll('.admin-nav-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var submenu = btn.nextElementSibling;
            var dangMo = submenu.style.display !== 'none';
            submenu.style.display = dangMo ? 'none' : 'block';
            btn.classList.toggle('open', !dangMo);
        });
    });

    var accountToggle = document.querySelector('.admin-account-toggle');
    if (accountToggle) {
        var accountWrap = accountToggle.closest('.admin-account-wrap');
        var accountMenu = accountWrap.querySelector('.admin-account-menu');
        accountToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var dangMo = accountMenu.style.display !== 'none';
            accountMenu.style.display = dangMo ? 'none' : 'flex';
            accountWrap.classList.toggle('open', !dangMo);
        });
        document.addEventListener('click', function (e) {
            if (!accountWrap.contains(e.target)) {
                accountMenu.style.display = 'none';
                accountWrap.classList.remove('open');
            }
        });
    }

    var sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function () {
            document.querySelector('.admin-shell').classList.toggle('sidebar-collapsed');
        });
    }
</script>
