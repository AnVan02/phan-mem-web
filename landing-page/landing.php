<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROSA - AI Local cho doanh nghiệp</title>
    <link rel="stylesheet" href="landing.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" href="images/rosa-icon.png" type="image/png">

</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <img src="images/rosa.png" alt="ROSA Logo" class="logo-icon">
            </div>
            <nav class="nav-links">
                <a href="#">Giải pháp</a>
                <a href="#">Sản phẩm</a>
                <a href="#">Ứng dụng</a>
                <a href="#">Khách hàng</a>
                <a href="#">Về ROSA</a>
            </nav>
            <a href="#" class="btn btn-primary">Liên hệ tư vấn</a>
        </div>
    </header>

    <main>
        <!-- Section 1: Hero -->
        <section class="hero container">
            <div class="hero-left">
                <div class="badge-label">
                    <span class="number">1</span> Máy trạm AI – HOST AI LOCAL
                </div>
                <h1 class="hero-title">Sức mạnh AI<br>ngay tại doanh nghiệp</h1>
                <p class="hero-desc">ASUS Ascent GX10 (NVIDIA® DXG Spark) là siêu máy tính AI để bàn nhỏ gọn, giúp doanh
                    nghiệp chạy AI ngay tại chỗ, bảo mật dữ liệu và không phụ thuộc cloud.</p>

                <div class="features-list">
                    <div class="feature-item">
                        <i class="ph-fill ph-shield-check"></i>
                        <div>
                            <h4>Bảo mật dữ liệu</h4>
                            <p>AI chạy hoàn toàn tại doanh nghiệp</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="ph-fill ph-lightning"></i>
                        <div>
                            <h4>Hiệu năng mạnh mẽ</h4>
                            <p>Xử lý AI nhanh chóng và hiệu quả</p>
                        </div>
                    </div>
                    <div class="feature-item">
                        <i class="ph-fill ph-cloud-slash"></i>
                        <div>
                            <h4>Không phụ thuộc cloud</h4>
                            <p>Tiết kiệm chi phí, chủ động vận hành</p>
                        </div>
                    </div>
                </div>

                <div class="hero-buttons">
                    <a href="https://www.asus.com/vn/networking-iot-servers/desktop-ai-supercomputer/ultra-small-ai-supercomputers/asus-ascent-gx10/"
                        class="btn btn-primary">Tìm hiểu ASUS GX10 <i class="ph ph-arrow-right"></i></a>
                    <a href="#" class="btn btn-outline">Nhận tư vấn giải pháp</a>
                </div>
            </div>
            <div class="hero-right">
                <div class="logos-top">
                    <img src="images/ASUS_Corporate_Logo.svg.webp" alt="ASUS" class="brand-logo asus-logo">
                    <div class="logo-divider"></div>
                    <img src="images/NVIDIA_logo_white.svg.webp" alt="NVIDIA" class="brand-logo nvidia-logo">
                </div>

                <h2 class="product-title">ASUS Ascent GX10</h2>
                <p class="product-sub">Powered by NVIDIA® DGX Spark</p>

                <div class="product-image-container">
                    <img src="images/w692.png" alt="ASUS Ascent GX10" class="product-image">
                </div>
            </div>
        </section>

        <!-- Section 2: Platform AI -->
        <section class="platform container">
            <div class="badge-label">
                <span class="number">2</span> Platform AI - Kết nối & Tự động hóa
            </div>
            <h2 class="section-title text-center">Kết nối AI Local với các ứng dụng hàng đầu</h2>
            <p class="section-desc text-center">Thay vì tốn tiền thuê cloud, doanh nghiệp dùng chính AI chạy trên máy
                trạm<br>để vận hành các công cụ AI nổi tiếng và tự động hóa quy trình.</p>

            <div class="platform-cards">
                <div class="card app-card">
                    <h3>Kết nối với các ứng dụng AI phổ biến</h3>
                    <p>Sử dụng AI Local để vận hành các công cụ bạn yêu thích</p>
                    <div class="platform-grid reveal">
                        <div class="diagram-wrap">
                            <svg viewBox="0 0 520 400" width="100%" role="img"
                                aria-label="Sơ đồ kết nối AI Local với các ứng dụng AI">
                                <defs>
                                    <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
                                        <stop offset="0%" stop-color="#52d6c6" stop-opacity=".9" />
                                        <stop offset="100%" stop-color="#52d6c6" stop-opacity=".15" />
                                    </linearGradient>
                                    <clipPath id="clip-center">
                                        <circle cx="260" cy="180" r="40" />
                                    </clipPath>
                                    <clipPath id="clip-tl">
                                        <circle cx="110" cy="70" r="34" />
                                    </clipPath>
                                    <clipPath id="clip-tr">
                                        <circle cx="410" cy="70" r="34" />
                                    </clipPath>
                                    <clipPath id="clip-bl">
                                        <circle cx="110" cy="290" r="34" />
                                    </clipPath>
                                    <clipPath id="clip-br">
                                        <circle cx="410" cy="290" r="34" />
                                    </clipPath>
                                </defs>
                                <!-- connecting lines -->
                                <g stroke="url(#lineGrad)" stroke-width="1.6" fill="none">
                                    <path d="M260,180 L110,70" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.6s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M260,180 L410,70" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.8s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M260,180 L110,290" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="2s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M260,180 L410,290" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.7s"
                                            repeatCount="indefinite" />
                                    </path>
                                </g>
                                <!-- center node: AI Local -->
                                <circle cx="260" cy="180" r="40" fill="#1d222d" stroke="#d89b4a" stroke-width="1.6" />
                                <image href="images/pc.jpg" x="220" y="140" width="80" height="80"
                                    clip-path="url(#clip-center)" preserveAspectRatio="xMidYMid slice" />
                                <text x="260" y="240" text-anchor="middle" fill="#edeff3"
                                    font-family=" 'Montserrat', Arial, sans-serif" font-size="13" font-weight="600">ASUS
                                    Ascent</text>
                                <text x="260" y="254" text-anchor="middle" fill="#9aa3b2"
                                    font-family=" 'Montserrat', Arial, sans-serif" font-size="9">GX10</text>
                                <!-- outer nodes -->
                                <g font-family=" 'Montserrat', Arial, sans-serif" font-size="11" fill="#edeff3">
                                    <circle cx="110" cy="70" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="images/claude.png" x="76" y="36" width="68" height="68"
                                        clip-path="url(#clip-tl)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="110" y="126" text-anchor="middle">Claude Code</text>

                                    <circle cx="410" cy="70" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="images/logo-n8n.png" x="376" y="36" width="68" height="68"
                                        clip-path="url(#clip-tr)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="410" y="126" text-anchor="middle">n8n</text>

                                    <circle cx="110" cy="290" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="images/hermes.png" x="76" y="256" width="68" height="68"
                                        clip-path="url(#clip-bl)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="110" y="346" text-anchor="middle">Hermes</text>

                                    <circle cx="410" cy="290" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="images/openclaw.png" x="376" y="256" width="68" height="68"
                                        clip-path="url(#clip-br)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="410" y="346" text-anchor="middle">OpenClaw</text>
                                </g>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="card n8n-card">
                    <h3>Tích hợp n8n – Tự động hóa quy trình</h3>
                    <p>Tự động hóa các quy trình doanh nghiệp với n8n</p>
                    <div class="logos-top">
                        <img src="images/logo-white.svg" alt="ASUS" class="brand-logo asus-logo">
                    </div>
                    <ul class="n8n-features">
                        <li><i class="ph-fill ph-check-circle"></i> Chăm sóc khách hàng tự động trên mạng xã hội</li>
                        <li><i class="ph-fill ph-check-circle"></i> Tự động gửi tin nhắn quảng cáo</li>
                        <li><i class="ph-fill ph-check-circle"></i> Lọc / phân loại email</li>
                        <li><i class="ph-fill ph-check-circle"></i> Và nhiều ứng dụng khác của n8n</li>
                    </ul>
                </div>


            </div>
        </section>

        <!-- Section 3: Chatbot -->
        <section class="chatbot container">
            <div class="badge-label">
                <span class="number">3</span> Tặng kèm: Giải pháp riêng của ROSA
            </div>
            <div class="chatbot-content">
                <div class="chatbot-left">
                    <h2 class="section-title">Hệ thống Chatbot nội bộ</h2>
                    <p class="section-desc">
                        Được phát triển bởi <span style="color:#e60012;font-weight:700;">ROSA</span>,
                        giải pháp hỗ trợ doanh nghiệp quản lý tập trung, khai thác hiệu quả kho tài liệu
                        và tối ưu hiệu suất làm việc.
                    </p>
                    <div class="tag-green">3 chức năng chính</div>

                    <div class="chatbot-features">
                        <div class="feature-item-chat">
                            <div class="icon-box"><i class="ph ph-chat-teardrop-text"></i></div>
                            <div>
                                <h4>Tìm kiếm thông tin nhanh</h4>
                                <p>Đặt câu hỏi bằng ngôn ngữ tự nhiên và nhận câu trả lời ngay từ tài liệu của doanh
                                    nghiệp.</p>
                            </div>
                        </div>

                        <div class="feature-item-chat">
                            <div class="icon-box"><i class="ph ph-book-open"></i></div>
                            <div>
                                <h4>Học từ tài liệu của bạn</h4>
                                <p>Chỉ cần tải lên tài liệu, chatbot sẽ học nội dung và trả lời đúng theo dữ liệu đã
                                    cung cấp.</p>
                            </div>
                        </div>

                        <div class="feature-item-chat">
                            <div class="icon-box"><i class="ph ph-waveform"></i></div>
                            <div>
                                <h4>Ghi âm & tóm tắt cuộc họp</h4>
                                <p>Tự động ghi âm, tóm tắt những nội dung chính và lưu lại để dễ dàng tra cứu.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="chatbot-right">
                    <div class="chatbot-mockup">
                        <div class="mockup-header">
                            <div class="window-controls">
                                <span class="dot red"></span>
                                <span class="dot yellow"></span>
                                <span class="dot green"></span>
                            </div>
                            <span class="mockup-title">
                                <img src="images/rosa.png" alt="ROSA" class="inline-logo">
                            </span>
                            <i class="ph ph-dots-three"></i>
                        </div>
                        <div class="mockup-body">
                            <div class="mockup-sidebar">
                                <div class="nav-item active"><i class="ph-fill ph-house"></i> Trang chủ</div>
                                <div class="nav-item"><i class="ph ph-chat-circle"></i> Hội thoại</div>
                                <div class="nav-item"><i class="ph ph-folder"></i> Tài liệu</div>
                                <div class="nav-item"><i class="ph ph-users"></i> Cuộc họp</div>
                                <div class="nav-item mt-auto"><i class="ph ph-gear"></i> Cài đặt</div>
                            </div>
                            <div class="mockup-main">

                                <!-- User asks about contract process -->
                                <div class="chat-message user">
                                    <div class="msg-content">Quy trình xử lý hợp đồng của công ty là gì?</div>
                                </div>

                                <!-- Bot answers with doc reference -->
                                <div class="chat-message">
                                    <div class="bot-avatar"></div>
                                    <div class="msg-content">
                                        Dựa trên tài liệu nội bộ, quy trình xử lý hợp đồng gồm 5 bước:
                                        <div class="doc-card">
                                            <i class="ph-fill ph-file-pdf doc-icon"></i>
                                            <span>Quy trình xử lý hợp đồng.pdf</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- User asks for meeting summary -->
                                <div class="chat-message user">
                                    <div class="msg-content">Tóm tắt cuộc họp dự án hôm nay</div>
                                </div>

                                <!-- Bot answers with summary + voice player -->
                                <div class="chat-message">
                                    <div class="bot-avatar"></div>
                                    <div class="msg-content">
                                        Dưới đây là tóm tắt cuộc họp:
                                        <ul class="summary-list">
                                            <li>Tiến độ dự án: 75%</li>
                                            <li>Vấn đề: Thiếu dữ liệu đầu vào</li>
                                            <li>Kế hoạch: Hoàn thành trong 2 tuần tới</li>
                                        </ul>
                                        <div class="audio-player">
                                            <i class="ph-fill ph-play-circle play-icon"></i>
                                            <div class="audio-progress"></div>
                                            <span class="time">05:24</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Input area -->
                                <div class="chat-input-area">
                                    <div class="input-actions-top">
                                        <span class="tag"><i class="ph ph-paperclip"></i> Tài liệu công ty</span>
                                        <span class="tag"><i class="ph ph-arrows-out-line-horizontal"></i> Quy trình làm
                                            việc</span>
                                    </div>
                                    <div class="input-box-wrapper">
                                        <i class="ph ph-plus-circle input-icon"></i>
                                        <div class="input-text">Hỏi bất kỳ điều gì...</div>
                                        <div class="audio-wave">
                                            <i class="ph-fill ph-microphone"></i>
                                            <div class="wave-bars active">
                                                <span></span><span></span><span></span><span></span><span></span><span></span>
                                            </div>
                                            <span class="time">00:12</span>
                                        </div>
                                        <button class="send-btn"><i class="ph-fill ph-paper-plane-right"></i></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 4: Target Audience -->
        <section class="audience container">
            <h2 class="section-title text-center">Đối tượng khách hàng phù hợp</h2>
            <p class="section-desc text-center">
                Giải pháp phù hợp với các tổ chức, doanh nghiệp có khối lượng tài liệu lớn và thường xuyên cần tra cứu
                thông tin.
            </p>

            <div class="audience-cards">
                <div class="a-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1541872703-74c5e44368f9?auto=format&fit=crop&w=500&q=80"
                            alt="Cơ quan hành chính">
                    </div>
                    <div class="a-content">
                        <h3>Cơ quan hành chính</h3>
                        <p>Quản lý và tra cứu nhanh các văn bản, quy định, hồ sơ hành chính.</p>
                    </div>
                </div>

                <div class="a-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1589829085413-56de8ae18c73?auto=format&fit=crop&w=500&q=80"
                            alt="Công ty luật">
                    </div>
                    <div class="a-content">
                        <h3>Công ty luật</h3>
                        <p>Tìm kiếm nhanh hồ sơ vụ việc, hợp đồng và tài liệu pháp lý.</p>
                    </div>
                </div>

                <div class="a-card">
                    <div class="img-wrapper">
                        <img src="https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?auto=format&fit=crop&w=500&q=80"
                            alt="Doanh nghiệp Logistics">
                    </div>
                    <div class="a-content">
                        <h3>Doanh nghiệp Logistics</h3>
                        <p>Quản lý chứng từ, quy trình vận hành và tài liệu nội bộ hiệu quả.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 5: Strategy -->
        <section class="strategy container">
            <div class="strategy-content">
                <div class="s-left">
                    <h2 class="section-title">Sẵn sàng đưa <span
                            style="color:#e60012;font-weight:700;">AI</span><br><span class="text-green">về doanh nghiệp
                            bạn?</span></h2>
                    <p>Đội ngũ <span style="color:#e60012;font-weight:700;">ROSA</span> tư vấn cấu hình máy trạm phù
                        hợp, lộ trình tích hợp platform AI và demo trực tiếp ROSA Chatbot.</p>
                    <h4 class="text-green uppercase">KẾT NỐI AI LOCAL VỚI CÁC ỨNG DỤNG AI ĐÃ CÓ SẴN</h4>
                    <div class="s-logos">
                        <div class="s-logo">
                            <img src="images/claude.png" alt="Claude Code" class="app-icon"><span>Claude Code</span>
                        </div>
                        <div class="s-logo">
                            <img src="images/openai-chatgpt.webp" alt="Claude Code" class="app-icon"><span>Open
                                AI</span>
                        </div>
                        <div class="s-logo">
                            <img src="images/hermes.png" alt="Claude Code" class="app-icon"><span>Hermes Agent</span>
                        </div>
                        <div class="s-logo">
                            <img src="images/openclaw.png" alt="Claude Code" class="app-icon"><span>OpenClaw</span>
                        </div>
                        <div class="s-logo">
                            <img src="images/logo-n8n.png" alt="Claude Code" class="app-icon"><span>n8n</span>
                        </div>
                        <div class="s-logo text-logo"><i class="ph ph-dots-three"
                                style="font-size: 24px; color: var(--primary);"></i><span>và nhiều<br>hơn nữa...</span>
                        </div>
                    </div>
                    <p class="s-desc">Tận dụng sức mạnh của các nền tảng hàng đầu thế giới,<br>kết hợp với AI Local để
                        mang lại giải pháp tối ưu cho doanh nghiệp.</p>
                </div>
                <div class="s-right">
                    <img src="images/ai-pc.png" alt="AI Strategy - Đứng trên vai người khổng lồ" class="human-glow">
                </div>
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="cta-banner container">
            <div class="cta-card">
                <div class="cta-left">
                    <h2>Sẵn sàng đưa AI về doanh nghiệp của bạn?</h2>
                    <p>Liên hệ với ROSA để được tư vấn giải pháp AI Local phù hợp nhất</p>
                </div>
                <div class="cta-right">
                    <a href="#" class="btn btn-primary">Liên hệ tư vấn ngay <i class="ph ph-arrow-right"></i></a>
                    <a href="#" class="btn btn-outline">Đặt lịch demo <i class="ph ph-arrow-right"></i></a>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
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
</body>

</html>