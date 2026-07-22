<?php
require_once 'admin/config/config.php';

try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS `policy_page` (
        `policy_id` int(11) NOT NULL AUTO_INCREMENT,
        `policy_slug` varchar(190) NOT NULL,
        `policy_title` varchar(255) NOT NULL,
        `policy_subtitle` varchar(500) NOT NULL DEFAULT '',
        `policy_content` longtext NOT NULL,
        `policy_image` varchar(255) NOT NULL DEFAULT '',
        `policy_status` tinyint(1) NOT NULL DEFAULT 1,
        `policy_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
        PRIMARY KEY (`policy_id`),
        UNIQUE KEY `uniq_policy_slug` (`policy_slug`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;");
    echo "Tạo bảng policy_page thành công!\n";
} catch (PDOException $e) {
    die("Lỗi tạo bảng: " . $e->getMessage() . "\n");
}

$bao_hanh_title    = 'Chính sách bảo hành & đổi trả';
$bao_hanh_subtitle = 'Việt Sơn Achieva cam kết mang đến trải nghiệm mua sắm an tâm và tin cậy cho khách hàng ở mọi sản phẩm linh kiện, thiết bị.';
$bao_hanh_content  = <<<'HTML'
<section class="policy-block">
    <h2 class="policy-block-title"><span class="policy-number">01</span> Quy định bảo hành</h2>
    <div class="policy-block-flex">
        <ul class="policy-list policy-list-check">
            <li><i class="fa-solid fa-circle-check"></i> Sản phẩm được bảo hành theo đúng thời hạn và điều
                kiện của nhà sản xuất.</li>
            <li><i class="fa-solid fa-circle-check"></i> Thời gian bảo hành được tính từ ngày mua hàng ghi
                trên hóa đơn hoặc phiếu xuất kho.</li>
            <li><i class="fa-solid fa-circle-check"></i> Áp dụng cho các lỗi kỹ thuật phát sinh do nhà sản
                xuất trong quá trình sử dụng bình thường.</li>
            <li><i class="fa-solid fa-circle-check"></i> Không bảo hành các trường hợp hư hỏng do sử dụng sai
                cách, rơi vỡ, va đập, vào nước hoặc tự ý sửa chữa.</li>
            <li><i class="fa-solid fa-circle-check"></i> Vui lòng xuất trình hóa đơn hoặc phiếu bảo hành khi
                có yêu cầu bảo hành.</li>
        </ul>
        <div class="policy-block-illustration"><i class="fa-solid fa-shield-halved"></i></div>
    </div>
</section>

<section class="policy-block">
    <h2 class="policy-block-title"><span class="policy-number">02</span> Quy định đổi trả</h2>

    <div class="policy-return-grid">
        <div class="policy-return-col is-success">
            <p class="policy-return-col-title">Điều kiện đổi trả</p>
            <ul class="policy-list policy-list-check">
                <li><i class="fa-solid fa-circle-check"></i> Sản phẩm còn nguyên vẹn, chưa qua sử dụng.</li>
                <li><i class="fa-solid fa-circle-check"></i> Còn đầy đủ phụ kiện, tem mác, hóa đơn mua
                    hàng.</li>
                <li><i class="fa-solid fa-circle-check"></i> Đổi trả trong vòng 7 ngày kể từ ngày nhận
                    hàng.</li>
                <li><i class="fa-solid fa-circle-check"></i> Sản phẩm bị lỗi kỹ thuật do nhà sản xuất hoặc
                    giao nhầm hàng.</li>
            </ul>
        </div>

        <div class="policy-return-col is-danger">
            <p class="policy-return-col-title">Trường hợp không được đổi trả</p>
            <ul class="policy-list policy-list-cross">
                <li><i class="fa-solid fa-circle-xmark"></i> Sản phẩm đã qua sử dụng hoặc bị hư hỏng do người
                    dùng.</li>
                <li><i class="fa-solid fa-circle-xmark"></i> Không còn hóa đơn hoặc phiếu bảo hành.</li>
                <li><i class="fa-solid fa-circle-xmark"></i> Tem mác, phụ kiện bị thiếu hoặc rách.</li>
                <li><i class="fa-solid fa-circle-xmark"></i> Sản phẩm nằm trong danh mục không hỗ trợ đổi trả
                    (nếu có thông báo).</li>
            </ul>
        </div>

        <div class="policy-return-col is-process">
            <p class="policy-return-col-title">Quy trình đổi trả</p>
            <div class="policy-process-steps">
                <div class="policy-process-step">
                    <div class="policy-process-icon"><i class="fa-solid fa-headset"></i></div>
                    <div class="policy-process-text">
                        <strong>1. Liên hệ</strong>
                        <span>Liên hệ bộ phận CSKH để thông báo tình trạng</span>
                    </div>
                </div>
                <div class="policy-process-arrow"><i class="fa-solid fa-arrow-down"></i></div>
                <div class="policy-process-step">
                    <div class="policy-process-icon"><i class="fa-solid fa-box"></i></div>
                    <div class="policy-process-text">
                        <strong>2. Gửi sản phẩm</strong>
                        <span>Đóng gói và gửi lại sản phẩm theo hướng dẫn</span>
                    </div>
                </div>
                <div class="policy-process-arrow"><i class="fa-solid fa-arrow-down"></i></div>
                <div class="policy-process-step">
                    <div class="policy-process-icon"><i class="fa-solid fa-magnifying-glass"></i></div>
                    <div class="policy-process-text">
                        <strong>3. Kiểm tra</strong>
                        <span>Shop kiểm tra tình trạng và xác nhận yêu cầu</span>
                    </div>
                </div>
                <div class="policy-process-arrow"><i class="fa-solid fa-arrow-down"></i></div>
                <div class="policy-process-step">
                    <div class="policy-process-icon"><i class="fa-solid fa-rotate"></i></div>
                    <div class="policy-process-text">
                        <strong>4. Hoàn tiền / Đổi hàng</strong>
                        <span>Hoàn tiền hoặc gửi sản phẩm mới theo yêu cầu</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="policy-note-bar">
        <i class="fa-solid fa-circle-info"></i>
        <span><strong>Lưu ý:</strong> Phí vận chuyển đổi trả sẽ được hỗ trợ tùy theo từng trường hợp cụ
            thể.</span>
    </div>
</section>

<section class="policy-block">
    <h2 class="policy-block-title"><span class="policy-number">03</span> Các chính sách liên quan</h2>
    <div class="policy-related-grid">
        <a class="policy-related-card" href="chinh-sach.php?slug=chinh-sach-van-chuyen">
            <div class="policy-related-icon"><i class="fa-solid fa-truck"></i></div>
            <strong>Chính sách vận chuyển</strong>
            <span>Giao hàng nhanh chóng, an toàn toàn quốc.</span>
            <span class="policy-related-link">Xem chi tiết →</span>
        </a>
        <a class="policy-related-card" href="chinh-sach.php?slug=chinh-sach-thanh-toan">
            <div class="policy-related-icon"><i class="fa-solid fa-credit-card"></i></div>
            <strong>Chính sách thanh toán</strong>
            <span>Đa dạng hình thức thanh toán tiện lợi.</span>
            <span class="policy-related-link">Xem chi tiết →</span>
        </a>
        <a class="policy-related-card" href="chinh-sach.php?slug=chinh-sach-bao-mat">
            <div class="policy-related-icon"><i class="fa-solid fa-lock"></i></div>
            <strong>Chính sách bảo mật</strong>
            <span>Cam kết bảo mật thông tin tuyệt đối cho khách hàng.</span>
            <span class="policy-related-link">Xem chi tiết →</span>
        </a>
        <a class="policy-related-card" href="chinh-sach.php?slug=huong-dan-mua-hang">
            <div class="policy-related-icon"><i class="fa-solid fa-cart-shopping"></i></div>
            <strong>Hướng dẫn mua hàng</strong>
            <span>Hướng dẫn đặt hàng và mua hàng dễ dàng.</span>
            <span class="policy-related-link">Xem chi tiết →</span>
        </a>
    </div>
</section>

<section class="policy-block">
    <h2 class="policy-block-title"><span class="policy-number">04</span> Quy định riêng dành cho các hãng</h2>
    <div class="policy-brand-grid">
        <div class="policy-brand-card">
            <div class="policy-brand-head">
                <img class="policy-brand-logo" src="https://img.icons8.com/external-tal-revivo-color-tal-revivo/80/external-intel-corporation-an-american-multinational-corporation-and-technology-company-logo-color-tal-revivo.png" alt="Intel">
                <span class="policy-brand-badge">CPU / NUC</span>
            </div>
            <ul>
                <li><i class="fa-solid fa-circle-check"></i> Dưới 30 ngày: Đổi mới 1:1 hoặc nhập lại nếu lỗi
                    NSX (chờ quyết định trong 7 ngày).</li>
                <li><i class="fa-solid fa-circle-check"></i> Trên 30 ngày (Đổi/bảo hành): Đăng ký bảo hành
                    trực tiếp theo quy định của Intel tại
                    <a href="https://supporttickets.intel.com" target="_blank" rel="noopener">supporttickets.intel.com</a>.
                </li>
                <li><i class="fa-solid fa-circle-check"></i> Ngoài thời hạn: Nhận lại tiền hoặc đổi sản phẩm
                    theo quy định riêng của Việt Sơn.</li>
            </ul>
        </div>

        <div class="policy-brand-card">
            <div class="policy-brand-head">
                <img class="policy-brand-logo" src="https://img.icons8.com/nolan/80/asus--v2.png" alt="Intel">
                <span class="policy-brand-badge">Mainboard / Server</span>
            </div>
            <ul>
                <li><i class="fa-solid fa-circle-check"></i> Dưới 180 ngày: Đổi mới nếu đủ điều kiện (lỗi do
                    nhà sản xuất) trong 7 ngày làm việc.</li>
                <li><i class="fa-solid fa-circle-check"></i> Trên 180 ngày: Thay thế hoặc sửa chữa theo tiêu
                    chuẩn bảo hành của ASUS.</li>
                <li><i class="fa-solid fa-circle-check"></i> Server: Áp dụng tiêu chuẩn bảo hành đặc thù của
                    ASUS Server.</li>
            </ul>
        </div>

        <div class="policy-brand-card">
            <div class="policy-brand-head">
                <img class="policy-brand-logo" src="https://img.icons8.com/external-tal-revivo-color-tal-revivo/80/external-intel-corporation-an-american-multinational-corporation-and-technology-company-logo-color-tal-revivo.png" alt="Intel">
                <span class="policy-brand-badge">RAM / SSD</span>
            </div>
            <ul>
                <li><i class="fa-solid fa-circle-check"></i> Sản phẩm được đổi mới không quá 7 ngày làm việc
                    nếu lỗi từ nhà sản xuất.</li>
                <li><i class="fa-solid fa-circle-check"></i> Không áp dụng bảo hành với sản phẩm lỗi do người
                    dùng.</li>
                <li><i class="fa-solid fa-circle-check"></i> Một số sản phẩm có chính sách riêng, vui lòng
                    liên hệ CSKH.</li>
            </ul>
        </div>

        <div class="policy-brand-card">
            <div class="policy-brand-head">
                <img class="policy-brand-logo" src="https://img.icons8.com/nolan/80/asus--v2.png" alt="Intel">
                <span class="policy-brand-badge">Mainboard / VGA</span>
            </div>
            <ul>
                <li><i class="fa-solid fa-circle-check"></i> Sản phẩm bảo hành theo tiêu chuẩn của
                    ASRock.</li>
                <li><i class="fa-solid fa-circle-check"></i> Đổi mới trong 7 ngày nếu đủ điều kiện.</li>
                <li><i class="fa-solid fa-circle-check"></i> Sau 7 ngày: bảo hành / sửa chữa theo quy
                    định.</li>
            </ul>
        </div>
    </div>
</section>

<section class="policy-block">
    <h2 class="policy-block-title"><span class="policy-number">05</span> Dịch vụ sửa chữa ngoài bảo hành</h2>
    <div class="policy-service-grid">
        <div class="policy-service-card">
            <div class="policy-service-icon"><i class="fa-regular fa-clock"></i></div>
            <strong>Thời gian xử lý</strong>
            <span>Từ 04 - 14 ngày làm việc (không tính thời gian vận chuyển)</span>
        </div>
        <div class="policy-service-card">
            <div class="policy-service-icon"><i class="fa-solid fa-file-invoice-dollar"></i></div>
            <strong>Báo giá minh bạch</strong>
            <span>Thông báo chi phí đầy đủ trước khi tiến hành sửa chữa</span>
        </div>
        <div class="policy-service-card">
            <div class="policy-service-icon"><i class="fa-solid fa-circle-check"></i></div>
            <strong>Cam kết chất lượng</strong>
            <span>Bảo hành 30 ngày cho các hạng mục đã sửa chữa</span>
        </div>
        <div class="policy-service-card">
            <div class="policy-service-icon"><i class="fa-solid fa-headset"></i></div>
            <strong>Hỗ trợ tận tâm</strong>
            <span>Tư vấn và hỗ trợ kỹ thuật trước và sau sửa chữa</span>
        </div>
    </div>
</section>

<section class="policy-support-box">
    <div>
        <p class="policy-support-title">Cần hỗ trợ thêm?</p>
        <p class="policy-support-sub">Đội ngũ CSKH của Việt Sơn Achieva luôn sẵn sàng hỗ trợ bạn mọi lúc.</p>
    </div>
    <div class="policy-support-contacts">
        <div>
            <strong>Hotline</strong>
            <span>0936 699 336</span>
            <small>8:00 - 17:30, Thứ 2 - Thứ 7</small>
        </div>
        <div>
            <strong>Email</strong>
            <span>support@vietsontdc.com</span>
            <small>Phản hồi trong 24h</small>
        </div>
    </div>
    <a href="bao-hanh.php" class="policy-cta-btn"><i class="fa-solid fa-headset"></i> Liên hệ ngay</a>
</section>
HTML;

$ve_chung_toi_content = <<<'HTML'
<section class="about-story">
    <h2 class="about-story-title">CÂU CHUYỆN CỦA <span>VIẾT SƠN</span></h2>
    <div class="about-story-underline"></div>
    <p class="about-story-text">
        Chào mừng bạn đến với <strong>Công ty Cổ Phần Tin Học Viết Sơn</strong>. Một trong những đơn vị có bề
        dày kinh nghiệm trong lĩnh vực cung cấp linh kiện công nghệ thông tin tại Việt Nam. Bắt đầu từ một
        doanh nghiệp có quy mô rất nhỏ với chưa đến 10 nhân viên, Viết Sơn từng bước phát triển ổn định và
        bền bỉ trong suốt hành trình gần 40 năm hình thành và phát triển kể từ khi được thành lập vào năm
        1990.
    </p>

    <div class="about-stats">
        <div class="about-stat-card">
            <div class="about-stat-icon"><i class="fa-solid fa-award"></i></div>
            <div class="about-stat-number">40+</div>
            <div class="about-stat-label">NĂM KINH NGHIỆM</div>
        </div>
        <div class="about-stat-card">
            <div class="about-stat-icon"><i class="fa-solid fa-users"></i></div>
            <div class="about-stat-number">50+</div>
            <div class="about-stat-label">NHÂN VIÊN</div>
        </div>
        <div class="about-stat-card">
            <div class="about-stat-icon"><i class="fa-solid fa-chart-line"></i></div>
            <div class="about-stat-number">1000+ TỶ</div>
            <div class="about-stat-label">DOANH SỐ HÀNG NĂM</div>
        </div>
        <div class="about-stat-card">
            <div class="about-stat-icon"><i class="fa-solid fa-box"></i></div>
            <div class="about-stat-number">2000+</div>
            <div class="about-stat-label">MÃ SẢN PHẨM</div>
        </div>
    </div>

    <div class="about-quote">
        <i class="fa-solid fa-quote-left about-quote-icon"></i>
        <p class="about-quote-text">Viết Sơn đã đạt được tốc độ tăng trưởng hàng năm ấn tượng, từng phân phối
            hàng ngàn mã sản phẩm và sở hữu hệ thống đại lý bán lẻ trải khắp dài đất hình chữ S. Chúng tôi tự
            hào là một trong những nhà phân phối CNTT hàng đầu tại Việt Nam với doanh thu xấp xỉ 50 triệu đô
            la Mỹ mỗi năm.</p>
    </div>
</section>

<section class="about-mission">
    <div class="about-mission-visual">
        <div class="about-mission-ring">
            <div class="about-mission-core"><i class="fa-solid fa-bullseye"></i></div>
        </div>
        <div class="about-mission-badge badge-top"><i class="fa-solid fa-shield-halved"></i></div>
        <div class="about-mission-badge badge-bottom"><i class="fa-solid fa-handshake"></i></div>
    </div>
    <div class="about-mission-content">
        <h2 class="about-mission-title">TÔN CHỈ <span>HOẠT ĐỘNG</span></h2>
        <div class="about-mission-underline"></div>
        <p class="about-mission-quote">"Lấy chữ TÍN làm nền tảng cho mọi nền tảng kinh doanh."</p>
        <p class="about-mission-text">Chúng tôi cam kết luôn thực hiện các hợp đồng kinh tế một cách hoàn
            hảo, đảm bảo trung thực, công khai và minh bạch. Viết Sơn không chỉ cung cấp sản phẩm, mà còn đem
            đến sự tiện lợi và hài lòng tối đa cho mọi đối tác và khách hàng thông qua chính sách bảo hành,
            hậu mãi vượt trội.</p>
        <p class="about-mission-text">Sự tin tưởng của Quý khách chính là tài sản quý giá nhất, thúc đẩy
            chúng tôi không ngừng hoàn thiện và phát triển hơn nữa trong tương lai.</p>
    </div>
</section>
HTML;

$seed = [
    [
        'slug'     => 'bao-hanh',
        'title'    => $bao_hanh_title,
        'subtitle' => $bao_hanh_subtitle,
        'content'  => $bao_hanh_content,
    ],
    [
        'slug'     => 've-chung-toi',
        'title'    => 'Về chúng tôi',
        'subtitle' => 'Hành trình gần 40 năm kiến tạo giá trị và khẳng định vị thế dẫn đầu trong ngành Công nghệ Thông tin tại Việt Nam.',
        'content'  => $ve_chung_toi_content,
    ],
];

$kiem_tra = $pdo->prepare("SELECT policy_id FROM policy_page WHERE policy_slug = :slug");
$them = $pdo->prepare("INSERT INTO policy_page (policy_slug, policy_title, policy_subtitle, policy_content, policy_status)
    VALUES (:slug, :title, :subtitle, :content, 1)");

foreach ($seed as $s) {
    $kiem_tra->execute([':slug' => $s['slug']]);
    if ($kiem_tra->fetch()) {
        echo "Bỏ qua (đã có sẵn): {$s['slug']}\n";
        continue;
    }
    $them->execute([
        ':slug'     => $s['slug'],
        ':title'    => $s['title'],
        ':subtitle' => $s['subtitle'],
        ':content'  => $s['content'],
    ]);
    echo "Đã thêm dữ liệu mặc định cho: {$s['slug']}\n";
}

echo "Hoàn tất.\n";
