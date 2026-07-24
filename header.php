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
            <a href="index.php" title="Trang chủ Viết Sơn">
                <img src="assets/image/Logo ACVS/ACVS.png" alt="">
            </a>
        </div>

        <button class="nav-toggle" type="button" aria-label="Mở menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav">
            <ul>
                    <!-- Dropdown nhỏ -->
                <li class="has-submenu">
                    <a href="cong-dong.php">
                        Về công ty
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <ul class="submenu">
                        <li><a href="ve-chung-toi.php">Thông tin công ty</a></li>
                        <li><a href="chinh-sach-bao-hanh.php">Chính sách bảo hành </a></li>
                        <li><a href="cong-dong.php?trang=podcast">Chính sách sản phẩm</a></li>
                        <li><a href="cong-dong.php?trang=gioi-thieu-ban-be">Chính sách công ty </a></li>
                        <?php foreach ($menu_chinh_sach['ve-cong-ty'] as $tr_menu): ?>
                            <li><a href="chinh-sach.php?slug=<?php echo urlencode($tr_menu['policy_slug']); ?>"><?php echo htmlspecialchars($tr_menu['policy_title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>

                <!-- Mega menu -->
                <li class="has-megamenu">
                    <a href="may-tinh-lap-san.php">
                        Máy tính lắp sẵn
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-content-group">
                                <div class="megamenu-cols">
                                    <div class="megamenu-col">
                                        <h4>Máy tính chơi game lắp sẵn</h4>
                                        <p class="megamenu-desc">Màn hình máy tính được tối ưu hóa để chơi game mượt mà ở độ phân giải 1080p, 1440p hoặc 4K.</p>
                                        <ul>
                                            <li><a href="san-pham.php">Sản phẩm</a></li>
                                            <li><a href="san-pham.php?loai=rosa">Máy bộ PC</a></li>
                                            <li><a href="san-pham.php?loai=amd">Người chơi thứ ba</a></li>
                                            <li><a href="may-tinh-lap-san.php?so-sanh=1">So sánh các máy tính chơi game lắp sẵn</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="megamenu-promo">
                                    <a href="uu-dai.php?bo-suu-tap=007">
                                        <img src="assets/image/pc.webp" alt="Máy tính chơi game lắp sẵn">
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                <!-- Mega menu -->
                <li class="has-megamenu">
                    <a href="san-pham.php">
                        Linh kiện máy tính
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-content-group">
                                <div class="megamenu-cols">
                                    <div class="megamenu-col">
                                        <h4>Linh kiện chính</h4>
                                        <ul>
                                            <li><a href="san-pham.php?danh_muc=1">CPU</a></li>
                                            <li><a href="san-pham.php?loai=mainboard">Mainboard</a></li>
                                            <li><a href="san-pham.php?loai=vga">VGA - Card màn hình</a></li>
                                            <li><a href="san-pham.php?loai=ram">RAM</a></li>
                                            <li><a href="san-pham.php?loai=ssd">SSD</a></li>
                                            <li><a href="san-pham.php?loai=manhinh">Màn hình</a></li>
                                            <li><a href="san-pham.php?loai=asus">ASUS</a></li>
                                        </ul>
                                    </div>
                                    <div class="megamenu-col">
                                        <h4>Lưu trữ &amp; Tản nhiệt</h4>
                                        <ul>
                                            <li><a href="san-pham.php?loai=hdd">Ổ cứng SSD/HDD</a></li>
                                            <li><a href="san-pham.php?loai=nguon">Nguồn máy tính</a></li>
                                            <li><a href="san-pham.php?loai=tan-nhiet">Tản nhiệt</a></li>
                                            <li><a href="san-pham.php?loai=vo-case">Vỏ case</a></li>
                                            <li><a href="san-pham.php?loai=agi">Agi</a></li>
                                            <li><a href="san-pham.php?loai=gskill">Gskill</a></li>
                                        </ul>
                                    </div>
                                    <div class="megamenu-col">
                                        <h4>Màn hình</h4>
                                        <ul>
                                            <li><a href="san-pham.php?loai=aoc">AOC</a></li>
                                            <li><a href="san-pham.php?loai=benq">Benq</a></li>
                                            <li><a href="san-pham.php?loai=unv">UNV</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <li><a href="bao-hanh.php">Bảo hành </a></li>
                </li>

                <!-- Dropdown nhỏ -->
                <li class="has-submenu">
                    <a href="cong-dong.php">
                        Cộng đồng
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <ul class="submenu">
                        <li><a href="ve-chung-toi.php">Về công ty </a></li>
                        <li><a href="tin-tuc-moi.php">Tin tức</a></li>
                        <li><a href="cong-dong.php?trang=podcast">Mô tả sản phầm</a></li>
                        <li><a href="cong-dong.php?trang=gioi-thieu-ban-be">Giới thiệu bạn bè</a></li>
                        <?php foreach ($menu_chinh_sach['cong-dong'] as $tr_menu): ?>
                            <li><a href="chinh-sach.php?slug=<?php echo urlencode($tr_menu['policy_slug']); ?>"><?php echo htmlspecialchars($tr_menu['policy_title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>


                <!-- Dropdown nhỏ -->
                <li class="has-submenu">
                    <a href="cong-dong.php">
                        Mô tả sản phẩm
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <ul class="submenu">
                        <li><a href="landing-page/chương-trinh-intel.php">INTEL</a></li>
                        <li><a href="landing-page/nen-tang-ai-local.php">KINGSTON</a></li>
                        <li><a href="cong-dong.php?trang=podcast">PALIT</a></li>
                        <li><a href="cong-dong.php?trang=gioi-thieu-ban-be">Giới thiệu bạn bè</a></li>
                        
                        <?php foreach ($menu_chinh_sach['mo-ta-san-pham'] as $tr_menu): ?>
                            <li><a href="chinh-sach.php?slug=<?php echo urlencode($tr_menu['policy_slug']); ?>"><?php echo htmlspecialchars($tr_menu['policy_title']); ?></a></li>
                        <?php endforeach; ?>
                    </ul>
                </li>


            <a href="tai-khoan.php" class="main-nav-account">
                <i class="fa-solid fa-circle-user"></i> Tài khoản
            </a>
        </nav>

        <div class="header-icons">
            <button type="button" class="icon-btn search-toggle" aria-label="Tìm kiếm"><img width="30" height="30" src="https://img.icons8.com/external-tanah-basah-detailed-outline-tanah-basah/48/external-search-user-interface-tanah-basah-detailed-outline-tanah-basah.png" alt="external-search-user-interface-tanah-basah-detailed-outline-tanah-basah"/></button>
            <a href="tai-khoan.php" class="icon-btn" aria-label="Tài khoản">
                <?php if ($da_dang_nhap_kh): ?>
                    <span class="header-account-avatar"><?php echo htmlspecialchars(mb_strtoupper(mb_substr($ten_khach_hang_header, 0, 1, 'UTF-8'), 'UTF-8')); ?></span>
                <?php else: ?>
                    <img width="30" height="30" src="https://img.icons8.com/ios/50/user-male-circle--v1.png" alt="user"/>
                <?php endif; ?>
            </a>
            <a href="gio-hang.php" class="icon-btn cart-icon-btn" aria-label="Giỏ hàng">
                <img width="30" height="30" src="https://img.icons8.com/badges/48/add-shopping-cart.png" alt="shopping-cart-loaded"/>
                <span class="cart-count-badge" id="cartCountBadge" style="display:none;">0</span>
            </a>
        </div>

        <form class="search-box" action="tim-kiem.php" method="get" autocomplete="off">
            <input type="text" name="q" id="headerSearchInput" placeholder="Tìm theo tên, mô tả hoặc mã sản phẩm..."
                aria-label="Tìm kiếm" value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>">
            <button type="submit" aria-label="Tìm kiếm">
                <i class="fas fa-search"></i>
            </button>
            <div class="search-suggest" id="headerSearchSuggest"></div>
        </form>
    </div>
</header>

