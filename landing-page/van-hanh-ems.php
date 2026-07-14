<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROSA AI - Trợ lý AI cho doanh nghiệp</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <?php
$emsCssVersion = filemtime(__DIR__ . '/van-hanh-ems.css');
?>
    <link rel="stylesheet" href="van-hanh-ems.css?v=<?php echo $emsCssVersion; ?>">
</head>
<body>

    <!-- Navbar -->
    <header class="navbar">
        <div class="container flex justify-between items-center">
            <div class="logo">
                <div class="logo-icon">R</div>
                ROSA AI
            </div>
            <a href="#" class="btn btn-primary js-open-modal">Đặt lịch demo miễn phí</a>
        </div>
    </header>

    <main>
        <!-- 1. Hero -->
        <section class="container hero">
            <div class="hero-left">
                <div class="eyebrow">TRỢ LÝ AI CHO DOANH NGHIỆP</div>
                <h1 class="hero-title">Trợ lý AI làm việc thay bạn — <span class="text-primary">24/7</span>, ngay tại công ty.</h1>
                <p class="hero-desc">
                    ROSA AI là tài khoản mạng trên Zalo, Telegram, Facebook Messenger; ghi nhớ toàn bộ cuộc trò chuyện, tự động học quy trình của bạn và làm việc không cần nghỉ.
                </p>
                <a href="#" class="btn btn-primary js-open-modal">Đặt lịch demo miễn phí <i class="ph ph-arrow-right"></i></a>
                
                <div class="hero-features">
                    <span><i class="ph-fill ph-check-circle"></i> AI hiểu tiếng Việt</span>
                    <span><i class="ph-fill ph-check-circle"></i> Triển khai 1 buổi, bắt đầu dùng ngay</span>
                    <span><i class="ph-fill ph-check-circle"></i> Không cần IT phức tạp</span>
                </div>
            </div>
            <div class="hero-right">
                <div class="hero-diagram">
                    <div class="diagram-lines">
                        <svg>
                            <!-- Lines from center to nodes -->
                            <line x1="50%" y1="50%" x2="25%" y2="20%" />
                            <line x1="50%" y1="50%" x2="75%" y2="20%" />
                            <line x1="50%" y1="50%" x2="25%" y2="60%" />
                            <line x1="50%" y1="50%" x2="75%" y2="60%" />
                            <line x1="50%" y1="50%" x2="50%" y2="85%" />
                        </svg>
                    </div>
                    <div class="diagram-center">
                        ROSA<br><span>AI WORK</span>
                    </div>
                    <div class="diagram-nodes">
                        <div class="d-node node-zalo">
                            <img src="https://img.icons8.com/color/48/zalo.png" alt="Zalo"> Zalo
                        </div>
                        <div class="d-node node-telegram">
                            <img src="https://img.icons8.com/color/48/telegram-app--v1.png" alt="Telegram"> Telegram
                        </div>
                        <div class="d-node node-messenger">
                            <img src="https://img.icons8.com/color/48/facebook-messenger--v1.png" alt="Messenger"> Messenger
                        </div>
                        <div class="d-node node-website">
                            <i class="ph-fill ph-globe text-primary" style="font-size:20px"></i> Website
                        </div>
                        <div class="d-node node-time">
                            <i class="ph ph-clock"></i> 24/7
                        </div>
                    </div>
                    <div class="diagram-footer">
                        HỌC TỪ BẠN — TRẢ LỜI MỌI KÊNH — KHÔNG CẦN CODE
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Problem -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center">
                    <div class="eyebrow">VẤN ĐỀ</div>
                    <h2 class="section-title">Nhân viên của bạn đang lãng phí hàng giờ mỗi ngày.</h2>
                </div>
                <div class="grid problem-grid">
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-chat-circle-dots"></i></div>
                        <p>Trả lời cùng một câu hỏi của khách hàng, hàng trăm lần mỗi ngày.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-clock"></i></div>
                        <p>Khách nhắn tin lúc 11 giờ đêm — không ai trả lời cho đến sáng hôm sau.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-file-text"></i></div>
                        <p>Họp xong, quyết định trôi vào quên lãng, việc giao ra không ai theo dõi.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-database"></i></div>
                        <p>Tìm câu trả lời, soạn tài liệu, nhập liệu thủ công... ngốn quá nhiều thời gian.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Solution -->
        <section class="section-padding">
            <div class="container solution-section">
                <div class="solution-left">
                    <div class="eyebrow">GIẢI PHÁP</div>
                    <h2 class="section-title" style="margin-bottom: 24px;">ROSA AI — bộ não AI cho cả doanh nghiệp của bạn.</h2>
                    <p class="solution-desc">
                        ROSA AI là trợ lý AI đặt ngay tại công ty bạn. Nó hiểu tất cả tài liệu của bạn, trả lời khách hàng như nhân viên tốt nhất của bạn nhưng không bao giờ mệt. Tiết kiệm thời gian, giảm sai sót và nâng cao chất lượng phục vụ — tự động hóa bắt đầu ngay từ hôm nay.
                    </p>
                    <div class="solution-quote">
                        "Một bộ não duy nhất, làm việc ở khắp mọi phòng ban, nghiệp vụ."
                    </div>
                </div>
                <div class="solution-right">
                    <div class="video-box">
                        <div class="play-btn"><i class="ph-fill ph-play"></i></div>
                        <h4>Xem video giới thiệu ROSA AI</h4>
                        <p>Trải nghiệm 1 phút về trợ lý AI cho doanh nghiệp<br>Video • Tự động hóa đa kênh</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Core Values -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left; margin-bottom: 48px;">
                    <div class="eyebrow">GIÁ TRỊ CỐT LÕI</div>
                    <h2 class="section-title" style="margin-bottom: 0;">Mọi thứ bạn cần để AI thực sự làm việc</h2>
                </div>
                <div class="grid value-grid">
                    <div class="value-card">
                        <div class="value-icon"><i class="ph ph-squares-four"></i></div>
                        <div class="value-content">
                            <h4>Một chatbot, mọi kênh</h4>
                            <p>Xây một con bot trong ROSA AI để dùng trên Zalo, Telegram, Facebook Messenger và website — cùng một trí tuệ, thống nhất trải nghiệm.</p>
                        </div>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="ph ph-sparkle"></i></div>
                        <div class="value-content">
                            <h4>AI cho bạn thấy hàng chục</h4>
                            <p>Nó đọc tài liệu, nhớ lại, trả lời và gợi ý đúng theo ngữ cảnh công việc của bạn.</p>
                        </div>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="ph ph-table"></i></div>
                        <div class="value-content">
                            <h4>Hiểu Excel như tôi kế toán</h4>
                            <p>Đọc và xử lý số liệu, so sánh đơn hàng, công nợ, doanh thu... chính xác như nhân viên kế toán.</p>
                        </div>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="ph ph-check-circle"></i></div>
                        <div class="value-content">
                            <h4>Họp xong là có biên bản + giao việc</h4>
                            <p>Ghi âm cuộc họp, AI tóm tắt, trích việc, phân người làm, và giao việc đúng người, làm hạn chót.</p>
                        </div>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="ph ph-gear"></i></div>
                        <div class="value-content">
                            <h4>Tự động hoá công nợ</h4>
                            <p>Nhắc lịch hẹn, theo dõi hợp đồng, xử lý đề xuất, chuyển dữ liệu... để hệ thống tự chạy.</p>
                        </div>
                    </div>
                    <div class="value-card">
                        <div class="value-icon"><i class="ph ph-users"></i></div>
                        <div class="value-content">
                            <h4>Mọi phòng ban một AI riêng</h4>
                            <p>Phân quyền, tạo phòng ban, bot chăm sóc khách, bot nội bộ, bot HR — mỗi bot một nhiệm vụ.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 5. Differences -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left;">
                    <div class="eyebrow">ĐIỂM KHÁC BIỆT</div>
                    <h2 class="section-title">Không chỉ là một chatbot.</h2>
                </div>
                <div class="grid diff-grid">
                    <div class="diff-col">
                        <h3>01</h3>
                        <h4>Biết lắng nghe & học</h4>
                        <p>Dựa trên toàn bộ dữ liệu nội bộ của bạn.</p>
                    </div>
                    <div class="diff-col">
                        <h3>02</h3>
                        <h4>Hiểu tiếng Việt sâu sắc</h4>
                        <p>Hiểu ngôn ngữ tự nhiên, kể cả khi khách gõ không dấu.</p>
                    </div>
                    <div class="diff-col">
                        <h3>03</h3>
                        <h4>Trả lời có nguồn</h4>
                        <p>AI dẫn nguồn, bạn kiểm tra dễ dàng — không ảo tưởng.</p>
                    </div>
                    <div class="diff-col">
                        <h3>04</h3>
                        <h4>Gắn kết dữ liệu</h4>
                        <p>Tự động hoá báo cáo, kết nối hệ thống nội bộ mà không tốn dev.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 6. Applications -->
        <section class="section-padding">
            <div class="container">
                <div class="app-section">
                    <div class="app-left">
                        <div class="eyebrow">ỨNG DỤNG THỰC TẾ</div>
                        <h2 class="section-title">ROSA AI có thể làm gì cho bạn?</h2>
                        <ul class="app-list">
                            <li><i class="ph-fill ph-check-circle"></i> Chăm sóc khách hàng tự động trên Zalo & Facebook, 24/7.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Tổng đài nội bộ: dán nhãn, tra cứu quy trình, chính sách, tài liệu — trả lời tức thì.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Biên bản họp và giao việc tự động.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Nhắc lịch hẹn khách hàng qua Zalo SMS / SMS.</li>
                            <li><i class="ph-fill ph-check-circle"></i> Theo dõi dữ liệu, tổng hợp báo cáo tự động.</li>
                        </ul>
                    </div>
                    <div class="app-right">
                        <div class="quote-box">
                            <i class="ph-fill ph-quotes"></i>
                            <p class="quote-text">
                                "Khu vực chèn câu chuyện khách hàng thật, cũng đánh chụp màn hình bot đang chạy trên Zalo hoặc biên bản họp được tạo tự động."
                            </p>
                            <div class="quote-author">
                                <span>— CT TNHH DOANH NGHIỆP</span> — Giám đốc vận hành
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7. FAQ -->
        <section class="section-padding">
            <div class="container">
                <div class="text-center" style="text-align: left; max-width: 800px; margin: 0 auto 32px;">
                    <div class="eyebrow">CÂU HỎI THƯỜNG GẶP</div>
                </div>
                <div class="faq-list">
                    <div class="faq-item">
                        <div class="faq-item-left">
                            <div class="faq-q">AI làm việc bằng tiếng Việt có tốt không?</div>
                            <div class="faq-a">Có. ROSA AI được tối ưu cho tiếng Việt, có văn bản lẫy giọng nói.</div>
                        </div>
                        <div class="faq-icon"><i class="ph ph-plus"></i></div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-item-left">
                            <div class="faq-q">Dữ liệu của tôi có an toàn không?</div>
                        </div>
                        <div class="faq-icon"><i class="ph ph-plus"></i></div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-item-left">
                            <div class="faq-q">AI có trả lời sai hay bịa không?</div>
                        </div>
                        <div class="faq-icon"><i class="ph ph-plus"></i></div>
                    </div>
                    <div class="faq-item">
                        <div class="faq-item-left">
                            <div class="faq-q">Chúng tôi không rành kỹ thuật, có dùng được không?</div>
                        </div>
                        <div class="faq-icon"><i class="ph ph-plus"></i></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 8. Footer CTA -->
        <section class="container">
            <div class="footer-cta">
                <div class="cta-bg"></div>
                <div class="cta-content">
                    <h2>Để AI làm việc. Bạn tập trung phát triển doanh nghiệp.</h2>
                    <p>Đặt lịch demo miễn phí — xem ROSA AI hoạt động trên dữ liệu và quy trình của chính bạn.</p>
                    <a href="#" class="btn btn-primary js-open-modal">Đặt lịch demo miễn phí <i class="ph ph-arrow-right"></i></a>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="logo">
                    <div class="logo-icon">R</div>
                    ROSA AI
                </div>
                <div class="f-copy">
                    © 2025 ROSA AI — Trợ lý AI cho doanh nghiệp Việt Nam — 24/7
                </div>
            </div>
        </section>
    </main>

    <!-- Modal đặt lịch demo -->
    <div id="contactModal" class="modal">
        <div class="modal-overlay"></div>
        <div class="modal-content">
            <button class="close-modal">&times;</button>
            <div class="modal-header">
                <h2>Đặt lịch demo ROSA AI</h2>
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
</script>

</body>
</html>

