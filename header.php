<?php
require_once __DIR__ . '/admin/config/config.php';
$da_dang_nhap_kh = isset($_SESSION['khach_hang_id']);
$ten_khach_hang_header = $_SESSION['khach_hang_ten'] ?? '';

// Các trang chính sách được admin bật "Hiển thị trên menu header", gom theo vị trí menu con.
$menu_chinh_sach = ['ve-cong-ty' => [], 'cong-dong' => [], 'mo-ta-san-pham' => []];
try {
    $stmt_menu_header = $pdo->query("SELECT policy_slug, policy_title, policy_menu_group FROM policy_page WHERE policy_status = 1 AND policy_show_menu = 1 ORDER BY policy_title ASC");
    foreach ($stmt_menu_header->fetchAll(PDO::FETCH_ASSOC) as $trang_menu) {
        if (isset($menu_chinh_sach[$trang_menu['policy_menu_group']])) {
            $menu_chinh_sach[$trang_menu['policy_menu_group']][] = $trang_menu;
        }
    }
} catch (PDOException $e) {
    // Chưa chạy migrate-menu-chinh-sach.php nên cột chưa tồn tại — bỏ qua, menu tĩnh vẫn hoạt động bình thường.
}
?>
<header class="site-header">
    <div class="container header-main-inner">
        <div class="site-logo">
            <a href="<?php echo htmlspecialchars(asset_url('index.php')); ?>" title="Trang chủ Viết Sơn">
                <img src="<?php echo htmlspecialchars(asset_url('assets/image/Logo ACVS/ACVS.png')); ?>" alt="">
            </a>
        </div>

        <button class="nav-toggle" type="button" aria-label="Mở menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav">
            <ul>

                <li class="has-megamenu">
                    <a href="<?php echo htmlspecialchars(asset_url('may-tinh-lap-san.php')); ?>">
                        Về công ty
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-content-group">
                                <div class="megamenu-cols">
                                    <div class="megamenu-col">
                                        <h4>Thông tin công ty</h4>
                                        <p class="megamenu-desc">Tìm hiểu về chúng tôi, các chính sách và cam kết nhằm mang đến
                                            sản phẩm chất lượng cùng dịch vụ khách hàng chuyên nghiệp..
                                        </p>
                                        <ul>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('ve-chung-toi.php')); ?>">Thông tin công ty</a></li>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('chinh-sach-bao-hanh.php')); ?>">Chính sách bảo hành </a></li>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('cong-dong.php' . '?trang=podcast')); ?>">Chính sách sản phẩm</a></li>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('cong-dong.php' . '?trang=gioi-thieu-ban-be')); ?>">Chính sách công ty </a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="megamenu-promo">
                                    <a href="<?php echo htmlspecialchars(asset_url('uu-dai.php' . '?bo-suu-tap=007')); ?>">
                                        <img src="<?php echo htmlspecialchars(asset_url('assets/image/pc.webp')); ?>" alt="Máy tính chơi game lắp sẵn">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Mega menu -->
               <li class="has-megamenu">
                    <a href="<?php echo htmlspecialchars(asset_url('may-tinh-lap-san.php')); ?>">
                        Sản phẩm
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-content-group">
                                <div class="megamenu-cols">
                                    <div class="megamenu-col">
                                        <h4>Sản phẩm</h4>
                                        <p class="megamenu-desc"> 
                                            Khám phá đa dạng sản phẩm công nghệ và linh kiện máy tính chất lượng, đáp ứng mọi nhu cầu từ học tập, làm việc, giải trí, chơi game đến sáng tạo nội dung. 
                                        </p>
                                        <ul>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('san-pham.php')); ?>">Sản phẩm</a></li>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('san-pham.php' . '?danh-muc=amd,intel')); ?>">CPU - Intel</a></li>
                                            <!--<li><a href="<?php echo htmlspecialchars(asset_url('san-pham.php' . '?thuong-hieu=rosa')); ?>">Máy bộ ROSA</a></li>-->
                                        </ul>
                                    </div>
                                </div>
                                <div class="megamenu-promo">
                                    <a href="<?php echo htmlspecialchars(asset_url('uu-dai.php' . '?bo-suu-tap=007')); ?>">
                                        <img src="<?php echo htmlspecialchars(asset_url('assets/image/san-pham.png')); ?>" alt="Máy tính chơi game lắp sẵn">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- Mega menu -->
                <!--<li class="has-megamenu">
                    <a href="<?php echo htmlspecialchars(asset_url('san-pham.php')); ?>">
                        Sản phẩm
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-content-group">
                                <div class="megamenu-cols">
                                    <div class="megamenu-col">
                                        <h4>Linh kiện chính</h4>
                                        <ul>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('san-pham.php' . '?danh-muc=amd,intel')); ?>">CPU - Intel</a></li>
                                           
                                        </ul>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </li>-->

                <!-- Mega menu -->
                <li class="has-megamenu">
                    <a href="<?php echo htmlspecialchars(asset_url('may-tinh-lap-san.php')); ?>">
                        Landing Page
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-content-group">
                                <div class="megamenu-cols">
                                    <div class="megamenu-col">
                                        <h4>Landing Page sản phẩm</h4>
                                        <p class="megamenu-desc">
                                            Khám phá các landing page giới thiệu sản phẩm, công nghệ và giải pháp
                                            từ những thương hiệu hàng đầu trong lĩnh vực phần cứng máy tính.
                                        </p>
                                        <ul>
                                            <li><a href="<?php echo htmlspecialchars(asset_url('landing-page/chuong-trinh-intel.php')); ?>">INTEL</a></li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="megamenu-promo">
                                    <a href="<?php echo htmlspecialchars(asset_url('uu-dai.php' . '?bo-suu-tap=007')); ?>">
                                        <img src="<?php echo htmlspecialchars(asset_url('assets/image/pc.webp')); ?>" alt="Máy tính chơi game lắp sẵn">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!--<li class="has-megamenu">
                    <a href="<?php echo htmlspecialchars(asset_url('khuyen-mai.php'));?>">
                        Khuyễn mãi
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>

                </li>-->
                <li><a href="<?php echo htmlspecialchars(asset_url('bao-hanh.php')); ?>">Bảo hành </a></li>


                <!-- Dropdown nhỏ -->
                <li class="has-submenu">
                    <a href="<?php echo htmlspecialchars(asset_url('cong-dong.php')); ?>">
                        Tin tức
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <ul class="submenu">
                        <li><a href="<?php echo htmlspecialchars(asset_url('tin-tuc-moi.php')); ?>">Tin tức</a></li>
                        <?php foreach ($menu_chinh_sach['cong-dong'] as $tr_menu): ?>
                            <li><a href="<?php echo htmlspecialchars(asset_url('chinh-sach.php?slug=' . urlencode($tr_menu['policy_slug']))); ?>"><?php echo htmlspecialchars($tr_menu['policy_title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>
            </ul>

            <a href="<?php echo htmlspecialchars(asset_url('tai-khoan.php')); ?>" class="main-nav-account" <?php echo $da_dang_nhap_kh ? '' : 'data-account-trigger="1"'; ?>>
                <i class="fa-solid fa-circle-user"></i> Tài khoản
            </a>
        </nav>

        <div class="header-icons">
            <button type="button" class="icon-btn search-toggle" aria-label="Tìm kiếm"><img width="30" height="30" src="https://img.icons8.com/external-tanah-basah-detailed-outline-tanah-basah/48/external-search-user-interface-tanah-basah-detailed-outline-tanah-basah.png" alt="external-search-user-interface-tanah-basah-detailed-outline-tanah-basah" /></button>
            <a href="<?php echo htmlspecialchars(asset_url('tai-khoan.php')); ?>" class="icon-btn" aria-label="Tài khoản" <?php echo $da_dang_nhap_kh ? '' : 'data-account-trigger="1"'; ?>>
                <?php if ($da_dang_nhap_kh): ?>
                    <span class="header-account-avatar"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($ten_khach_hang_header, 0, 1, 'UTF-8'), 'UTF-8')); ?></span>
                <?php else: ?>
                    <img width="30" height="30" src="https://img.icons8.com/ios/50/user-male-circle--v1.png" alt="user" />
                <?php endif; ?>
            </a>
            <a href="<?php echo htmlspecialchars(asset_url('gio-hang.php')); ?>" class="icon-btn cart-icon-btn" aria-label="Giỏ hàng">
                <img width="30" height="30" src="https://img.icons8.com/badges/48/add-shopping-cart.png" alt="shopping-cart-loaded" />
                <span class="cart-count-badge" id="cartCountBadge" style="display:none;">0</span>
            </a>
        </div>

        <form class="search-box" action="<?php echo htmlspecialchars(asset_url('tim-kiem.php')); ?>" method="get" autocomplete="off">
            <input type="text" name="q" id="headerSearchInput" placeholder="Tìm theo tên, mô tả hoặc mã sản phẩm..."
                aria-label="Tìm kiếm" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
            <button type="submit" aria-label="Tìm kiếm">
                <i class="fas fa-search"></i>
            </button>
            <div class="search-suggest" id="headerSearchSuggest"></div>
        </form>
    </div>

    <?php if (!$da_dang_nhap_kh): ?>
    <div class="account-modal-overlay" id="accountModalOverlay">
        <div class="account-modal" role="dialog" aria-modal="true" aria-labelledby="accountModalTitle">
            <button type="button" class="account-modal-close" id="accountModalClose" aria-label="Đóng">
                <i class="fa-solid fa-xmark"></i>
            </button>
            <h2 class="account-modal-title" id="accountModalTitle">Tài khoản</h2>
            <div class="account-modal-mascot">
                <i class="fa-solid fa-circle-user"></i>
            </div>
            <p class="account-modal-desc">
                Vui lòng đăng nhập tài khoản để xem ưu đãi và thanh toán dễ dàng hơn.
            </p>
            <div class="account-modal-actions">
                <a href="<?php echo htmlspecialchars(asset_url('tai-khoan.php?tab=dang-ky')); ?>" class="account-modal-btn account-modal-btn-outline">Đăng ký</a>
                <a href="<?php echo htmlspecialchars(asset_url('tai-khoan.php?tab=dang-nhap')); ?>" class="account-modal-btn account-modal-btn-solid">Đăng nhập</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</header>