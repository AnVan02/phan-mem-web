<header class="site-header">
    <div class="container header-main-inner">
        <div class="site-logo">
            <a href="index.php" title="Trang chủ Viết Sơn">
                <img src="assets/image/logoVS_new.png" alt="">
            </a>
        </div>

        <button class="nav-toggle" type="button" aria-label="Mở menu" aria-expanded="false">
            <span></span><span></span><span></span>
        </button>

        <nav class="main-nav">
            <ul>
                <li><a href="/uu-dai.php">Ưu đãi</a></li>

                <!-- Mega menu -->
                <li class="has-megamenu">
                    <a href="/may-tinh-lap-san.php">
                        Máy tính lắp sẵn
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-col">
                                <h4>Máy tính chơi game lắp sẵn</h4>
                                <p class="megamenu-desc">Màn hình máy tính được tối ưu hóa để chơi game mượt mà ở độ phân giải 1080p, 1440p hoặc 4K.</p>
                                <ul>
                                    <li><a href="san-pham.php">Sản phẩm</a></li>
                                    <li><a href="/may-tinh-lap-san.php?loai=phan-khuc-2">Người chơi thứ hai</a></li>
                                    <li><a href="/may-tinh-lap-san.php?loai=phan-khuc-3">Người chơi thứ ba</a></li>
                                    <li><a href="/may-tinh-lap-san.php?so-sanh=1">So sánh các máy tính chơi game lắp sẵn</a></li>
                                </ul>
                            </div>
                            <div class="megamenu-col megamenu-promo">
                                <a href="/uu-dai.php?bo-suu-tap=007">
                                    <img src="assets/image/pc.webp" alt="Máy tính chơi game lắp sẵn">
                                    <span class="megamenu-promo-caption">Máy tính <em>lắp sẵn</em></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <li><a href="/dang-ky-pc.php">Đăng ký PC</a></li>

                <!-- Mega menu -->
                <li class="has-megamenu">
                    <a href="san-pham.php">
                        Linh kiện máy tính
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <div class="megamenu">
                        <div class="megamenu-inner">
                            <div class="megamenu-col">
                                <h4>Linh kiện chính</h4>
                                <ul>
                                    <li><a href="/linh-kien-may-tinh.php?loai=cpu">CPU</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=mainboard">Mainboard</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=vga">VGA - Card màn hình</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=ram">RAM</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=ssq">SSD</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=manhinh">Màn hình</a></li>

                                </ul>
                            </div>
                            <div class="megamenu-col">
                                <h4>Lưu trữ &amp; Tản nhiệt</h4>
                                <ul>
                                    <li><a href="/linh-kien-may-tinh.php?loai=o-cung">Ổ cứng SSD/HDD</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=nguon">Nguồn máy tính</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=tan-nhiet">Tản nhiệt</a></li>
                                    <li><a href="/linh-kien-may-tinh.php?loai=vo-case">Vỏ case</a></li>
                                    
                                </ul>
                            </div>
                            <div class="megamenu-col megamenu-promo">   
                                <a href="/linh-kien-may-tinh.php?uu-dai=combo">
                                    <img src="assets/image/promo-combo.jpg" alt="Combo linh kiện">
                                    <span class="megamenu-promo-caption">Combo <em>CPU + Main + RAM</em></span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>

                <!-- Dropdown nhỏ -->
                <li class="has-submenu">
                    <a href="/cong-dong.php">
                        Cộng đồng
                        <span class="submenu-arrow" aria-hidden="true"></span>
                    </a>
                    <ul class="submenu">
                        <li><a href="/cong-dong.php?trang=cau-lac-bo">Câu lạc bộ Viết Sơn</a></li>
                        <li><a href="tin-tuc-moi.php">Blog</a></li>
                        <li><a href="/cong-dong.php?trang=podcast">Podcast</a></li>
                        <li><a href="/cong-dong.php?trang=gioi-thieu-ban-be">Giới thiệu bạn bè</a></li>
                    </ul>
                </li>

                <li><a href="/ung-ho.php">Ủng hộ</a></li>
            </ul>
        </nav>

        <div class="header-icons">
            <a href="tai-khoan.php" class="icon-btn" aria-label="Tài khoản"><img width="30" height="30" src="https://img.icons8.com/office/40/user.png" alt="user"/></a>
            <button type="button" class="icon-btn search-toggle" aria-label="Tìm kiếm"><img width="30" height="30" src="https://img.icons8.com/arcade/64/search.png" alt="search"/></button>
            <a href="/gio-hang.php" class="icon-btn" aria-label="Giỏ hàng"><img width="30" height="30" src="https://img.icons8.com/plasticine/100/shopping-cart-loaded.png" alt="shopping-cart-loaded"/></a>
        </div>

        <form class="search-box" action="/tim-kiem.php" method="get">
            <input type="text" name="q" placeholder="Tìm kiếm" aria-label="Tìm kiếm">
            <button type="submit" aria-label="Tìm kiếm">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>
</header>

