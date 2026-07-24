<?php
$GLOBALS['page_specific_title'] = 'ROSA - AI Local cho doanh nghiệp | Máy trạm AI On-Premises';
$GLOBALS['page_specific_description'] = 'Giải pháp AI Local toàn diện cho doanh nghiệp Việt: máy trạm AI on-premises, ROSA AI Workspace (chatbot nội bộ), ROSA AI Connect kết nối Claude Code, n8n, tự động hóa quy trình. Bảo mật dữ liệu tuyệt đối, không phụ thuộc cloud.';
$GLOBALS['page_specific_keywords'] = 'AI local, máy trạm AI, AI on-premises, chatbot nội bộ doanh nghiệp, tự động hóa AI, n8n automation, ROSA AI, AI cho doanh nghiệp Việt Nam';

$cssVersion = filemtime(__DIR__ . '/landing.css');
$jsVersion = filemtime(__DIR__ . '/landing.js');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo htmlspecialchars($GLOBALS['page_specific_title']); ?></title>
    <meta name="description" content="<?php echo htmlspecialchars($GLOBALS['page_specific_description']); ?>">
    <meta name="keywords" content="<?php echo htmlspecialchars($GLOBALS['page_specific_keywords']); ?>">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="https://rosacomputer.vn/rosa-ai-platform/nen-tang-ai-local.php">

    <!-- Open Graph -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?php echo htmlspecialchars($GLOBALS['page_specific_title']); ?>">
    <meta property="og:description" content="<?php echo htmlspecialchars($GLOBALS['page_specific_description']); ?>">
    <meta property="og:image" content="https://rosacomputer.vn/images/rosa.png">
    <meta property="og:url" content="https://rosacomputer.vn/rosa-ai-platform/nen-tang-ai-local.php">
    <meta property="og:locale" content="vi_VN">
    <meta name="twitter:card" content="summary_large_image">

    <link rel="stylesheet" href="/css/nen-tang-ai-local.css?v=<?php echo $cssVersion; ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" href="images/rosa-icon.png" type="image/png">
    <script src="nen-tang-ai-local.js?v=<?php echo $jsVersion; ?>" defer></script>
</head>

