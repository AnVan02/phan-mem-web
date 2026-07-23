<?php
// ==== SEO cho trang này ====
$GLOBALS['page_specific_title']       = 'ROSA AI Workspace - Trợ lý AI nội bộ cho đội ngũ doanh nghiệp';
$GLOBALS['page_specific_description'] = 'ROSA AI Workspace: trợ lý AI hiểu tài liệu công ty, trả lời có nguồn, tự động chăm sóc khách hàng trên Zalo/Facebook 24/7, ghi biên bản họp và giao việc tự động. Dữ liệu lưu trong mạng nội bộ, an toàn tuyệt đối.';
$GLOBALS['page_specific_keywords']    = 'ROSA AI Workspace, trợ lý AI nội bộ, chatbot doanh nghiệp, AI tra cứu tài liệu, chăm sóc khách hàng tự động Zalo, biên bản họp tự động, AI local doanh nghiệp Việt Nam';
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($GLOBALS['page_specific_title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($GLOBALS['page_specific_description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($GLOBALS['page_specific_keywords']); ?>">
    <link rel="canonical" href="https://rosacomputer.vn/rosa-ai-platform/rosa-ai-workspace.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($GLOBALS['page_specific_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($GLOBALS['page_specific_description']); ?>">
    <meta property="og:image" content="https://rosacomputer.vn/images/rosa.png">
    <meta property="og:url" content="https://rosacomputer.vn/rosa-ai-platform/rosa-ai-workspace.php">
    <meta property="og:locale" content="vi_VN">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" href="images/rosa-icon.png" type="image/png">
    <?php
    $emsCssVersion = filemtime(__DIR__ . '/rosa-ai-workspace.css');
    ?>
    <link rel="stylesheet" href="rosa-ai-workspace.css?v=<?php echo $emsCssVersion; ?>">
</head>
<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <img src="images/rosa.png" alt="ROSA Logo" class="logo-icon">
            </div>
            <nav class="nav-links">
                <a href="nen-tang-ai-local.php">GIẢI PHÁP</a>
                <a href="ROSA-AI-CONNECT.php">AI CONNECT</a>
                <a href="ROSA-AI-WORKSPACE.php">AI WORKSPACE</a>
                <a href="#" class="btn btn-primary nav-cta">Liên hệ tư vấn</a>
            </nav>
            <a href="#" class="btn btn-primary nav-cta-desktop">Liên hệ tư vấn <i class="ph ph-arrow-right"></i></a>
            <button class="nav-toggle" aria-label="Mở menu" aria-expanded="false">
                <i class="ph ph-list"></i>
            </button>
        </div>
    </header>

    <main>
        <!-- 1. Hero -->
        <section class="kh-hero container">
            <div class="kh-hero-left">
                <div class="hero-pill">
                    <i class="ph-fill ph-star"></i> ROSA AI Workspace
                </div>
                <h1 class="kh-hero-title">Trợ lý AI cho<br>cả đội ngũ của bạn<br><span class="grad-text">— ngay tại công ty.</span></h1>
                <p class="kh-hero-desc">
                    ROSA AI Workspace gánh phần việc lặp đi lặp lại: trả lời khách 24/7 trên Zalo, Telegram, Facebook
                    Messenger; giúp nhân viên tra cứu bảng giá, chính sách, khuyến mãi trong vài giây; ghi biên bản và
                    giao việc sau mỗi cuộc họp. Chạy trên AI của riêng công ty bạn — dữ liệu an toàn, không tốn phí
                    theo từng tin nhắn.
                </p>

                <div class="hero-buttons">
                    <a href="#" class="btn btn-primary js-open-modal">Đặt lịch demo miễn phí <i class="ph ph-arrow-right"></i></a>
                </div>

                <ul class="kh-trust">
                    <li><i class="ph-fill ph-check-circle"></i> AI hiểu tiếng Việt</li>
                    <li><i class="ph-fill ph-check-circle"></i> Mỗi câu trả lời đều kiểm chứng được nguồn</li>
                    <li><i class="ph-fill ph-check-circle"></i> Dữ liệu trong mạng nội bộ</li>
                </ul>
            </div>
            <div class="kh-hero-right">
                <div class="hero-diagram">
                    <img src="images/landing5.png" alt="Sơ đồ ROSA AI Workspace mang trợ lý AI nội bộ ra Zalo, Telegram, Facebook Messenger và các quy trình tự động n8n">
                </div>
                <div class="hero-features">
                    <div class="hf-item">
                        <i class="ph-fill ph-rocket-launch"></i>
                        <span>Hiểu tài liệu<br>Kiểm chứng nguồn</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph-fill ph-shield-check"></i>
                        <span>Hỏi excel<br>đúng số</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph ph-infinity"></i>
                        <span>Họp -> Biên bản -> Giao việc</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph-fill ph-lightning"></i>
                        <span>Phân quyền bảo mật</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Problem -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left;">
                    <div class="eyebrow">VẤN ĐỀ CỦA BẠN </div>
                    <h2 class="section-title">Đội ngũ của bạn đang mất quá nhiều thời gian vào những việc lặp lại.</h2>
                </div>

                <div class="problem-showcase">
                    <div class="problem-row">
                        <div class="problem-media">
                            <div class="problem-media-inner">
                                <img width="100" height="100" src="images/image1.png" alt="Tra cứu thông tin mất thời gian"/>
                            </div>
                        </div>
                        <div class="problem-content">
                            <div class="problem-number">01</div>
                            <h4 class="problem-title">Tra thông tin mất thời gian</h4>
                            <p>Nhân viên muốn tra chính sách, mức chiết khấu hoặc chương trình khuyến mãi phải lục file hoặc hỏi quản lý rồi ngồi chờ.</p>
                        </div>
                    </div>

                    <div class="problem-row reverse">
                        <div class="problem-media">
                            <div class="problem-media-inner">
                                <img width="150" height="150" src="images/image1.png" alt="Nhân viên mới onboarding chậm"/>
                            </div>
                        </div>
                        <div class="problem-content">
                            <div class="problem-number">02</div>
                            <h4 class="problem-title">Nhân viên mới onboarding chậm</h4>
                            <p>Nhân viên mới mất hàng tuần để nắm bảng giá, quy trình và chính sách của công ty.</p>
                        </div>
                    </div>

                    <div class="problem-row">
                        <div class="problem-media">
                            <div class="problem-media-inner">
                                <img width="100" height="100" src="images/image1.png" alt="Trả lời khách lặp đi lặp lại"/>
                            </div>
                        </div>
                        <div class="problem-content">
                            <div class="problem-number">03</div>
                            <h4 class="problem-title">Trả lời khách lặp đi lặp lại</h4>
                            <p>Cùng một câu hỏi của khách, nhân viên phải trả lời hàng trăm lần mỗi ngày.</p>
                        </div>
                    </div>

                    <div class="problem-row reverse">
                        <div class="problem-media">
                            <div class="problem-media-inner">
                                <img width="96" height="96" src="https://img.icons8.com/badges/48/business-time.png" alt="Mất cơ hội ngoài giờ làm việc"/>
                            </div>
                        </div>
                        <div class="problem-content">
                            <div class="problem-number">04</div>
                            <h4 class="problem-title">Mất cơ hội ngoài giờ làm việc</h4>
                            <p>Khách nhắn tin ngoài giờ không được phản hồi kịp thời, đồng thời các đầu việc sau cuộc họp cũng dễ bị bỏ quên và không được theo dõi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- 3. Solution -->
        <section class="section-padding">
            <div class="container">
                <div class="solution-card">
                    <div class="text-center" style="text-align: left; margin-bottom: 32px;">
                        <div class="eyebrow">GIẢI PHÁP</div>
                        <h2 class="section-title" style="margin-bottom: 0;">ROSA AI Workspace giúp bạn</h2>
                    </div>
                    <div class="solution-flex">
                        <div class="solution-left">
                            <ul class="solution-checklist">
                                <li><i class="ph-fill ph-check-circle"></i> Gánh phần việc lặp đi lặp lại, để đội ngũ tập trung vào khách hàng và chất lượng công việc.</li>
                                <li><i class="ph-fill ph-check-circle"></i> Một trợ lý AI đóng vai bộ não — hiểu hết tài liệu công ty và trả lời mọi câu hỏi.</li>
                                <li><i class="ph-fill ph-check-circle"></i> n8n là tay chân — mang bộ não đó ra khắp các kênh khách hàng và tự chạy những quy trình lặp lại khác trong công ty.</li>
                            </ul>
                            <div class="solution-quote">
                                "Một bộ não, nhiều đôi tay — vừa hiểu công ty bạn, vừa tự làm việc thay bạn."
                            </div>
                        </div>
                       <div class="solution-right">
                            <div class="video-box">
                                <img src="images/giai-phap.png" alt="Giải pháp">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Phần 1 - Trợ lý AI nội bộ (bộ não) -->
        <section class="section-padding num-section">
            <div class="container">
                <div class="text-center" style="text-align: left; margin-bottom: 48px;">
                    <div class="eyebrow">PHẦN 1 · Trợ lý AI nội bộ</div>
                    <h2 class="section-title" style="margin-bottom: 0;">Một trợ lý hiểu công ty bạn.</h2>
                </div>

                <div class="num-list">

                    <!-- 1 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/01</span>
                            <h3 class="num-heading">Tra cứu thông tin nội bộ tức thì</h3>
                        </div>
                        <div class="num-row-right">
                            <p>Hỏi bảng giá, mức chiết khấu, chính sách, chương trình khuyến mãi hay quy trình bảo hành và nhận câu trả lời ngay, kèm nguồn trong tài liệu gốc.</p>
                        </div>
                    </div>

                    <!-- 2 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/02</span>
                            <h3 class="num-heading">Nhân viên mới hòa nhập nhanh hơn</h3>
                        </div>
                        <div class="num-row-right">
                            <p>Không cần lục file hay hỏi quản lý. AI giúp nhân viên mới nắm bảng giá, quy trình và chính sách chỉ trong thời gian ngắn.</p>
                        </div>
                    </div>

                    <!-- 3 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/03</span>
                            <h3 class="num-heading">Tạo nhiều chatbot, mỗi bot một nhiệm vụ</h3>
                        </div>
                        <div class="num-row-right">
                            <p>Bot chăm sóc khách hàng, bot nội bộ, bot HR... Mỗi bot có kho kiến thức và nhiệm vụ riêng.</p>
                        </div>
                    </div>

                    <!-- 4 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/04</span>
                            <h3 class="num-heading">Tải tài liệu lên là bot biết</h3>
                        </div>
                        <div class="num-row-right">
                            <p>Hỗ trợ PDF, Word, Excel, ảnh chụp và PDF scan. Chỉ cần tải lên, AI sẽ đọc và hiểu nội dung.</p>
                        </div>
                    </div>

                    <!-- 5 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/05</span>
                            <h3 class="num-heading">Phân quyền theo phòng ban</h3>
                        </div>
                        <div class="num-row-right">
                            <p>Quản lý ai được sử dụng bot nào và được truy cập kho kiến thức nào theo từng vai trò.</p>
                        </div>
                    </div>

                    <!-- 6 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/06</span>
                            <h3 class="num-heading">AI trả lời có nguồn tham chiếu</h3>
                        </div>
                        <div class="num-row-right">
                            <p>Mỗi câu trả lời đều dẫn nguồn từ tài liệu gốc, giúp kiểm chứng thông tin và hạn chế AI "bịa".</p>
                        </div>
                    </div>

                    <!-- 7 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/07</span>
                            <h3 class="num-heading">Hỏi Excel như hỏi kế toán</h3>
                        </div>
                        <div class="num-row-right">
                            <p>Đặt câu hỏi về file số liệu và nhận đúng từng con số theo đúng định dạng tiếng Việt.</p>
                        </div>
                    </div>

                    <!-- 8 -->
                    <div class="num-row">
                        <div class="num-row-left">
                            <span class="num-index">/08</span>
                            <h3 class="num-heading">Biên bản họp và giao việc tự động</h3>
                        </div>
                        <div class="num-row-right">
                            <p>AI gỡ băng cuộc họp, tóm tắt quyết định, giao việc đúng người kèm hạn chót và xuất biên bản PDF/Word.</p>
                        </div>
                    </div>

                </div>
            </div>
        </section>
        
        <!-- 5. Phần 2 - n8n (tay và chân) -->
        <section class="section-padding n8n-dark-section">
            <div class="container">
                <div class="n8n-header">
                    <div class="eyebrow eyebrow-green">PHẦN 2 · TỰ ĐỘNG HÓA VỚI N8N</div>
                    <h2 class="section-title n8n-title">Mang trợ lý đi khắp nơi — và <span class="text-green-grad">tự động hoá</span> phần còn lại</h2>
                    <p class="n8n-subtitle">Kết nối đa kênh & tự động hóa các quy trình lặp lại trong công ty bạn.</p>
                </div>

                <!-- 2 card chính -->
                <div class="n8n-main-cards">
                    <div class="n8n-main-card n8n-card-blue-border">
                        <div class="n8n-card-top">
                            <div class="icon-circle"><img width="80" height="80" src="https://img.icons8.com/stickers/60/message-bot.png" alt="message-bot"/></div>
                            <span class="n8n-card-label">VỚI AI — MANG TRỢ LÝ AI ĐI KHẮP MỌI KÊNH</span>
                        </div>
                        <p>Giúp AI của bạn giao tiếp, hỗ trợ và chăm sóc khách hàng qua nhiều nền tảng: Zalo, Messenger, Website, Email, Telegram, CRM, tổng đài, chatbot,...</p>
                        <p>Khách hàng có thể hỏi bất kỳ đâu — AI hiểu, trả lời và hỗ trợ tức thì.</p>
                    </div>
                    <div class="n8n-main-card n8n-card-purple-border">
                        <div class="n8n-card-top">
                            <div class="icon-circle"><img width="90" height="90" src="images/logoo.png" alt="message-bot"/></div>
                            <span class="n8n-card-label">VỚI N8N — TỰ ĐỘNG HOÁ CÁC QUY TRÌNH KHÁC TRONG CÔNG TY</span>
                        </div>
                        <p>n8n là công cụ giúp bạn tự động hoá các tác vụ lặp lại: xử lý dữ liệu, gửi báo cáo, đồng bộ hệ thống, phê duyệt, nhắc việc,...</p>
                        <p>Giúp tiết kiệm thời gian – giảm sai sót – tối ưu hiệu suất đội ngũ.</p>
                    </div>
                </div>

                <!-- Lưới 6 tính năng -->
                <div class="n8n-features-grid">
                    <div class="n8n-feat-item">
                        <div class="n8n-feat-icon n8n-feat-teal"><i class="ph-fill ph-telegram-logo"></i></div>
                        <p>Trợ lý AI luôn sẵn sàng, tham gia mọi điểm chạm khách hàng của bạn.</p>
                    </div>
                    <div class="n8n-feat-item">
                        <div class="n8n-feat-icon n8n-feat-blue"><i class="ph-fill ph-users-three"></i></div>
                        <p>Đội ngũ mai it thao tác thủ công, giảm tải áp lực và tập trung vào công việc quan trọng, sáng tạo hơn.</p>
                    </div>
                    <div class="n8n-feat-item">
                        <div class="n8n-feat-icon n8n-feat-green"><i class="ph-fill ph-file-text"></i></div>
                        <p>Xử lý dữ liệu, báo cáo, cập nhật hệ thống diễn ra tự động, nhanh chóng, chính xác 24/7.</p>
                    </div>
                    <div class="n8n-feat-item">
                        <div class="n8n-feat-icon n8n-feat-orange"><i class="ph-fill ph-database"></i></div>
                        <p>Dễ tích hợp với hệ thống: Zalo, Telesale, CRM,...</p>
                    </div>
                    <div class="n8n-feat-item">
                        <div class="n8n-feat-icon n8n-feat-purple"><i class="ph-fill ph-shield-check"></i></div>
                        <p>Theo dõi quy trình & quản lý toàn bộ luồng dễ dàng.</p>
                    </div>
                    <div class="n8n-feat-item">
                        <div class="n8n-feat-icon n8n-feat-lime"><i class="ph-fill ph-check-circle"></i></div>
                        <p>Tăng hiệu quả giá bán, sản xuất, vận hành.</p>
                    </div>
                </div>

                <!-- Banner tổng kết -->
                <div class="n8n-summary-banner">
                    <div class="n8n-summary"><img width="80" height="80" src="https://img.icons8.com/external-flat-icons-vectorslab/68/external-Ai-artificial-and-intelligence-flat-icons-vectorslab.png" alt="external-Ai-artificial-and-intelligence-flat-icons-vectorslab"/>
                </div>
                    <p class="n8n-summary-title">Điểm nhấn nổi bật: Tự động hóa toàn diện để tăng năng suất và giảm gánh nặng vận hành.</p>
                    <div class="n8n-summary-checks">
                        <span><i class="ph-fill ph-check-circle"></i> Thông minh hơn</span>
                        <span><i class="ph-fill ph-check-circle"></i> Nhanh hơn</span>
                        <span><i class="ph-fill ph-check-circle"></i> Ít sai sót hơn</span>
                        <span><i class="ph-fill ph-check-circle"></i> Hiệu quả hơn</span>
                    </div>
                    <p class="n8n-summary-cta">Để <span class="text-primary">ROSA AI</span> đồng hành cùng bạn kiến tạo hệ thống vận hành tự động – <span class="text-green-grad">Tăng trưởng bền vững.</span></p>
                </div>
            </div>
        </section>


        <!-- 6. Differences -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left;">
                    <div class="eyebrow">ĐIỂM KHÁC BIỆT</div>
                    <h2 class="section-title">Không chỉ là một chatbot.</h2>
                </div>

                <div class="grid diff-grid">
                    <div class="diff-col">
                        <h3>01</h3>
                        <h4>Hỗ trợ đội ngũ, không thay thế</h4>
                        <p>AI tự động xử lý các công việc lặp lại để nhân viên tập trung vào những việc tạo ra giá trị.</p>
                    </div>

                    <div class="diff-col">
                        <h3>02</h3>
                        <h4>Hiểu dữ liệu của doanh nghiệp</h4>
                        <p>Được huấn luyện từ tài liệu, quy trình và chính sách của công ty để trả lời chính xác.</p>
                    </div>

                    <div class="diff-col">
                        <h3>03</h3>
                        <h4>Trả lời có nguồn, đáng tin cậy</h4>
                        <p>Mỗi câu trả lời đều có thể dẫn nguồn từ tài liệu nội bộ, hạn chế tối đa thông tin sai lệch.</p>
                    </div>

                    <div class="diff-col">
                        <h3>04</h3>
                        <h4>Triển khai theo nhu cầu doanh nghiệp</h4>
                        <p>Tích hợp với website, Zalo, Facebook, CRM hoặc hệ thống nội bộ, dễ mở rộng khi doanh nghiệp phát triển.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- 7. Applications -->
        <section class="section-padding">
            <div class="container">
                <div class="app-section">
                    <div class="app-left">
                        <div class="eyebrow">ỨNG DỤNG THỰC TẾ</div>
                        <h2 class="section-title">ROSA AI Workspace giúp gì được cho <span class="text-primary">đội ngũ của bạn</span>?</h2>
                        <ul class="app-list">
                            <li>
                                <!-- <div class="app-icon"><i class="ph-fill ph-tag"></i></div> -->
                                <div class="app-text"><i class="ph-fill ph-check-circle app-check"></i><strong>Nhân viên sale</strong> tra bảng giá, chiết khấu, khuyến mãi đang chạy — trả lời khách ngay tại chỗ.</div>
                            </li>
                            <li>
                                <!-- <div class="app-icon"><i class="ph-fill ph-graduation-cap"></i></div> -->
                                <div class="app-text"><i class="ph-fill ph-check-circle app-check"></i><strong>Nhân viên mới</strong> tự tra quy trình, chính sách, tài liệu đào tạo — rút ngắn thời gian hoà nhập.</div>
                            </li>
                            <li>
                                <!-- <div class="app-icon"><i class="ph-fill ph-headset"></i></div> -->
                                <div class="app-text"><i class="ph-fill ph-check-circle app-check"></i><strong>Nhân viên CSKH</strong> tra chính sách bảo hành, đổi trả — trả lời khách chính xác, có dẫn nguồn.</div>
                            </li>
                            <li>
                                <!-- <div class="app-icon"><i class="ph-fill ph-chats-circle"></i></div> -->
                                <div class="app-text"><i class="ph-fill ph-check-circle app-check"></i><strong>Chăm sóc khách hàng</strong> tự động trên Zalo & Facebook 24/7, chuyển người thật khi cần.</div>
                            </li>
                            <li>
                                <!-- <div class="app-icon"><i class="ph-fill ph-file-text"></i></div> -->
                                <div class="app-text"><i class="ph-fill ph-check-circle app-check"></i><strong>Biên bản họp</strong> và giao việc tự động sau mỗi cuộc họp.</div>
                            </li>
                            <li>
                                <!-- <div class="app-icon"><i class="ph-fill ph-calendar-check"></i></div> -->
                                <div class="app-text"><i class="ph-fill ph-check-circle app-check"></i><strong>Nhắc lịch hẹn</strong> khách hàng qua Zalo ZNS / SMS.</div>
                            </li>
                            <li>
                                <!-- <div class="app-icon"><i class="ph-fill ph-chart-line-up"></i></div> -->
                                <div class="app-text"><i class="ph-fill ph-check-circle app-check"></i><strong>Theo dõi giá đối thủ</strong>, tổng hợp báo cáo tự động.</div>
                            </li>
                        </ul>
                    </div>
                    <div class="app-right">
                        <img src="images/rosa-ai-page.png" alt="Bằng chứng ROSA AI Workspace" style="width:100%; height:auto; border-radius:16px; display:block;">
                    </div>
                </div>
            </div>
        </section>
        <!-- 8. Pricing + FAQ -->
        <section class="section-padding">
            <div class="container">
                <div class="pricing-faq-row">
                    <div class="pricing-faq-col pricing-faq-col-price">
                    
                        <div class="pricing-img-wrap">
                            <img src="images/cau-hoi.png" alt="Bảng giá ROSA AI Workspace" style="width:100%; height:auto; border-radius:16px; display:block;">
                        </div>
                    </div>
                    <div class="pricing-faq-col pricing-faq-col-faq">
                        <div class="text-center" style="text-align: left; margin-bottom: 32px;">
                            <div class="eyebrow">CÂU HỎI THƯỜNG GẶP</div>
                            <h2 class="section-title" style="margin-bottom: 0;">Giải đáp câu hỏi phổ biến</h2>
                        </div>
                        <div class="faq-list">
                            <div class="faq-item">
                                <div class="faq-item-left">
                                    <div class="faq-q">AI này có thay thế nhân viên của tôi không?</div>
                                    <div class="faq-a">Không. Mục tiêu là gánh phần việc lặp lại để đội ngũ tập trung vào khách hàng và những việc cần con người. Nhân viên dùng nó như một trợ lý tra cứu và hỗ trợ hằng ngày.</div>
                                </div>
                                <div class="faq-icon"><i class="ph ph-plus"></i></div>
                            </div>
                            <div class="faq-item">
                                <div class="faq-item-left">
                                    <div class="faq-q">AI làm việc bằng tiếng Việt có tốt không?</div>
                                    <div class="faq-a">Có. ROSA AI Workspace được tối ưu cho tiếng Việt, cả văn bản lẫn giọng nói.</div>
                                </div>
                                <div class="faq-icon"><i class="ph ph-plus"></i></div>
                            </div>
                            <div class="faq-item">
                                <div class="faq-item-left">
                                    <div class="faq-q">Dữ liệu của tôi có an toàn không?</div>
                                    <div class="faq-a">Có. Dữ liệu công ty không rời khỏi mạng nội bộ của bạn.</div>
                                </div>
                                <div class="faq-icon"><i class="ph ph-plus"></i></div>
                            </div>
                            <div class="faq-item">
                                <div class="faq-item-left">
                                    <div class="faq-q">AI có trả lời sai hay bịa không?</div>
                                    <div class="faq-a">Hiếm khi. Hệ thống có nhiều lớp chống bịa, và mọi câu trả lời đều kiểm chứng được nguồn trong tài liệu gốc.</div>
                                </div>
                                <div class="faq-icon"><i class="ph ph-plus"></i></div>
                            </div>
                            <div class="faq-item">
                                <div class="faq-item-left">
                                    <div class="faq-q">Chúng tôi không rành kỹ thuật, có dùng được không?</div>
                                    <div class="faq-a">Được. Đội ROSA sẽ cài đặt và cấu hình sẵn các quy trình cho bạn, không cần đội ngũ IT riêng.</div>
                                </div>
                                <div class="faq-icon"><i class="ph ph-plus"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 9. CTA cuối trang -->
        <section class="cta-banner container">
            <div class="cta-card">
                <div class="kh-cta-card">
                    <div class="cta-left">
                        <div class="kh-cta-icon"><i class="ph-fill ph-rocket-launch"></i></div>
                        <h2>Để đội ngũ của bạn tập trung vào điều quan trọng nhất.</h2>
                        <p>Đặt lịch demo miễn phí — xem ROSA AI Workspace chạy thử ngay trên tài liệu và quy trình của chính bạn.</p>
                        <a href="#" class="btn btn-primary js-open-modal">Đặt lịch demo miễn phí <i class="ph ph-arrow-right"></i></a>
                    </div>
                    <div class="cta-right cta-trust">
                        <span><i class="ph-fill ph-check-circle"></i> AI hiểu tiếng Việt</span>
                        <span><i class="ph-fill ph-check-circle"></i> Mỗi câu trả lời đều kiểm chứng được nguồn</span>
                        <span><i class="ph-fill ph-check-circle"></i> Dữ liệu trong mạng nội bộ</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 10. Footer -->
        <footer class="footer">
            <div class="container footer-content">
                <div class="f-logo-area">
                    <div class="logo">
                        <img src="images/rosa.png" alt="ROSA Logo" class="logo-icon">
                    </div>
                    <div class="f-slogan">
                        ROSA - Kết nối AI Local với thế giới<br>
                        <span>Giải pháp AI Local toàn diện cho doanh nghiệp Việt</span>
                    </div>
                </div>
                <div class="f-contact">
                    <span><i class="ph ph-phone"></i> (028) 39293765</span>
                    <span><i class="ph ph-envelope"></i> support@rosacomputer.ai</span>
                    <span><i class="ph ph-globe"></i> https://rosacomputer.vn/</span>
                </div>
                <div class="f-social">
                    <a href="#"><i class="ph ph-facebook-logo"></i></a>
                    <a href="#"><i class="ph ph-linkedin-logo"></i></a>
                    <a href="#"><i class="ph ph-youtube-logo"></i></a>
                </div>
            </div>
        </footer>
    </main>

    <!-- Modal đặt lịch demo -->
    <div id="contactModal" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <div class="modal-header">
                <h2>Đặt lịch demo ROSA AI Workspace</h2>
                <p>Để lại thông tin, đội ngũ ROSA sẽ liên hệ lại với bạn trong vòng 24h.</p>
            </div>
            <form id="demoForm" class="modal-form">
                <div class="form-group">
                    <label for="ems-fullname">Họ và tên *</label>
                    <input type="text" id="ems-fullname" name="fullname" placeholder="Nguyễn Văn A" required>
                </div>
                <div class="form-group">
                    <label for="ems-phone">Số điện thoại *</label>
                    <input type="tel" id="ems-phone" name="phone" placeholder="0901 234 567" required>
                </div>
                <div class="form-group">
                    <label for="ems-email">Email công việc *</label>
                    <input type="email" id="ems-email" name="email" placeholder="name@company.com" required>
                </div>
                <div class="form-group">
                    <label for="ems-company">Tên doanh nghiệp</label>
                    <input type="text" id="ems-company" name="company" placeholder="Công ty ABC">
                </div>
                <div class="form-group">
                    <label for="ems-message">Nhu cầu của bạn</label>
                    <textarea id="ems-message" name="message" rows="3" placeholder="Ví dụ: Tôi muốn AI tự động trả lời Zalo cho doanh nghiệp..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-full">Gửi yêu cầu</button>
            </form>
        </div>
    </div>

    <script>
        // Menu di động (hamburger)
        var navToggle = document.querySelector('.nav-toggle');
        var navLinks = document.querySelector('.nav-links');

        function closeNavMenu() {
            navLinks.classList.remove('active');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.innerHTML = '<i class="ph ph-list"></i>';
            document.body.style.overflow = '';
        }

        function openNavMenu() {
            navLinks.style.top = document.querySelector('.navbar').getBoundingClientRect().bottom + 'px';
            navLinks.classList.add('active');
            navToggle.setAttribute('aria-expanded', 'true');
            navToggle.innerHTML = '<i class="ph ph-x"></i>';
            document.body.style.overflow = 'hidden';
        }

        navToggle.addEventListener('click', function() {
            if (navLinks.classList.contains('active')) {
                closeNavMenu();
            } else {
                openNavMenu();
            }
        });

        navLinks.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                closeNavMenu();
            });
        });

        // Mở modal
        document.querySelectorAll('.js-open-modal').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('contactModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });

        // Đóng modal khi bấm nút &times;
        document.querySelector('.close-modal').addEventListener('click', function() {
            document.getElementById('contactModal').classList.remove('active');
            document.body.style.overflow = '';
        });

        // Đóng modal khi bấm vào overlay
        document.querySelector('.modal-overlay').addEventListener('click', function() {
            document.getElementById('contactModal').classList.remove('active');
            document.body.style.overflow = '';
        });

        // Đóng modal khi nhấn Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('contactModal').classList.remove('active');
                document.body.style.overflow = '';
            }
        });

        // Chọn gói giá: bấm vào thẻ nào thì thẻ đó nổi bật
        document.querySelectorAll('.price-card').forEach(function(card) {
            card.addEventListener('click', function() {
                document.querySelectorAll('.price-card').forEach(function(other) {
                    other.classList.remove('is-selected');
                });
                card.classList.add('is-selected');
            });
        });

        // Toggle FAQ: bấm câu hỏi để hiện/ẩn câu trả lời
        document.querySelectorAll('.faq-item').forEach(function(item) {
            item.addEventListener('click', function() {
                var isActive = item.classList.contains('active');
                document.querySelectorAll('.faq-item').forEach(function(other) {
                    other.classList.remove('active');
                });
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });

        // Sticky stack "Phần 1": glow sáng dần theo tiến độ scroll qua từng dòng
        (function () {
            var list = document.querySelector('.num-list');
            if (!list) return;
            var rows = Array.prototype.slice.call(list.querySelectorAll('.num-row'));
            if (!rows.length) return;

            var stickyTop = parseFloat(getComputedStyle(rows[0]).top) || 0;
            var metrics = [];

            function measure() {
                var listTop = list.getBoundingClientRect().top + window.scrollY;
                metrics = rows.map(function (row) {
                    var rowTop = listTop + row.offsetTop;
                    var start = rowTop - stickyTop;
                    return { el: row, start: start, end: start + row.offsetHeight };
                });
            }

            function update() {
                var y = window.scrollY;
                metrics.forEach(function (m) {
                    var span = m.end - m.start;
                    var progress = span > 0 ? (y - m.start) / span : 0;
                    progress = Math.max(0, Math.min(1, progress));
                    // ramps to full brightness quickly, then stays lit until covered
                    var eased = Math.min(1, progress / 0.35);
                    m.el.style.setProperty('--glow', eased.toFixed(3));
                });
            }

            var ticking = false;
            function onScroll() {
                if (ticking) return;
                ticking = true;
                requestAnimationFrame(function () {
                    update();
                    ticking = false;
                });
            }

            measure();
            update();
            window.addEventListener('scroll', onScroll, { passive: true });
            window.addEventListener('resize', function () {
                measure();
                update();
            });
        })();
    </script>

</body>

</html>