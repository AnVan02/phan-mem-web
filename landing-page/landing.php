    <?php
    $cssVersion = filemtime(__DIR__ . '/landing.css');
    $jsVersion = filemtime(__DIR__ . '/landing.js');
    ?>
    <!DOCTYPE html>
    <html lang="vi">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>ROSA - AI Local cho doanh nghiệp</title>
        <link rel="stylesheet" href="landing.css?v=<?php echo $cssVersion; ?>">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <script src="https://unpkg.com/@phosphor-icons/web"></script>
        <link rel="icon" href="images/rosa-icon.png" type="image/png">
        <script src="landing.js?v=<?php echo $jsVersion; ?>" defer></script>

    </head>

    <body>
        <!-- Navbar -->
        <header class="navbar">
            <div class="container nav-content">
                <div class="logo">
                    <img src="images/rosa.png" alt="ROSA Logo" class="logo-icon">
                </div>
                <nav class="nav-links">
                    <a href="landing.php">GIẢI PHÁP</a>
                    <a href="ROSA-AI-CONNECT.php">AI CONNECT</a>
                    <a href="ROSA-AI-WORKSPACE.php">AI WORKSPACE</a>
                    <a href="#">ỨNG DỤNG </a>
                    <a href="#">SẢN PHẨM</a>
                    <a href="#" class="btn btn-primary nav-cta">Liên hệ tư vấn</a>
                </nav>
                <a href="#" class="btn btn-primary nav-cta-desktop">Liên hệ tư vấn</a>
                <button class="nav-toggle" aria-label="Mở menu" aria-expanded="false">
                    <i class="ph ph-list"></i>
                </button>
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
                    <p class="hero-desc">Máy trạm AI để bàn nhỏ gọn, được thiết kế nhằm đáp ứng nhu cầu phát triển, thử nghiệm và triển khai các mô hình AI trên hạ tầng cục bộ (on-premises),
                        phù hợp cho môi trường văn phòng, trung tâm nghiên cứu và doanh nghiệp, đồng thời tăng cường khả năng bảo mật dữ liệu và tối ưu hiệu quả xử lý.</p>

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
                        <!-- <a href="https://www.asus.com/vn/networking-iot-servers/desktop-ai-supercomputer/ultra-small-ai-supercomputers/asus-ascent-gx10/"
                            class="btn btn-primary">Tìm hiểu ASUS GX10 <i class="ph ph-arrow-right"></i></a> -->
                        <a href="#" class="btn btn-primary">Nhận tư vấn giải pháp</a>
                    </div>
                </div>
                <div class="hero-right">
                    <!-- <div class="logos-top">
                        <img src="images/ASUS_Corporate_Logo.svg.webp" alt="ASUS" class="brand-logo asus-logo">
                        <div class="logo-divider"></div>
                        <img src="images/NVIDIA_logo_white.svg.webp" alt="NVIDIA" class="brand-logo nvidia-logo">
                    </div>

                    <h2 class="product-title">ASUS Ascent GX10</h2>
                    <p class="product-sub">Powered by NVIDIA® DGX Spark</p> -->

                    <div class="product-carousel">
                        <div class="carousel-viewport">
                            <div class="carousel-slide active" data-index="0">
                                <img src="images/ROSA-AI-Platform.png" alt="ROSA AI Platform" class="carousel-image">
                            </div>
                            <div class="carousel-slide" data-index="1">
                                <img src="images/banner.png" alt="ASUS Ascent GX10" class="carousel-image">
                            </div>
                            <div class="carousel-slide" data-index="2">
                                <img src="images/page4.png" alt="Sản phẩm ROSA AI" class="carousel-image">
                            </div>
                        </div>
                        <button type="button" class="carousel-arrow carousel-prev" aria-label="Ảnh trước">
                            <i class="ph ph-caret-left"></i>
                        </button>
                        <button type="button" class="carousel-arrow carousel-next" aria-label="Ảnh sau">
                            <i class="ph ph-caret-right"></i>
                        </button>
                        <div class="carousel-dots">
                            <button type="button" class="carousel-dot active" data-index="0" aria-label="Xem ảnh 1"></button>
                            <button type="button" class="carousel-dot" data-index="1" aria-label="Xem ảnh 2"></button>
                            <button type="button" class="carousel-dot" data-index="2" aria-label="Xem ảnh 3"></button>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Section 2: Platform AI -->
            <section class="platform container">
                <div class="badge-label">
                    <span class="number">2</span> ROSA AI Platform - Kết nối & Tự động hóa
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
                                <svg viewBox="0 0 560 460" width="100%" role="img"
                                    aria-label="Sơ đồ kết nối AI Local với các ứng dụng AI">
                                    <defs>
                                        <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
                                            <stop offset="0%" stop-color="#52d6c6" stop-opacity=".9" />
                                            <stop offset="100%" stop-color="#52d6c6" stop-opacity=".15" />
                                        </linearGradient>
                                        <clipPath id="clip-center">
                                            <circle cx="280" cy="220" r="44" />
                                        </clipPath>
                                        <clipPath id="clip-tl">
                                            <circle cx="90" cy="85" r="34" />
                                        </clipPath>
                                        <clipPath id="clip-tr">
                                            <circle cx="470" cy="85" r="34" />
                                        </clipPath>
                                        <clipPath id="clip-ml">
                                            <circle cx="40" cy="220" r="34" />
                                        </clipPath>
                                        <clipPath id="clip-mr">
                                            <circle cx="520" cy="220" r="34" />
                                        </clipPath>
                                        <clipPath id="clip-bl">
                                            <circle cx="90" cy="355" r="34" />
                                        </clipPath>
                                        <clipPath id="clip-br">
                                            <circle cx="470" cy="355" r="34" />
                                        </clipPath>
                                    </defs>

                                    <!-- connecting lines -->
                                    <g stroke="#52d6c6" stroke-opacity=".55" stroke-width="1.6" fill="none">
                                        <path d="M280,220 L90,85" stroke-dasharray="4 5">
                                            <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.6s" repeatCount="indefinite" />
                                        </path>
                                        <path d="M280,220 L470,85" stroke-dasharray="4 5">
                                            <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.8s" repeatCount="indefinite" />
                                        </path>
                                        <path d="M280,220 L40,220" stroke-dasharray="4 5">
                                            <animate attributeName="stroke-dashoffset" values="0;-18" dur="2.1s" repeatCount="indefinite" />
                                        </path>
                                        <path d="M280,220 L520,220" stroke-dasharray="4 5">
                                            <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.9s" repeatCount="indefinite" />
                                        </path>
                                        <path d="M280,220 L90,355" stroke-dasharray="4 5">
                                            <animate attributeName="stroke-dashoffset" values="0;-18" dur="2s" repeatCount="indefinite" />
                                        </path>
                                        <path d="M280,220 L470,355" stroke-dasharray="4 5">
                                            <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.7s" repeatCount="indefinite" />
                                        </path>
                                    </g>

                                    <!-- center node: AI Local -->
                                    <circle cx="280" cy="220" r="44" fill="#1d222d" stroke="#d89b4a" stroke-width="1.6" />
                                    <image href="images/pc.jpg" x="236" y="176" width="88" height="88"
                                        clip-path="url(#clip-center)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="280" y="282" text-anchor="middle" fill="#edeff3"
                                        font-family="'Montserrat', Arial, sans-serif" font-size="13" font-weight="600">ASUS Ascent</text>
                                    <text x="280" y="296" text-anchor="middle" fill="#9aa3b2"
                                        font-family="'Montserrat', Arial, sans-serif" font-size="9">GX10</text>

                                    <!-- outer nodes -->
                                    <g font-family="'Montserrat', Arial, sans-serif" font-size="11" fill="#edeff3">

                                        <!-- TL: Claude Code -->
                                        <circle cx="90" cy="85" r="40" fill="#1d222d" stroke="#52d6c6" stroke-width="1.2" />
                                        <image href="images/claude.png" x="56" y="51" width="68" height="68"
                                            clip-path="url(#clip-tl)" preserveAspectRatio="xMidYMid slice" />
                                        <text x="90" y="141" text-anchor="middle">Claude Code</text>

                                        <!-- TR: n8n -->
                                        <circle cx="470" cy="85" r="40" fill="#1d222d" stroke="#52d6c6" stroke-width="1.2" />
                                        <image href="images/logo-n8n.png" x="436" y="51" width="68" height="68"
                                            clip-path="url(#clip-tr)" preserveAspectRatio="xMidYMid slice" />
                                        <text x="470" y="141" text-anchor="middle">n8n</text>

                                        <!-- ML: Krita -->
                                        <circle cx="40" cy="220" r="40" fill="#1d222d" stroke="#52d6c6" stroke-width="1.2" />
                                        <image href="images/krita.svg" x="6" y="186" width="68" height="68"
                                            clip-path="url(#clip-ml)" preserveAspectRatio="xMidYMid slice" />
                                        <text x="40" y="276" text-anchor="middle">Krita</text>

                                        <!-- MR: Photoshop -->
                                        <circle cx="520" cy="220" r="40" fill="#1d222d" stroke="#52d6c6" stroke-width="1.2" />
                                        <image href="https://img.icons8.com/color/48/adobe-photoshop--v1.png" x="486" y="186" width="68" height="68"
                                            clip-path="url(#clip-mr)" preserveAspectRatio="xMidYMid slice" />
                                        <text x="520" y="276" text-anchor="middle">Photoshop</text>

                                        <!-- BL: Hermes -->
                                        <circle cx="90" cy="355" r="40" fill="#1d222d" stroke="#52d6c6" stroke-width="1.2" />
                                        <image href="images/hermes.png" x="56" y="321" width="68" height="68"
                                            clip-path="url(#clip-bl)" preserveAspectRatio="xMidYMid slice" />
                                        <text x="90" y="411" text-anchor="middle">Hermes</text>

                                        <!-- BR: OpenClaw -->
                                        <circle cx="470" cy="355" r="40" fill="#1d222d" stroke="#52d6c6" stroke-width="1.2" />
                                        <image href="images/openclaw.png" x="436" y="321" width="68" height="68"
                                            clip-path="url(#clip-br)" preserveAspectRatio="xMidYMid slice" />
                                        <text x="470" y="411" text-anchor="middle">OpenClaw</text>

                                    </g>
                                </svg>
                            </div>
                        </div>
                    </div>


                    <div class="card n8n-card n8n-card-wide">
                        <div class="n8n-card-col n8n-card-left">
                            <h3>Tích hợp n8n – Tự động hóa quy trình</h3>
                            <p>Tự động hóa các quy trình doanh nghiệp với n8n</p>
                            <div class="logos-top">
                                <img src="images/logo-white.svg" alt="ASUS" class="brand-logo asus-logo">
                            </div>
                            <ul class="n8n-features">
                                <li><i class="ph-fill ph-check-circle"></i> Chăm sóc khách hàng tự động trên mạng xã hội</li>
                                <li><i class="ph-fill ph-check-circle"></i> Tự động gửi tin nhắn quảng cáo</li>
                                <li><i class="ph-fill ph-check-circle"></i> Lọc / phân loại email</li>
                                <li><i class="ph-fill ph-check-circle"></i> Đồng bộ dữ liệu giữa các hệ thống </li>
                                <li><i class="ph-fill ph-check-circle"></i> Tạo báo cáo tự động theo lịch</li>
                                <li><i class="ph-fill ph-check-circle"></i> Kích hoạt quy trình theo sự kiện </li>
                                <li><i class="ph-fill ph-check-circle"></i> Và nhiều ứng dụng khác của N8n</li>
                            </ul>
                        </div>
                        <div class="n8n-card-col n8n-card-right">
                            <div class="n8n-flow-title">Ví dụ quy trình tự động</div>
                            <div class="n8n-flow">
                                <div class="n8n-flow-step">
                                    <div class="n8n-flow-icon icon-green"><i class="ph-fill ph-chat-teardrop-text"></i></div>
                                    <div class="n8n-flow-text">
                                        <strong>1. Nhận tin nhắn</strong>
                                        <span>Từ Zalo / Facebook / Website</span>
                                    </div>
                                </div>
                                <div class="n8n-flow-connector"><i class="ph-fill ph-plus-circle"></i></div>
                                <div class="n8n-flow-step">
                                    <div class="n8n-flow-icon icon-purple"><i class="ph-fill ph-funnel"></i></div>
                                    <div class="n8n-flow-text">
                                        <strong>2. Phân loại &amp; xử lý</strong>
                                        <span>AI phân loại &amp; trích xuất thông tin</span>
                                    </div>
                                </div>
                                <div class="n8n-flow-connector"><i class="ph-fill ph-plus-circle"></i></div>
                                <div class="n8n-flow-step">
                                    <div class="n8n-flow-icon icon-teal"><i class="ph-fill ph-database"></i></div>
                                    <div class="n8n-flow-text">
                                        <strong>3. Lưu dữ liệu</strong>
                                        <span>Lưu vào Google Sheet / CRM</span>
                                    </div>
                                </div>
                                <div class="n8n-flow-connector"><i class="ph-fill ph-plus-circle"></i></div>
                                <div class="n8n-flow-step">
                                    <div class="n8n-flow-icon icon-orange"><i class="ph-fill ph-envelope-simple"></i></div>
                                    <div class="n8n-flow-text">
                                        <strong>4. Gửi phản hồi</strong>
                                        <span>Tự động gửi email / tin nhắn</span>
                                    </div>
                                </div>
                                <div class="n8n-flow-connector"><i class="ph-fill ph-plus-circle"></i></div>
                                <div class="n8n-flow-step">
                                    <div class="n8n-flow-icon icon-blue"><i class="ph-fill ph-chart-bar"></i></div>
                                    <div class="n8n-flow-text">
                                        <strong>5. Báo cáo &amp; theo dõi</strong>
                                        <span>Tổng hợp &amp; gửi báo cáo định kỳ</span>
                                    </div>
                                </div>
                            </div>
                        </div>
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

                        <!-- CTA -->
                        <div class="chatbot-actions">
                            <a href="https://rosacomputer.vn/landing-page/ROSA-AI-WORKSPACE.php" class="btn-primary">
                                <i class="ph ph-arrow-square-out"></i>
                                Xem chi tiết giải pháp
                            </a>
                        </div>


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
                        <div class="chatbot-mockup chatbot-mockup-image">
                            <img src="images/chat_ai.png" alt="Giao diện chatbot ROSA AI Workspace" class="chatbot-screenshot">
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
                        <div class="a-top">
                            <div class="icon-circle icon-blue"><i class="fa-solid fa-landmark"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2942/2942076.png" alt="Cơ quan hành chính">
                        </div>
                        <div class="a-content">
                            <h3>Cơ quan hành chính</h3>
                            <span class="underline underline-blue"></span>
                            <p>Quản lý và tra cứu nhanh các văn bản, quy định, hồ sơ hành chính.</p>
                        </div>
                        <div class="check-badge check-blue"><i class="fa-solid fa-check"></i></div>
                    </div>

                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-green"><i class="fa-solid fa-scale-balanced"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/3068/3068757.png" alt="Công ty luật">
                        </div>
                        <div class="a-content">
                            <h3>Công ty luật</h3>
                            <span class="underline underline-green"></span>
                            <p>Tìm kiếm nhanh hồ sơ vụ việc, hợp đồng và tài liệu pháp lý.</p>
                        </div>
                        <div class="check-badge check-green"><i class="fa-solid fa-check"></i></div>
                    </div>

                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-lightblue"><i class="fa-solid fa-hospital"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2966/2966327.png" alt="Bệnh viện">
                        </div>
                        <div class="a-content">
                            <h3>Bệnh viện</h3>
                            <span class="underline underline-lightblue"></span>
                            <p>Quản lý hồ sơ bệnh án, quy trình chuyên môn và tài liệu nội bộ.</p>
                        </div>
                        <div class="check-badge check-lightblue"><i class="fa-solid fa-check"></i></div>
                    </div>

                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-purple"><i class="fa-solid fa-building-columns"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830284.png" alt="Ngân hàng">
                        </div>
                        <div class="a-content">
                            <h3>Ngân hàng</h3>
                            <span class="underline underline-purple"></span>
                            <p>Quản lý hồ sơ khách hàng, hợp đồng, tài liệu nghiệp vụ và tuân thủ.</p>
                        </div>
                        <div class="check-badge check-purple"><i class="fa-solid fa-check"></i></div>
                    </div>

                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-orange"><i class="fa-solid fa-truck"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830289.png" alt="Doanh nghiệp Logistics">
                        </div>
                        <div class="a-content">
                            <h3>Doanh nghiệp Logistics</h3>
                            <span class="underline underline-orange"></span>
                            <p>Quản lý chứng từ, quy trình vận hành và tài liệu nội bộ hiệu quả.</p>
                        </div>
                        <div class="check-badge check-orange"><i class="fa-solid fa-check"></i></div>
                    </div>

                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-teal"><i class="fa-solid fa-industry"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/1584/1584892.png" alt="Doanh nghiệp sản xuất">
                        </div>
                        <div class="a-content">
                            <h3>Doanh nghiệp sản xuất</h3>
                            <span class="underline underline-teal"></span>
                            <p>Quản lý tài liệu kỹ thuật, quy trình sản xuất và hồ sơ chất lượng.</p>
                        </div>
                        <div class="check-badge check-teal"><i class="fa-solid fa-check"></i></div>
                    </div>

                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-blue2"><i class="fa-solid fa-city"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/1670/1670080.png" alt="Doanh nghiệp bất động sản">
                        </div>
                        <div class="a-content">
                            <h3>Doanh nghiệp bất động sản</h3>
                            <span class="underline underline-blue2"></span>
                            <p>Quản lý hồ sơ dự án, hợp đồng, pháp lý và tài liệu liên quan bất động sản.</p>
                        </div>
                        <div class="check-badge check-blue2"><i class="fa-solid fa-check"></i></div>
                    </div>

                    <div class="a-card">
                        <div class="a-top">
                            <div class="icon-circle icon-yellow"><i class="fa-solid fa-chart-line"></i></div>
                            <img class="a-illustration" src="https://cdn-icons-png.flaticon.com/512/2830/2830284.png" alt="Chứng khoán">
                        </div>
                        <div class="a-content">
                            <h3>Chứng khoán</h3>
                            <span class="underline underline-yellow"></span>
                            <p>Quản lý hồ sơ khách hàng, báo cáo, tài liệu phân tích và giao dịch.</p>
                        </div>
                        <div class="check-badge check-yellow"><i class="fa-solid fa-check"></i></div>
                    </div>

                </div>

                <!-- Bottom feature strip -->
                <div class="audience-features">
                    <div class="af-item">
                        <div class="af-icon af-icon-blue"><img width="38" height="38" src="https://img.icons8.com/color/48/search--v1.png" alt="search--v1" /></div>
                        <div class="af-text">
                            <h4>Tìm kiếm nhanh chóng</h4>
                            <p>Tiết kiệm thời gian tra cứu, nâng cao hiệu quả công việc.</p>
                        </div>
                    </div>
                    <div class="af-item">
                        <div class="af-icon af-icon-green"><i class="fa-solid fa-shield-halved"></i></div>
                        <div class="af-text">
                            <h4>Bảo mật an toàn</h4>
                            <p>Kiểm soát quyền truy cập, đảm bảo an toàn dữ liệu.</p>
                        </div>
                    </div>
                    <div class="af-item">
                        <div class="af-icon af-icon-purple"><i class="fa-solid fa-layer-group"></i></div>
                        <div class="af-text">
                            <h4>Quản lý tập trung</h4>
                            <p>Lưu trữ tập trung, dễ dàng quản lý và khai thác.</p>
                        </div>
                    </div>
                    <div class="af-item">
                        <div class="af-icon af-icon-orange"><i class="fa-solid fa-chart-pie"></i></div>
                        <div class="af-text">
                            <h4>Tối ưu chi phí</h4>
                            <p>Giảm chi phí lưu trữ, in ấn và quản lý tài liệu.</p>
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
                        <!-- CTA -->
                        <div class="chatbot-actions">
                            <a href="https://rosacomputer.vn/landing-page/ROSA-AI-CONNECT.php" class="btn-primary">
                                <i class="ph ph-arrow-square-out"></i>
                                Xem chi tiết giải pháp
                            </a>
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
            <!-- Modal Liên Hệ -->
            <div id="contactModal" class="modal">
                <div class="modal-overlay"></div>
                <div class="modal-content modal-content-consult">
                    <button class="close-modal">&times;</button>
                    <div class="modal-header">
                        <h2>Đăng ký tư vấn giải pháp AI</h2>
                        <p>Để lại thông tin, đội ngũ ROSA sẽ liên hệ lại với bạn trong vòng 24h.</p>
                    </div>
                    <form id="consultationForm" class="modal-form">
                        <div class="form-group">
                            <label for="fullname">Họ và tên</label>
                            <input type="text" id="fullname" name="fullname" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Số điện thoại</label>
                            <input type="tel" id="phone" name="phone" placeholder="0901 234 567" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Email công việc</label>
                            <input type="email" id="email" name="email" placeholder="name@company.com" required>
                        </div>
                        <div class="form-group">
                            <label for="company">Tên doanh nghiệp</label>
                            <input type="text" id="company" name="company" placeholder="Công ty ABC">
                        </div>
                        <div class="form-group">
                            <label for="message">Nhu cầu của bạn</label>
                            <textarea id="message" name="message" rows="3" placeholder="Ví dụ: Tôi muốn tư vấn về Chatbot nội bộ..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-full">Gửi yêu cầu tư vấn <i class="ph ph-arrow-right"></i></button>
                        <button type="submit" class="btn btn-primary w-full">Liên hệ zalo <i class="ph ph-arrow-right"></i></button>
                    </form>
                    <div class="form-success" id="formSuccess">
                        <div class="form-success-icon"><i class="ph-fill ph-check-circle"></i></div>
                        <h3>Cảm ơn bạn!</h3>
                        <p>ROSA đã nhận được thông tin và sẽ liên hệ sớm.</p>
                        <button type="button" class="btn btn-primary form-success-close">Đóng</button>
                    </div>
                </div>
            </div>
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
                    <a href="https://www.facebook.com/rosaaicomputer/"><i class="ph ph-facebook-logo"></i></a>
                    <a href="https://www.linkedin.com/in/rosa-ai-computer-20980b352/"><i class="ph ph-linkedin-logo"></i></a>
                    <a href="https://www.youtube.com/@rosaaicomputer"><i class="ph ph-youtube-logo"></i></a>
                </div>
            </div>
        </footer>
    </body>

    </html>