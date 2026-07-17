<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROSA AI Workspace - Trợ lý AI cho đội ngũ doanh nghiệp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" href="images/rosa-icon.png" type="image/png">
    <?php
    $emsCssVersion = filemtime(__DIR__ . '/ROSA-AI-Workspace.css');
    ?>
    <link rel="stylesheet" href="ROSA-AI-Workspace.css?v=<?php echo $emsCssVersion; ?>">
</head>

<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <img src="images/rosa.png" alt="ROSA Logo" class="logo-icon">
            </div>
            <nav class="nav-links">
                <a href="landing.php">Giải pháp</a>
                <a href="ROSA-AI-CONNECT.php">AI CONNECT</a>
                <a href="ROSA-AI-WORKSPACE.php">AI WORKSPACE</a>
                <a href="#">Ứng dụng</a>
                <a href="#">Sản phẩm</a>
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
                    <div class="eyebrow">VẤN ĐỀ</div>
                    <h2 class="section-title">Đội ngũ của bạn đang mất quá nhiều thời gian vào những việc lặp lại.</h2>
                </div>
                <div class="grid problem-grid">
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-magnifying-glass"></i></div>
                        <p>Nhân viên muốn tra một chính sách, một mức chiết khấu, một chương trình khuyến mãi đang chạy — phải lục file hoặc hỏi quản lý rồi ngồi chờ.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-user-plus"></i></div>
                        <p>Nhân viên mới mất hàng tuần mới thuộc hết bảng giá, quy trình, chính sách công ty.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-chat-circle-dots"></i></div>
                        <p>Cùng một câu hỏi của khách, nhân viên trả lời đi trả lời lại hàng trăm lần mỗi ngày.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-clock"></i></div>
                        <p>Khách nhắn tin lúc 11 giờ đêm — không ai trả lời cho đến sáng hôm sau.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-file-text"></i></div>
                        <p>Họp xong, quyết định trôi vào quên lãng, việc giao ra không ai theo dõi.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Solution -->
        <section class="section-padding">
            <div class="container solution-section">
                <div class="solution-left">
                    <div class="eyebrow">GIẢI PHÁP</div>
                    <h2 class="section-title" style="margin-bottom: 24px;">ROSA AI Workspace — trợ lý giúp cả công ty làm việc nhẹ hơn.</h2>
                    <p class="solution-desc">
                        ROSA AI Workspace không thay thế đội ngũ của bạn. Nó gánh phần việc lặp đi lặp lại, để mọi người tập trung vào điều thật sự quan trọng: khách hàng và chất lượng công việc.
                    </p>
                    <p class="solution-desc">
                        Hình dung đơn giản: có một trợ lý AI đóng vai bộ não, hiểu hết tài liệu công ty và trả lời mọi câu hỏi. Còn n8n là tay chân, mang bộ não đó ra khắp các kênh khách hàng và tự tay chạy những quy trình lặp đi lặp lại khác trong công ty.
                    </p>
                    <div class="solution-quote">
                        "Một bộ não, nhiều đôi tay — vừa hiểu công ty bạn, vừa tự làm việc thay bạn."
                    </div>
                </div>
                <div class="solution-right">
                    <div class="video-box">
                        <video src="images/chat_ai.mp4" autoplay muted loop playsinline></video>
                    </div>
                </div>
            </div>  
        </section>

        <!-- 4. Phần 1 - Trợ lý AI nội bộ (bộ não) -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left; margin-bottom: 48px;">
                    <div class="eyebrow">PHẦN 1 · Trợ lý AI nội bộ</div>
                    <h2 class="section-title" style="margin-bottom: 0;">Một trợ lý hiểu công ty bạn.</h2>
                </div>
                <div class="grid value-grid value-grid-4">
                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-blue"><i class="ph-fill ph-magnifying-glass"></i></div>
                            <div class="a-illustration-icon"><i class="ph ph-folder-open"></i></div>
                        </div>
                        <div class="a-content">
                            <h3>Tra cứu thông tin nội bộ</h3>
                            <span class="underline underline-blue"></span>
                            <p>Hỏi bảng giá, chiết khấu, chính sách hay quy trình bảo hành là có câu trả lời ngay, kèm nguồn trong tài liệu gốc — không cần lục file, không cần chờ quản lý.</p>
                        </div>
                    </div>
                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-green"><i class="ph-fill ph-squares-four"></i></div>
                            <div class="a-illustration-icon"><i class="ph ph-robot"></i></div>
                        </div>
                        <div class="a-content">
                            <h3>Tạo nhiều chatbot, mỗi bot một nhiệm vụ</h3>
                            <span class="underline underline-green"></span>
                            <p>Bot chăm sóc khách, bot nội bộ, bot HR — mỗi bot có kho kiến thức và nhiệm vụ riêng.</p>
                        </div>
                    </div>
                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-lightblue"><i class="ph-fill ph-file-arrow-up"></i></div>
                            <div class="a-illustration-icon"><i class="ph ph-files"></i></div>
                        </div>
                        <div class="a-content">
                            <h3>Tải tài liệu lên là bot biết</h3>
                            <span class="underline underline-lightblue"></span>
                            <p>Bảng giá, hợp đồng, quy trình, chính sách — đọc được cả PDF, Word, Excel lẫn ảnh chụp hay bản scan.</p>
                        </div>
                    </div>
                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-purple"><i class="ph-fill ph-users"></i></div>
                            <div class="a-illustration-icon"><i class="ph ph-lock-key"></i></div>
                        </div>
                        <div class="a-content">
                            <h3>Phân quyền cho từng người, từng phòng ban</h3>
                            <span class="underline underline-purple"></span>
                            <p>Ai được dùng bot nào, xem kho kiến thức nào — bạn là người quyết định.</p>
                        </div>
                    </div>
                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-orange"><i class="ph-fill ph-sparkle"></i></div>
                            <div class="a-illustration-icon"><i class="ph ph-shield-check"></i></div>
                        </div>
                        <div class="a-content">
                            <h3>AI cho bạn thấy bằng chứng</h3>
                            <span class="underline underline-orange"></span>
                            <p>Mỗi câu trả lời đều chỉ rõ nguồn trong tài liệu gốc, bạn kiểm tra lại được ngay — không lo AI "bịa".</p>
                        </div>
                    </div>
                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-teal"><i class="ph-fill ph-table"></i></div>
                            <div class="a-illustration-icon"><i class="ph ph-chart-bar"></i></div>
                        </div>
                        <div class="a-content">
                            <h3>Hỏi Excel như hỏi kế toán</h3>
                            <span class="underline underline-teal"></span>
                            <p>Đặt câu hỏi thẳng về file số liệu, nhận đúng từng con số theo đúng định dạng tiếng Việt.</p>
                        </div>
                    </div>
                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-blue2"><i class="ph-fill ph-check-circle"></i></div>
                            <div class="a-illustration-icon"><i class="ph ph-calendar-check"></i></div>
                        </div>
                        <div class="a-content">
                            <h3>Biên bản họp và giao việc tự động</h3>
                            <span class="underline underline-blue2"></span>
                            <p>Ghi âm hoặc tải file cuộc họp lên, AI gỡ băng kèm tên người nói, tóm tắt quyết định rồi giao việc đúng người kèm hạn chót — xuất luôn ra PDF/Word.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- 5. Phần 2 - n8n (tay và chân) -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left; margin-bottom: 48px;">
                    <div class="eyebrow">PHẦN 2 · TỰ ĐỘNG HÓA VỚI N8N</div>
                    <h2 class="section-title" style="margin-bottom: 0;">Mang trợ lý đi khắp nơi — và tự động hoá phần còn lại</h2>
                </div>
                <div class="tasks-grid">
                    <div class="task-card">
                        <div class="icon-circle icon-blue"><i class="ph-fill ph-share-network"></i></div>
                        <h4 class="task-title">Việc 1 — Đưa trợ lý AI ra mọi kênh khách hàng</h4>
                        <p class="task-desc">Con bot bạn xây trong ROSA AI Workspace được n8n mang lên website, Facebook Messenger, Zalo, Telegram… Cùng một "bộ não" trả lời khách ở khắp nơi, không phải dựng bốn con bot khác nhau hay cấu hình lại bốn lần.</p>
                    </div>
                    <div class="task-card">
                        <div class="icon-circle icon-purple"><i class="ph-fill ph-gear-six"></i></div>
                        <h4 class="task-title">Việc 2 — Tự động hoá các quy trình khác trong công ty</h4>
                        <p class="task-desc">n8n đảm nhận phần tự động hoá còn lại. Vì chạy trên AI nội bộ của bạn nên không tốn phí theo từng lượt xử lý.</p>
                    </div>
                </div>
                <div class="automation-grid">
                    <div class="automation-item">
                        <i class="ph-fill ph-megaphone"></i>
                        <span>Tạo nội dung: lấy tin tức, soạn bài đăng cho từng nền tảng, duyệt rồi tự đăng.</span>
                    </div>
                    <div class="automation-item">
                        <i class="ph-fill ph-envelope-simple-open"></i>
                        <span>Xử lý email & lead: đọc email đến, phân loại, sàng lọc lead từ quảng cáo, gán đúng nhân viên sale và nhắc follow-up.</span>
                    </div>
                    <div class="automation-item">
                        <i class="ph-fill ph-file-text"></i>
                        <span>Xử lý tài liệu: đọc hoá đơn, chứng từ, hợp đồng rồi bóc số liệu vào file hoặc phần mềm kế toán.</span>
                    </div>
                    <div class="automation-item">
                        <i class="ph-fill ph-bell-ringing"></i>
                        <span>Nhắc lịch hẹn tự động qua Zalo ZNS / SMS.</span>
                    </div>
                    <div class="automation-item">
                        <i class="ph-fill ph-chart-line-up"></i>
                        <span>Theo dõi giá đối thủ, cảnh báo khi có thay đổi.</span>
                    </div>
                    <div class="automation-item">
                        <i class="ph-fill ph-clipboard-text"></i>
                        <span>Tổng hợp và gửi báo cáo ngày/tuần tự động.</span>
                    </div>
                </div>

                <div class="n8n-quote-banner">
                    <i class="ph-fill ph-quotes"></i>
                    <p class="quote-text">Gần như bất kỳ quy trình lặp đi lặp lại nào khác trong công ty bạn cũng tự động hoá được.</p>
                    <div class="quote-author">Đội ROSA sẽ cùng bạn thiết kế và cấu hình các quy trình này — <span>bạn không cần biết kỹ thuật.</span></div>
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
                        <p>AI lo phần việc lặp lại, con người lo phần cần đầu óc và cảm xúc.</p>
                    </div>
                    <div class="diff-col">
                        <h3>02</h3>
                        <h4>Đặt tại công ty bạn</h4>
                        <p>Dữ liệu không rời khỏi mạng nội bộ.</p>
                    </div>
                    <div class="diff-col">
                        <h3>03</h3>
                        <h4>Hiểu tiếng Việt sâu sắc</h4>
                        <p>Trả lời chuẩn tiếng Việt, kể cả khi khách gõ không dấu.</p>
                    </div>
                    <div class="diff-col">
                        <h3>04</h3>
                        <h4>Trả lời có nguồn</h4>
                        <p>AI đáng tin, luôn kiểm chứng được, không bịa đặt.</p>
                    </div>
                    <div class="diff-col">
                        <h3>05</h3>
                        <h4>Chi phí cố định</h4>
                        <p>Tự động hoá bao nhiêu tuỳ thích, không tính phí theo từng tin nhắn.</p>
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
                        <h2 class="section-title">ROSA AI Workspace giúp gì được cho đội ngũ của bạn?</h2>
                        <ul class="app-list">
                            <li><i class="ph-fill ph-check-circle"></i> Nhân viên sale tra bảng giá, chiết khấu, khuyến mãi đang chạy — trả lời khách ngay tại chỗ.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Nhân viên mới tự tra quy trình, chính sách, tài liệu đào tạo — rút ngắn thời gian hoà nhập.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Nhân viên CSKH tra chính sách bảo hành, đổi trả — trả lời khách chính xác, có dẫn nguồn.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Chăm sóc khách hàng tự động trên Zalo & Facebook 24/7, chuyển người thật khi cần.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Biên bản họp và giao việc tự động sau mỗi cuộc họp.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Nhắc lịch hẹn khách hàng qua Zalo ZNS / SMS.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Theo dõi giá đối thủ, tổng hợp báo cáo tự động.</li>
                        </ul>
                    </div>
                    <div class="app-right">
                        <div class="quote-box">
                            <div class="eyebrow eyebrow-quote">BẰNG CHỨNG</div>
                            <!-- <i class="ph-fill ph-quotes"></i> -->
                            <p class="quote-text">
                                "Khu vực chèn câu chuyện khách hàng thật, kèm ảnh chụp màn hình bot đang chạy trên Zalo hoặc biên bản họp được tạo tự động."
                            </p>
                            <div class="quote-author">
                                <span>— CT TNHH DOANH NGHIỆP</span> — Giám đốc vận hành
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. FAQ -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left; max-width: 1500px; margin: 0 auto 32px; font-size:20px">
                    <div class="eyebrow">CÂU HỎI THƯỜNG GẶP</div>
                    <h2 class="section-title"> Giải đáp những câu hỏi phổ biến về ROSA AI Workspace</h2>
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
        </section>

        <!-- 9. CTA cuối trang -->
        <section class="cta-banner container">
            <div class="cta-card kh-cta-card">
                <div class="kh-cta-icon"><img src="https://img.icons8.com/office/40/crowd.png" alt="Claude Code"></div>
                <div class="cta-left">
                    <h2>Để đội ngũ của bạn tập trung vào điều quan trọng nhất.</h2>
                    <p>Đặt lịch demo miễn phí — xem ROSA AI Workspace chạy thử ngay trên tài liệu và quy trình của chính bạn.</p>
                </div>
                <div class="cta-right">
                    <a href="#" class="btn btn-primary js-open-modal">Đặt lịch demo miễn phí <i class="ph ph-arrow-right"></i></a>
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
    </script>

</body>

</html>