<?php
    // $ADMIN_ROOT: đường dẫn tương đối tới thư mục admin/, khai báo trước khi include file này.
    // $active_page: 'dashboard' | 'tin-tuc'
    // $active_sub (khi $active_page = 'tin-tuc'): 'danh-sach' | 'them'
    if (!isset($ADMIN_ROOT)) $ADMIN_ROOT = '';
    if (!isset($active_page)) $active_page = '';
    if (!isset($active_sub)) $active_sub = '';
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
        $ten_hien_thi = $_SESSION['account_name'] ?? $_SESSION['login'];
    ?>
        <div class="admin-account-wrap admin-topbar-account-wrap">
            <button type="button" class="admin-topbar-account admin-account-toggle">
                <span>Hi, <?php echo htmlspecialchars($ten_hien_thi); ?></span>
                <div class="admin-account-avatar">
                    <img src="https://img.icons8.com/bubbles/100/manager.png" alt="manager"/>
                </div>
                <i class="fa-solid fa-chevron-down admin-account-caret"></i>
            </button>
            <div class="admin-account-menu" style="display:none;">
                <a href="<?php echo $ADMIN_ROOT; ?>dang-nhap.php" class="admin-account-menu-item">
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
        <img src="<?php echo $ADMIN_ROOT; ?>../assets/images/icon/logo VS_icon.jpg" alt="Logo">
        <span>Viết Sơn Achieva</span>
    </div>

    <nav class="admin-nav">
        <a href="<?php echo $ADMIN_ROOT; ?>dashboad.php" class="admin-nav-item <?php echo $active_page === 'dashboard' ? 'active' : ''; ?>">
            <img width="30" height="30" src="https://img.icons8.com/fluency/48/dashboard-layout.png" alt="dashboard-layout"/> Dashboard
        </a>

        <div class="admin-nav-group">
            <button type="button" class="admin-nav-item admin-nav-toggle <?php echo $active_page === 'tin-tuc' ? 'active open' : ''; ?>">
               <img width="30" height="30" src="https://img.icons8.com/3d-fluency/94/blog.png" alt="blog"/> Tin tức
                <i class="fa-solid fa-chevron-down submenu-caret"></i>
            </button>
            <div class="admin-submenu" <?php echo $active_page === 'tin-tuc' ? '' : 'style="display:none;"'; ?>>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_baiviet/dah-sach-bai-viet.php" class="admin-subnav-item <?php echo $active_sub === 'danh-sach' ? 'active' : ''; ?>">Danh sách bài viết</a>
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
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_thuoctinh/danh-sach.php?type=danh-muc" class="admin-subnav-item <?php echo $active_sub === 'danh-muc' ? 'active' : ''; ?>">Danh mục</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_thuoctinh/danh-sach.php?type=thuong-hieu" class="admin-subnav-item <?php echo $active_sub === 'thuong-hieu' ? 'active' : ''; ?>">Thương hiệu</a>
                <a href="<?php echo $ADMIN_ROOT; ?>quanly_thuoctinh/danh-sach.php?type=dung-luong" class="admin-subnav-item <?php echo $active_sub === 'dung-luong' ? 'active' : ''; ?>">Dung lượng</a>
            </div>
        </div>

       <a href="<?php echo $ADMIN_ROOT; ?>../" target="_blank" class="admin-nav-item">
            <img width="25" height="25" src="https://img.icons8.com/dusk/64/guarantee--v1.png" alt="guarantee--v1"/>Bảo hành
        </a>

        <a href="<?php echo $ADMIN_ROOT; ?>../san-pham.php" target="_blank" class="admin-nav-item">
            <img width="30" height="30" src="https://img.icons8.com/fluency/48/external-link.png" alt="external-link"/>Xem trang sản phẩm
        </a>
        
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
