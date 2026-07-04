<?php
    // $ADMIN_ROOT: đường dẫn tương đối tới thư mục admin/, khai báo trước khi include file này.
    // $active_page: 'dashboard' | 'tin-tuc'
    // $active_sub (khi $active_page = 'tin-tuc'): 'danh-sach' | 'them'
    if (!isset($ADMIN_ROOT)) $ADMIN_ROOT = '';
    if (!isset($active_page)) $active_page = '';
    if (!isset($active_sub)) $active_sub = '';
?>
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
            </div>
        </div>

        <a href="<?php echo $ADMIN_ROOT; ?>../san-pham.php" target="_blank" class="admin-nav-item">
            <img width="30" height="30" src="https://img.icons8.com/fluency/48/external-link.png" alt="external-link"/>Xem trang sản phẩm
        </a>
        <a href="<?php echo $ADMIN_ROOT; ?>../index.php" target="_blank" class="admin-nav-item">
           <img width="25" height="25" src="https://img.icons8.com/dusk/64/domain.png" alt="domain"/> Trang chủ website
        </a>
    </nav>

    <div class="admin-sidebar-bottom">
        <a href="<?php echo $ADMIN_ROOT; ?>dang-nhap.php" class="admin-nav-item">
            <img width="30" height="30" src="https://img.icons8.com/fluency/48/exit--v1.png" alt="exit--v1"/> Đăng xuất
        </a>
    </div>
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
</script>