<body>
    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <a href="https://rosacomputer.vn/">
                    <img src="images/rosa.png" alt="ROSA Logo" class="logo-icon">
                </a>
            </div>
            <nav class="nav-links">
                <a href="nen-tang-ai-local.php">GIẢI PHÁP</a>
                <a href="rosa-ai-connect.php">ROSA AI CONNECT</a>
                <a href="rosa-ai-workspace.php">ROSA AI WORKSPACE</a>
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
                <p class="hero-desc">Máy trạm AI để bàn nhỏ gọn, được thiết kế nhằm đáp ứng nhu cầu phát triển, thử
                    nghiệm và triển khai các mô hình AI trên hạ tầng cục bộ (on-premises),
                    phù hợp cho môi trường văn phòng, trung tâm nghiên cứu và doanh nghiệp, đồng thời tăng cường khả
                    năng bảo mật dữ liệu và tối ưu hiệu quả xử lý.</p>

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
                            <img src="images/w692.png" alt="ASUS Ascent GX10" class="carousel-image">
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
                        <button type="button" class="carousel-dot active" data-index="0"
                            aria-label="Xem ảnh 1"></button>
                        <button type="button" class="carousel-dot" data-index="1" aria-label="Xem ảnh 2"></button>
                        <button type="button" class="carousel-dot" data-index="2" aria-label="Xem ảnh 3"></button>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section 2: Platform AI -->
        <section class="platform container">
            <div class="badge-label">
                <span class="number">2</span> ROSA AI PLATFORM - Kết nối & Tự động hóa
            </div>
            <h2 class="section-title text-center">Kết nối AI Local với các ứng dụng hàng đầu</h2>
            <p class="section-desc text-center">Thay vì tốn tiền thuê cloud, doanh nghiệp dùng chính AI chạy trên máy
                trạm<br>để vận hành các công cụ AI nổi tiếng và tự động hóa quy trình.</p>

            <div class="platform-cards">
                <div class="card app-card">
                    <h3>ROSA AI CONNECT</h3>
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
                                        <circle cx="280" cy="220" r="66" />
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
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.6s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M280,220 L470,85" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.8s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M280,220 L40,220" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="2.1s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M280,220 L520,220" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.9s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M280,220 L90,355" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="2s"
                                            repeatCount="indefinite" />
                                    </path>
                                    <path d="M280,220 L470,355" stroke-dasharray="4 5">
                                        <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.7s"
                                            repeatCount="indefinite" />
                                    </path>
                                </g>

                                <!-- center node: AI Local -->
                                <circle cx="280" cy="220" r="66" fill="#1d222d" stroke="#d89b4a" stroke-width="1.6" />
                                <image href="images/ROSA-AI-Platform.png" x="214" y="154" width="130" height="130"
                                    clip-path="url(#clip-center)" preserveAspectRatio="xMidYMid meet" />
                                <text x="280" y="306" text-anchor="middle" fill="#edeff3"
                                    font-family="'Montserrat', Arial, sans-serif" font-size="14" font-weight="600">ROSA
                                    AI READY
                                </text>

                                <!-- outer nodes -->
                                <g font-family="'Montserrat', Arial, sans-serif" font-size="11" fill="#edeff3">

                                    <!-- TL: Claude Code -->
                                    <circle cx="90" cy="85" r="40" fill="#1d222d" stroke="#52d6c6" stroke-width="1.2" />
                                    <image href="images/claude.png" x="56" y="51" width="68" height="68"
                                        clip-path="url(#clip-tl)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="90" y="141" text-anchor="middle">Claude Code</text>

                                    <!-- TR: n8n -->
                                    <circle cx="470" cy="85" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="images/logo-n8n.png" x="436" y="51" width="68" height="68"
                                        clip-path="url(#clip-tr)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="470" y="141" text-anchor="middle">n8n</text>

                                    <!-- ML: Krita -->
                                    <circle cx="40" cy="220" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="images/krita.svg" x="6" y="186" width="68" height="68"
                                        clip-path="url(#clip-ml)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="40" y="276" text-anchor="middle">Krita</text>

                                    <!-- MR: Photoshop -->
                                    <circle cx="520" cy="220" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="https://img.icons8.com/color/48/adobe-photoshop--v1.png" x="486"
                                        y="186" width="68" height="68" clip-path="url(#clip-mr)"
                                        preserveAspectRatio="xMidYMid slice" />
                                    <text x="520" y="276" text-anchor="middle">Photoshop</text>

                                    <!-- BL: Hermes -->
                                    <circle cx="90" cy="355" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
                                    <image href="images/hermes.png" x="56" y="321" width="68" height="68"
                                        clip-path="url(#clip-bl)" preserveAspectRatio="xMidYMid slice" />
                                    <text x="90" y="411" text-anchor="middle">Hermes</text>

                                    <!-- BR: OpenClaw -->
                                    <circle cx="470" cy="355" r="40" fill="#1d222d" stroke="#52d6c6"
                                        stroke-width="1.2" />
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
                        <h3>ROSA AI WORKSPACE</h3>
                        <p>Trợ lý ảo AI truy vấn thông tin & tự động hóa quy trình</p>
                        <div class="logos-top">
                            <img src="images/ROSA-AI-ASSISTANT.png" alt="ROSA AI ASSISTANT"
                                class="brand-logo rosa-logo">
                            <img src="images/logo-white.svg" alt="ASUS" class="brand-logo asus-logo">
                        </div>
                        <ul class="n8n-features">
                            <li><i class="ph-fill ph-check-circle"></i> Chăm sóc khách hàng tự động trên mạng xã hội
                            </li>
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
                                <div class="n8n-flow-icon icon-green"><i class="ph-fill ph-chat-teardrop-text"></i>
                                </div>
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
                <span class="number">3</span> ROSA AI WORKSPACE
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
                        <!--<a href="#" class="btn-primary">-->
                        <!--    <i class="ph ph-rocket-launch"></i>-->
                        <!--    Dùng thử ngay-->
                        <!--</a>-->

                        <a href="https://rosacomputer.vn/rosa-ai-platform/rosa-ai-workspace.php"
                            class="btn btn-primary">
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
                        <img src="images/chat_ai.png" alt="Giao diện chatbot ROSA AI Workspace"
                            class="chatbot-screenshot">
                    </div>
                </div>
            </div>
        </section>

        <section class="platform container">
            <div class="card n8n-card n8n-card-wide">
                <div class="n8n-card-col n8n-card-left">
                    <h3>N8N AUTOMATION</h3>
                    <p>"Tay và chân" của ROSA AI Workspace - tự động hóa quy trình và đưa AI đến mọi kênh làm việc.</p>

                    <ul class="n8n-features">
                        <li><i class="ph-fill ph-check-circle"></i> Đưa trợ lý AI lên Website, Facebook, Zalo, Telegram
                            và nhiều nền tảng khác</li>
                        <li><i class="ph-fill ph-check-circle"></i> Tự động chăm sóc và phản hồi khách hàng 24/7</li>
                        <li><i class="ph-fill ph-check-circle"></i> Kết nối CRM, Google Sheets, Email, ERP và hàng trăm
                            ứng dụng khác</li>
                        <li><i class="ph-fill ph-check-circle"></i> Đồng bộ dữ liệu giữa các hệ thống mà không cần thao
                            tác thủ công</li>
                        <li><i class="ph-fill ph-check-circle"></i> Tự động xử lý quy trình: duyệt yêu cầu, gửi thông
                            báo, tạo báo cáo theo lịch</li>
                        <li><i class="ph-fill ph-check-circle"></i> Kích hoạt quy trình theo sự kiện và giảm tối đa công
                            việc lặp lại</li>
                        <li><i class="ph-fill ph-check-circle"></i> Hoạt động cùng Trợ lý AI nội bộ để doanh nghiệp vận
                            hành nhanh hơn và hiệu quả hơn</li>
                    </ul>
                </div>

                <div class="n8n-card-col n8n-card-right">
                    <div class="chatbot-mockup chatbot-mockup-image">
                        <img src="images/N8N-AUTOMATION.png" alt="ROSA AI Workspace - n8n Automation"
                            class="chatbot-screenshot">
                    </div>
                </div>
            </div>
        </section>


        <!-- Bottom feature strip -->
        <div class="audience-features">
            <div class="af-item">
                <div class="af-icon af-icon-blue"><img width="38" height="38"
                        src="https://img.icons8.com/color/48/search--v1.png" alt="search--v1" /></div>
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
        <!-- Section 5: Strategy -->

        <section class="strategy container">
            <div class="strategy-content">
                <div class="s-left">
                    <div class="badge-label">
                        <span class="number">4</span> ROSA AI CONNECT
                    </div>
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
                        <!--<a href="#" class="btn-primary">-->
                        <!--    <i class="ph ph-rocket-launch"></i>-->
                        <!--    Dùng thử ngay-->
                        <!--</a>-->

                        <a href="https://rosacomputer.vn/rosa-ai-platform/rosa-ai-connect.php" class="btn-outline">
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


        <!-- Section 5: Strategy -->

        <section class="strategy container">
            <div class="strategy-content">
                <div class="s-left strategy-left-content">
                    <div class="badge-label">
                        <span class="number">5</span> PHÂN TÍCH HIỂU QUẢ ĐẦU TƯ
                    </div>
                    <div class="thesis">Trả phí AI Cloud <span class="hi">mãi mãi</span>, hay đầu tư một lần để <span
                            class="hi">sở
                            hữu AI vĩnh viễn</span>?</div>
                    <p class="subtext">
                        Một doanh nghiệp dùng <b>AI Cloud</b> cho đội ngũ phát triển, marketing và vận hành đang trả
                        <span class="redtag">10–15 triệu đồng/tháng</span> — chỉ riêng chi phí token, và khoản này lặp
                        lại vô thời
                        hạn.
                        Nếu thay bằng <b>ROSA AI Platform</b> kết hợp hạ tầng phần cứng AI nội bộ, tổng chi phí khoảng
                        <span class="bluetag">180 triệu đồng</span> — trả một lần duy nhất, dùng vĩnh viễn, không phát
                        sinh phí theo
                        tháng.
                    </p>
                </div>
            </div>

            <div class="ai-pricing-section">
                <div class="ai-pricing-header">
                    <div class="line"></div>
                    <div class="dot"></div>
                    <h2>GIẢI PHÁP AI CLOUD TRIỂN KHAI</h2>
                    <div class="dot"></div>
                    <div class="line"></div>
                </div>

                <div class="ai-pricing-grid">
                    <!-- Sidebar -->
                    <div class="pricing-sidebar">
                        <div class="sidebar-item">
                            <i class="ph ph-squares-four"></i> Nhóm ngành
                        </div>
                        <div class="sidebar-item">
                            <i class="ph ph-users"></i> Quy mô
                        </div>
                        <div class="sidebar-item">
                            <i class="ph ph-package"></i>Gói AI
                        </div>
                        <div class="sidebar-item">
                            <i class="ph ph-currency-circle-dollar"></i> Chi phí AI Cloud
                        </div>
                        <div class="sidebar-item">
                            <i class="ph ph-chart-line-up"></i> Hiệu quả kỳ vọng
                        </div>
                        <div class="sidebar-item">
                            <i class="ph ph-clock"></i> Thời gian thu hồi vốn và đầu tư
                        </div>
                    </div>

                    <!-- Cards -->
                    <!-- Card 1 -->
                    <div class="pricing-card card-green">
                        <div class="card-cell header-cell">
                            <div class="icon-wrap"><img src="https://img.icons8.com/color/40/developer--v1.png"></div>
                            <div class="title-wrap">
                                <h3>Lập trình viên</h3>
                                <p>Xây dựng giải pháp bán hàng thông minh với trải nghiệm cá nhân hóa</p>
                            </div>
                        </div>
                        <div class="card-cell" data-label="Quy mô">
                            <i class="ph-fill ph-users"></i> 5 – 10 người
                        </div>

                        <div class="card-cell" data-label="Gói AI ">
                            <i class="ph-fill ph-users"></i>Claude Max / Codex Pro / Cursor Pro
                        </div>

                        <div class="card-cell price-cell" data-label="Chi phí AI Cloud">
                            <div class="price-info">
                                <div class="price">15 - 50 Triệu VNĐ/ tháng</div>
                            </div>
                        </div>

                        <div class="card-cell" data-label="Hiệu quả kỳ vọng">
                            <i class="ph-fill ph-chart-line-up"></i>Đầu tư một lần ~180 triệu, không phát sinh chi phí
                            token
                        </div>

                        <div class="price-time " data-label="Thời gian thu hồi vốn">
                            <i class="ph ph-clock"></i> &lt; 12 tháng
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div class="pricing-card card-purple">
                        <div class="card-cell header-cell">
                            <div class="icon-wrap"><img
                                    src="https://img.icons8.com/external-smashingstocks-outline-color-smashing-stocks/40/external-digital-marketing-seo-and-development-smashingstocks-outline-color-smashing-stocks.png">
                            </div>
                            <div class="title-wrap">
                                <h3>Marketing & Thiết kế</h3>
                                <p>Sáng tạo chiến lược truyền thông và thiết kế, tối ưu vận hành, nâng cao hiệu quả kinh
                                    doanh</p>
                            </div>
                        </div>
                        <div class="card-cell" data-label="Quy mô">
                            <i class="ph-fill ph-users"></i> 10 – 20 người
                        </div>

                        <div class="card-cell" data-label="Gói AI ">
                            <i class="ph-fill ph-users"></i> GPT Image, Photoshop, ComfyUI API, Video AI (King AI, VEO,
                            SORA)
                        </div>

                        <div class="card-cell price-cell" data-label="Chi phí AI Cloud">
                            <div class="price-info">
                                <div class="price">10 - 20 Triệu VNĐ/ tháng</div>

                            </div>

                        </div>

                        <div class="card-cell" data-label="Hiệu quả kỳ vọng">
                            <i class="ph-fill ph-chart-bar"></i>Tạo ảnh, video AI không giới hạn trên hạ tầng nội bộ
                        </div>

                        <div class="price-time " data-label="Thời gian thu hồi vốn">
                            <i class="ph ph-clock"></i> &lt; 18 tháng
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div class="pricing-card card-orange">
                        <div class="card-cell header-cell">
                            <div class="icon-wrap"><img src="https://img.icons8.com/color/30/city-buildings.png"></div>
                            <div class="title-wrap">
                                <h3>Doanh nghiệp</h3>
                                <p>Tối ưu hóa quy trình vận hành, nâng cao năng lực cạnh tranh và thúc đẩy tăng trưởng
                                    bền vững.</p>
                            </div>
                        </div>
                        <div class="card-cell" data-label="Quy mô">
                            <i class="ph-fill ph-users"></i> 20 – 50+ người
                        </div>
                        <div class="card-cell" data-label="Gói AI ">
                            <i class="ph-fill ph-users"></i> Chatbot, RAG, Automation đa phòng ban hoặc 1 hoặc 2 nhân sự
                            CSKH trực 24/7, nhân viên nhân sự tổng hợp báo cáo...
                        </div>
                        <div class="card-cell price-cell" data-label="Chi phí AI Cloud">
                            <div class="price-info">
                                <div class="price">20 - 30 Triệu VNĐ/ tháng</div>
                            </div>
                        </div>
                        <div class="card-cell" data-label="Hiệu quả kỳ vọng">
                            <i class="ph-fill ph-target"></i>AI dùng chung toàn doanh nghiệp giúp tư vấn CSKH 24/7, Ghi
                            âm và tóm tắt cuộc, không phát sinh chi phí theo ngườidùng hoặc token.
                        </div>
                        <div class="price-time " data-label="Thời gian thu hồi vốn và đầu tư ">
                            <i class="ph ph-clock"></i> &lt; 9 tháng
                        </div>
                    </div>
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
            <div class="modal-content">
                <button class="close-modal">&times;</button>
                <div class="modal-header">
                    <h2>Đăng ký tư vấn giải pháp AI</h2>
                    <p>Để lại thông tin, đội ngũ ROSA sẽ liên hệ lại với bạn trong vòng 24h.</p>
                </div>
                <form id="consultationForm" class="modal-form">
                    <div class="form-group">
                        <label for="fullname">Họ và tên *</label>
                        <input type="text" id="fullname" name="fullname" placeholder="Nguyễn Văn A" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Số điện thoại *</label>
                        <input type="tel" id="phone" name="phone" placeholder="0901 234 567" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email công việc *</label>
                        <input type="email" id="email" name="email" placeholder="name@company.com" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Tên doanh nghiệp</label>
                        <input type="text" id="company" name="company" placeholder="Công ty ABC">
                    </div>
                    <div class="form-group">
                        <label for="message">Nhu cầu của bạn</label>
                        <textarea id="message" name="message" rows="3"
                            placeholder="Ví dụ: Tôi muốn tư vấn về Chatbot nội bộ..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Gửi yêu cầu tư vấn</button>
                </form>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="rosa-footer" role="contentinfo">
        <div class="wrapper">
            <div class="footer-main">
                <!-- Logo và Mạng xã hội -->
                <div class="footer-column">
                    <div class="logo_footer">
                        <a href="/"><img src="/assets/images/rosa.webp" alt="Logo ROSA AI Computer"></a>
                    </div>
                    <p class="company-name">Công ty TNHH Điện tử và Tin học Toàn Việt</p>
                    <p class="tax-code">MST: 3700491951</p>
                    <p class="company-name ">Mạng xã hội</p>
                    <div class="social-icons">
                        <a href="https://www.facebook.com/people/ROSA-AI-Computer/61559427752479/" target="_blank"
                            title="Facebook" style="background: #1877F2; font-size: 24px">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="https://www.linkedin.com/in/rosa-ai-computer-20980b352/" target="_blank"
                            title="LinkedIn" style="background: #0A66C2; font-size: 24px">
                            <i class="fab fa-linkedin"></i>
                        </a>
                        <a href="https://www.youtube.com/@rosaaicomputer" target="_blank" title="YouTube"
                            style="background: #FF0000; font-size: 24px">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <!--<a href="https://zalo.me/909749126673606301" target="_blank" title="Zalo" style="background: #007FFF; font-size: 24px; color: white; padding: 8px 12px; border-radius: 5px; text-decoration: none;">-->
                        <!--    <i class="fab fa-zalo"></i> Zalo-->
                        <!--</a>-->

                    </div>
                </div>

                <!-- Chính sách  -->
                <div class="footer-column" role="navigation" aria-label="Chính sách">
                    <p class="company-name" style="font-size:22px;font-weight:bold">Chính sách</p>
                    <ul>
                        <li><a href="/DSDL.php">Đại lý</a></li>
                        <li><a href="/thong-tin-cong-ty.php">Profile công ty</a></li>
                        <li><a href="https://rosacomputer.vn/pages/main/index.php?page=products&brand_id=4">Sản phẩm</a>
                        </li>
                        <li><a href="/chinh-sach-bao-hanh.php">Bảo hành</a></li>
                        <li><a href="/chinh-sach-bao-mat.php">Bảo mật</a></li>
                    </ul>
                    <a href="http://online.gov.vn/Website/chi-tiet-135455" target="_blank">
                        <img src="https://rosacomputer.vn/image/BCT.png" alt="Bộ Công Thương"
                            style="width:160px; height:auto;float: left">
                    </a>
                </div>

                <!-- Chương trình -->
                <div class="footer-column" role="navigation" aria-label="Chương trình ROSA">
                    <p class="company-name" style="font-size:22px;font-weight:bold">Chương trình ROSA</p>
                    <ul>
                        <li><a href="/courses/python-course.php">Python cơ bản</a></li>
                        <li><a href="/courses/yolo-course.php">Thị giác máy tính</a></li>
                        <li><a href="/ROSA-SW.php">Ứng dụng ROSA</a></li>
                        <li><a href="/smb/Nextcloud.php">Quản trị doanh nghiệp</a></li>
                        <li><a href="/palit.php">Chương trình Palit</a></li>
                    </ul>
                    <div class="stats">
                        <?php
                        $count_file = "total_count.txt";
                        $visitor_file = "visitor_count.txt";
                        $user_ip = $_SERVER['REMOTE_ADDR'];

                        $total_count_default = 88888;
                        $total_count_real = file_exists($count_file) ? (int)file_get_contents($count_file) : 1;
                        $total_count_display = $total_count_default + $total_count_real;

                        $visitor_ips = file_exists($visitor_file) ? file($visitor_file, FILE_IGNORE_NEW_LINES) : [];

                        if (!in_array($user_ip, $visitor_ips)) {
                            $visitor_ips[] = $user_ip;
                            file_put_contents($visitor_file, implode(PHP_EOL, $visitor_ips) . PHP_EOL);
                        }

                        $total_count_real++;
                        file_put_contents($count_file, $total_count_real);

                        $unique_visitors_default = 11111;
                        $unique_visitors_real = count($visitor_ips);
                        $unique_visitors_display = $unique_visitors_default + $unique_visitors_real;
                        ?>
                        <p style="font-size: 16px; color:#FFF">Lượt truy cập duy nhất:
                            <?php echo $unique_visitors_display; ?></p>
                        <p style="font-size: 16px; color:#FFF">Tổng lượt truy cập: <?php echo $total_count_display; ?>
                        </p>
                    </div>
                </div>

                <!-- Liên hệ -->
                <div class="footer-column" role="contentinfo" aria-label="Liên hệ">
                    <p class="company-name" style="font-size:22px;font-weight:bold">Liên hệ</p>
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><strong>Chi nhánh & TTBH HCM:</strong> 150Ter Bùi Thị Xuân, Phường Bến Thành, TP.Hồ Chí Minh
                        </p>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-map-marker-alt"></i>
                        <p><strong>Chi nhánh Hà Nội:</strong> Số 01 Thái Hà, Phường Đống Đa, TP.Hà Nội</p>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-phone"></i>
                        <p><strong>Phòng kinh doanh:</strong> (028) 39293765</p>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-tools"></i>
                        <p><strong>Phòng kỹ thuật:</strong> (028) 39260996</p>
                    </div>
                    <div class="contact-info">
                        <i class="fas fa-envelope"></i>
                        <p><strong>Email:</strong> support@rosacomputer.ai</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer bottom -->
        <div class="footer-bottom">
            <div class="wrapper">
                <p>© 2024 | Bản quyền thuộc về CÔNG TY TNHH ĐIỆN TỬ VÀ TIN HỌC <a href="https://rosacomputer.vn/">TOÀN
                        VIỆT</a> </p>
            </div>
        </div>
    </footer>

    <div class="contact-fixed">
        <!--<div class="chabot_main">-->
        <!--    <img src="https://rosacomputer.vn/assets/images/chatbot-ai.gif"-->
        <!--         alt="chatbotai"-->
        <!--         style="cursor:pointer"-->
        <!--         onclick="toggleChatbot()">-->
        <!--</div>-->

        <a href="https://zalo.me/909749126673606301" target="_blank" class="zalo-link">
            <img src="https://img.icons8.com/color/48/zalo.png">
        </a>
    </div>

    <!-- ===== CONFIG CHATBOT ===== -->
    <script>
        //     window.ChatbotConfig = {
        //         tenantName: 'Rosa',
        //         serverUrl: 'https://server1.rosachatbot.com',
        //         width: '650px',
        //         height: '650px',
        //         buttonColor: '#ff0000',
        //         showButton: false
        //     };
        // 
    </script>

    <!-- ===== LOAD CHATBOT WIDGET ===== -->
    <!--<script src="https://server1.rosachatbot.com/static/js/chatbot-widget.js"></script>-->

    <!-- ===== JS ĐIỀU KHIỂN ===== -->
    <!--<script>-->
    <!--    function toggleChatbot() {-->
    <!--        if (window.ChatbotWidget) {-->
    <!--            ChatbotWidget.toggle();-->
    <!--        } else {-->
    <!--            alert("Chatbot đang load, thử lại sau vài giây");-->
    <!--        }-->
    <!--    }-->
    <!--</script>-->

    <!--<link href="https://cdn.jsdelivr.net/npm/@n8n/chat/dist/style.css" rel="stylesheet" />-->
    <!--<script type="module">-->
    <!--	import { createChat } from 'https://cdn.jsdelivr.net/npm/@n8n/chat/dist/chat.bundle.es.js';-->
    <!--	createChat({-->
    <!--		webhookUrl: 'https://gx10demon8n.rosachatbot.com/webhook/a03cd5ee-c96f-4a0f-922a-8bb1848eddb6/chat'-->
    <!--	});-->
    <!--</script>-->

    <!-- ===== ROSA Chatbot Widget (plugin) ===== -->
    <script>
        window.RosaChatbotConfig = {
            webhookUrl: 'https://gx10demon8n.rosachatbot.com/webhook/a03cd5ee-c96f-4a0f-922a-8bb1848eddb6/chat'
            // , botIconUrl: "https://rosacomputer.vn/assets/images/rosa.webp"  // (tuy chon) doi icon
        };
    </script>
    <script type="module" src="https://rosacomputer.vn/rosa-chatbot.js?v=3"></script>

    <!-- link js  -->
    <script src="../script/footer.js"></script>
    <!-- link js  -->
    <script src="../script/footer.js"></script>

    <script>
        //     function makeFloatingChatbot() {
        //     const chatbot = document.querySelector('.chabot_main');
        //     if (!chatbot) return;

        //     let x = window.innerWidth - 280;
        //     let y = window.innerHeight - 280;
        //     let dx = 2;
        //     let dy = 2;

        //     // Sử dụng file âm thanh của bạn
        //     const bounceSound = new Audio('../assets/images/rosa-chatbot.mp3');

        //     // Biến để kiểm soát thời gian phát âm thanh
        //     let canPlaySound = true;
        //     let soundCooldown =  30000; //  30 giây milliseconds
        //     let isFirstLoad = true; // Biến kiểm tra lần đầu load trang

        //     chatbot.style.position = 'fixed';
        //     chatbot.style.left = x + 'px';
        //     chatbot.style.top = y + 'px';
        //     chatbot.style.transition = 'none';

        //     // Phát âm thanh ngay khi vào trang web
        //     function playInitialSound() {
        //         const sound = bounceSound.cloneNode();
        //         sound.volume = 0.3;
        //         sound.play().catch(e => {
        //             console.log('Không thể phát âm thanh:', e);
        //             // Nếu trình duyệt chặn autoplay, thử phát khi user click
        //             document.addEventListener('click', function initClick() {
        //                 sound.play();
        //                 document.removeEventListener('click', initClick);
        //             }, { once: true });
        //         });

        //         // Sau khi phát âm thanh đầu tiên, đặt cooldown
        //         canPlaySound = false;
        //         setTimeout(() => {
        //             canPlaySound = true;
        //         }, soundCooldown);
        //     }

        //     function playBounceSound() {
        //         // Chỉ phát âm thanh nếu được phép
        //         if (!canPlaySound) return;

        //         const sound = bounceSound.cloneNode();
        //         sound.volume = 0.3;
        //         sound.play().catch(e => console.log('Không thể phát âm thanh:', e));

        //         // Tắt âm thanh và bật lại sau 30 giây
        //         canPlaySound = false;
        //         setTimeout(() => {
        //             canPlaySound = true;
        //         }, soundCooldown);
        //     }

        //     function animate() {
        //         // Phát âm thanh lần đầu tiên
        //         if (isFirstLoad) {
        //             playInitialSound();
        //             isFirstLoad = false;
        //         }

        //         const chatbotWidth = chatbot.offsetWidth;
        //         const chatbotHeight = chatbot.offsetHeight;

        //         x += dx;
        //         y += dy;

        //         if (x + chatbotWidth >= window.innerWidth || x <= 0) {
        //             dx = -dx;
        //             playBounceSound();
        //         }

        //         if (y + chatbotHeight >= window.innerHeight || y <= 0) {
        //             dy = -dy;
        //             playBounceSound();
        //         }

        //         chatbot.style.left = x + 'px';
        //         chatbot.style.top = y + 'px';

        //         requestAnimationFrame(animate);
        //     }

        //     animate();

        //     window.addEventListener('resize', () => {
        //         if (x + chatbot.offsetWidth > window.innerWidth) {
        //             x = window.innerWidth - chatbot.offsetWidth - 10;
        //         }
        //         if (y + chatbot.offsetHeight > window.innerHeight) {
        //             y = window.innerHeight - chatbot.offsetHeight - 10;
        //         }
        //     });

        //     chatbot.addEventListener('click', function(e) {
        //         e.stopPropagation();
        //         document.getElementById('chatbot-popup').style.display = 'block';
        //     });
        // }
        // document.addEventListener('DOMContentLoaded', makeFloatingChatbot);
        // 
    </script>

</body>

</html>