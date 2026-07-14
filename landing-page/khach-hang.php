<?php
$cssVersion = filemtime(__DIR__ . '/landing.css');
$khCssVersion = filemtime(__DIR__ . '/khach-hang.css');
$jsVersion = filemtime(__DIR__ . '/landing.js');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROSA AI Bridge - AI Local không giới hạn cho doanh nghiệp</title>
    <link rel="stylesheet" href="khach-hang.css?v=<?php echo $khCssVersion; ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link rel="icon" href="images/rosa-icon.png" type="image/png">
    <script src="landing.js?v=<?php echo $jsVersion; ?>" defer></script>
</head>

<body class="kh-page">
    <!-- Navbar -->
    <header class="navbar">
        <div class="container nav-content">
            <div class="logo">
                <img src="images/rosa.png" alt="ROSA Logo" class="logo-icon">
            </div>
            <nav class="nav-links">
                <a href="#">Giải pháp</a>
                <a href="khach-hang.php">Khách hàng</a>
                <a href="nguoi-van-hanh.php">Về ROSA</a>
                <a href="#">Ứng dụng</a>
                <a href="#">Sản phẩm</a>
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
                    <i class="ph-fill ph-lightning"></i> ROSA AI Bridge
                </div>
                <h1 class="kh-hero-title">AI không giới hạn,<br>ngay trong<br><span class="grad-text">văn phòng của bạn.</span></h1>
                <p class="kh-hero-desc">
                    ROSA AI Bridge biến máy chủ tại công ty bạn thành nguồn AI nội bộ. Dùng Claude Code, Cursor,
                    ComfyUI hay bất kỳ công cụ nào bạn đang dùng chỉ với vài cú nhấp chuột — không lo giới hạn
                    token, không sợ rò rỉ dữ liệu.
                </p>

                <div class="hero-buttons">
                    <a href="#" class="btn btn-primary">Đặt lịch demo miễn phí <i class="ph ph-arrow-right"></i></a>
                    <a href="#cach-hoat-dong" class="btn btn-outline"><i class="ph-fill ph-play-circle"></i> Xem cách hoạt động</a>
                </div>

                <ul class="kh-trust">
                    <li><i class="ph-fill ph-check-circle"></i> Dữ liệu 100% nội bộ</li>
                    <li><i class="ph-fill ph-check-circle"></i> Không giới hạn token</li>
                    <li><i class="ph-fill ph-check-circle"></i> Không phụ thuộc Cloud</li>
                </ul>
            </div>
            <div class="kh-hero-right">
                <div class="hero-diagram">
                    <img src="images/landing_2.png" alt="Sơ đồ ROSA AI Bridge kết nối Claude Code, Cursor, VS Code, ComfyUI qua API nội bộ">
                </div>
                <div class="hero-features">
                    <div class="hf-item">
                        <i class="ph-fill ph-rocket-launch"></i>
                        <span>Triển khai<br>trong 10 phút</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph-fill ph-shield-check"></i>
                        <span>Dữ liệu<br>không rời công ty</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph ph-infinity"></i>
                        <span>Không giới hạn<br>Token</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph-fill ph-lightning"></i>
                        <span>Tăng tốc<br>Claude Code</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 2. Vấn đề -->
        <section class="kh-section problem container">
            <div class="eyebrow">01 / Vấn đề</div>
            <div class="problem-head">
                <h2 class="kh-title">Bạn đang thuê AI theo từng token<br>— và <span class="grad-text-sm">trả tiền mãi mãi.</span></h2>

                <div class="problem-grid">
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-trend-up"></i></div>
                        <h4>Hoá đơn OpenAI, Anthropic tăng đều mỗi tháng.</h4>
                        <p>Càng dùng nhiều, càng trả nhiều — và không bao giờ dừng.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-shield-warning"></i></div>
                        <h4>Dữ liệu, mã nguồn bị gửi ra máy chủ ngoài.</h4>
                        <p>Rủi ro rò rỉ mỗi lần gọi AI từ bên ngoài công ty.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-hourglass-medium"></i></div>
                        <h4>Đang chạy giữa chừng thì… hết quota.</h4>
                        <p>Công việc gián đoạn, cả đội phải ngồi chờ.</p>
                    </div>
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-lock-key"></i></div>
                        <h4>Bạn bị khoá chặt vào một nhà cung cấp.</h4>
                        <p>Bị tính tiền theo mức dùng — cho công cụ dùng cả ngày.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Giải pháp -->
        <section class="kh-section solution container">
            <div class="eyebrow">02 / Giải pháp</div>
            <div class="solution-grid">
                <div class="solution-left">
                    <h2 class="kh-title">ROSA AI Bridge — AI của riêng bạn, dùng không giới hạn.</h2>
                    <p>
                        Thay vì thuê AI từ cloud theo từng token, bạn sở hữu nó. ROSA AI Bridge là nền tảng giúp
                        bạn triển khai AI mạnh mẽ ngay trong hệ thống nội bộ — an toàn, chủ động và không giới
                        hạn. Tương thích API OpenAI &amp; Anthropic, chỉ cần trỏ công cụ vào máy chủ mới, mọi
                        thứ chạy ngay, không cần viết lại code.
                    </p>
                    <div class="tool-pills">
                        <span class="tool-pill"><img src="images/claude.png" alt="Claude Code">Claude Code</span>
                        <span class="tool-pill"><i class="ph ph-cursor-click"></i>Cursor</span>
                        <span class="tool-pill"><i class="ph ph-flow-arrow"></i>ComfyUI</span>
                        <span class="tool-pill"><i class="ph ph-dots-three"></i>và hơn thế nữa...</span>
                    </div>
                </div>
                <div class="solution-right">
                    <div class="mini-diagram">
                        <svg viewBox="0 0 480 340" width="100%" role="img"
                            aria-label="Sơ đồ ROSA AI Bridge kết nối với các công cụ AI">
                            <defs>
                                <linearGradient id="miniLine" x1="0" y1="0" x2="1" y2="0">
                                    <stop offset="0%" stop-color="#c084fc" stop-opacity=".9" />
                                    <stop offset="100%" stop-color="#c084fc" stop-opacity=".2" />
                                </linearGradient>
                                <linearGradient id="miniCore" x1="0" y1="0" x2="0" y2="1">
                                    <stop offset="0%" stop-color="#1b1030" />
                                    <stop offset="100%" stop-color="#0b0710" />
                                </linearGradient>
                                <radialGradient id="miniGlow" cx="50%" cy="50%" r="50%">
                                    <stop offset="0%" stop-color="#8b5cf6" stop-opacity=".5" />
                                    <stop offset="100%" stop-color="#8b5cf6" stop-opacity="0" />
                                </radialGradient>
                            </defs>

                            <!-- connecting lines -->
                            <g stroke="url(#miniLine)" stroke-width="1.6" fill="none">
                                <path d="M92,64 H150 V120" stroke-dasharray="4 5">
                                    <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.6s"
                                        repeatCount="indefinite" />
                                </path>
                                <path d="M92,264 H150 V220" stroke-dasharray="4 5">
                                    <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.8s"
                                        repeatCount="indefinite" />
                                </path>
                                <path d="M388,64 H330 V120" stroke-dasharray="4 5">
                                    <animate attributeName="stroke-dashoffset" values="0;-18" dur="2s"
                                        repeatCount="indefinite" />
                                </path>
                                <path d="M388,264 H330 V220" stroke-dasharray="4 5">
                                    <animate attributeName="stroke-dashoffset" values="0;-18" dur="1.7s"
                                        repeatCount="indefinite" />
                                </path>
                            </g>

                            <!-- base glow -->
                            <ellipse cx="240" cy="234" rx="90" ry="14" fill="url(#miniGlow)" />

                            <!-- core rack -->
                            <rect x="190" y="120" width="100" height="100" rx="12" fill="url(#miniCore)"
                                stroke="#8b5cf6" stroke-width="1.4" />
                            <g stroke="rgba(192,132,252,.35)" stroke-width="1">
                                <rect x="200" y="132" width="80" height="12" rx="3" fill="#150f22" />
                                <rect x="200" y="150" width="80" height="12" rx="3" fill="#150f22" />
                                <rect x="200" y="168" width="80" height="12" rx="3" fill="#150f22" />
                                <rect x="200" y="186" width="80" height="12" rx="3" fill="#150f22" />
                            </g>
                            <g fill="#c084fc">
                                <circle cx="208" cy="138" r="1.6" />
                                <circle cx="208" cy="156" r="1.6" />
                                <circle cx="208" cy="174" r="1.6" />
                                <circle cx="208" cy="192" r="1.6" />
                            </g>
                            <rect x="195" y="100" width="90" height="24" rx="6" fill="#0b0710" stroke="#76b900"
                                stroke-width="1.4" />
                            <text x="240" y="116" text-anchor="middle" font-family="'Montserrat', Arial, sans-serif"
                                font-size="13" font-weight="800" fill="#76b900">ROSA</text>

                            <!-- outer nodes -->
                            <circle cx="60" cy="64" r="32" fill="#0b0710" stroke="#8b5cf6" stroke-width="1.2" />
                            <foreignObject x="42" y="46" width="36" height="36">
                                <div xmlns="http://www.w3.org/1999/xhtml" class="mini-fo"><i
                                        class="ph-fill ph-cube"></i></div>
                            </foreignObject>

                            <circle cx="60" cy="264" r="32" fill="#0b0710" stroke="#8b5cf6" stroke-width="1.2" />
                            <foreignObject x="42" y="246" width="36" height="36">
                                <div xmlns="http://www.w3.org/1999/xhtml" class="mini-fo"><i
                                        class="ph ph-cursor-click"></i></div>
                            </foreignObject>

                            <circle cx="420" cy="64" r="32" fill="#0b0710" stroke="#8b5cf6" stroke-width="1.2" />
                            <foreignObject x="402" y="46" width="36" height="36">
                                <div xmlns="http://www.w3.org/1999/xhtml" class="mini-fo"><i
                                        class="ph-fill ph-cloud"></i></div>
                            </foreignObject>

                            <circle cx="420" cy="264" r="32" fill="#0b0710" stroke="#8b5cf6" stroke-width="1.2" />
                            <foreignObject x="402" y="246" width="36" height="36">
                                <div xmlns="http://www.w3.org/1999/xhtml" class="mini-fo"><i
                                        class="ph ph-arrow-right"></i></div>
                            </foreignObject>
                        </svg>
                    </div>
                </div>
            </div>
        </section>

        <!-- 4. Cách hoạt động -->
        <section class="kh-section how-it-works container" id="cach-hoat-dong">
            <div class="eyebrow">03 / Cách hoạt động</div>
            <h2 class="kh-title">Đơn giản đến bất ngờ</h2>
            <p class="section-sub">Chỉ 3 bước để sở hữu sức mạnh AI không giới hạn.</p>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <div class="step-illust-wrap">
                        <img src="images/may-chu.png" alt="Máy chủ ROSA AI Bridge" class="step-illust-img">
                    </div>
                    <h4>Đặt máy chủ ROSA AI Bridge<br>tại văn phòng</h4>
                    <p>Kết nối vào mạng nội bộ<br>của công ty một cách an toàn.</p>
                </div>
                <div class="step-arrow"><i class="ph ph-arrow-right"></i></div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-illust-wrap">
                        <img src="images/cong-cu.png" alt="Kết nối công cụ Claude Code, Cursor, ComfyUI, VS Code, API" class="step-illust-img">
                    </div>
                    <h4>Trỏ công cụ của bạn<br>vào địa chỉ máy chủ</h4>
                    <p>Claude Code, Cursor, ComfyUI,<br>VS Code, API... đều trỏ về máy chủ.</p>
                </div>
                <div class="step-arrow"><i class="ph ph-arrow-right"></i></div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-illust-wrap">
                        <img src="images/vo_cuc.png" alt="Không giới hạn token" class="step-illust-img">
                    </div>
                    <h4>Dùng thoải mái.</h4>
                    <p>Không đếm token, không quota,<br>không hoá đơn cloud.</p>
                </div>
            </div>

            <div class="steps-trust">
                <div class="steps-trust-item"><i class="ph-fill ph-shield-check"></i> Dữ liệu 100% nội bộ</div>
                <div class="steps-trust-item"><i class="ph-fill ph-lock-key"></i> Không gửi ra ngoài</div>
                <div class="steps-trust-item"><i class="ph ph-cloud-slash"></i> Không phụ thuộc Cloud</div>
            </div>
        </section>

        <!-- 5. Giá trị cốt lõi -->
        <section class="kh-section core-value container">
            <div class="eyebrow">04 / Giá trị cốt lõi</div>
            <h2 class="kh-title">Một máy chủ, mọi năng lực AI</h2>

            <div class="value-grid">
                <div class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="https://img.icons8.com/fluency/48/get-revenue.png" alt="get-revenue"/></div>
                    <h4>Không hoá đơn token</h4>
                    <p>Trả một lần cho máy chủ. Dùng token không giới hạn, mãi mãi.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon value-blue"><i class="ph-fill ph-gauge"></i></div>
                    <h4>Không tường quota</h4>
                    <p>Chạy agent và tác vụ nặng cả ngày — không bị bóp tốc độ giữa chừng.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon value-teal"><i class="ph-fill ph-shield-check"></i></div>
                    <h4>Dữ liệu ở lại công ty</h4>
                    <p>Mã nguồn và tài liệu của bạn không rời khỏi mạng nội bộ.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon value-purple"><i class="ph-fill ph-plugs-connected"></i></div>
                    <h4>Giữ nguyên công cụ</h4>
                    <p>Tương thích API OpenAI &amp; Anthropic — không cần viết lại code.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon value-orange"><i class="ph-fill ph-code"></i></div>
                    <h4>Nền tảng cho lập trình viên</h4>
                    <p>API key riêng để đội dev tự xây ứng dụng nội bộ trên máy chủ này.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon value-rose"><i class="ph-fill ph-cube"></i></div>
                    <h4>9 năng lực AI trong một hộp</h4>
                    <p>Lập trình, chat, RAG, OCR, bóc tách ghi âm, giọng nói, tạo ảnh, tìm web.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon value-yellow"><i class="ph-fill ph-cpu"></i></div>
                    <h4>Không phụ thuộc phần cứng</h4>
                    <p>Chạy trên NVIDIA, AMD, Intel — hoặc phần cứng bạn đã có sẵn.</p>
                </div>
                <div class="value-card">
                    <div class="value-icon value-green"><i class="ph-fill ph-lock-key"></i></div>
                    <h4>Bảo mật tối ưu</h4>
                    <p>Chỉ hoạt động trong mạng nội bộ, kiểm soát truy cập &amp; phân quyền chặt chẽ.</p>
                </div>
            </div>
        </section>

        <!-- 6. Bài toán chi phí -->
        <section class="kh-section cost container" id="chi-phi">
            <div class="eyebrow">05 / Tiết kiệm chi phí</div>

            <div class="cost-grid">
                <div class="cost-chart">
                    <i class="ph-fill ph-trend-up chart-arrow"></i>
                    <div class="bar-chart">
                       <img src="images/bieu-do.png" alt="bieu đô" class="step-illust-img">
                    </div>
                </div>
                <div class="cost-main">
                    <h2 class="kh-title">Chỉ 10 lập trình viên dùng Claude Code đã đủ hoàn vốn.</h2>
                    <p>
                        Hãy lấy hoá đơn AI cloud tháng gần nhất của bạn. Đó là số tiền bạn sẽ trả lại mỗi tháng,
                        mãi mãi. Với ROSA AI Bridge, bạn trả một lần cho máy chủ — và chi phí sử dụng về gần như
                        bằng 0. Đội càng đông, dùng càng nhiều, hoàn vốn càng nhanh.
                    </p>
                    <a href="#" class="btn btn-primary js-open-modal">Tính ROI ngay cho doanh nghiệp của bạn <i class="ph ph-arrow-right"></i></a>
                </div>
                <div class="cost-stats">
                    <div class="stat-card">
                        <div class="stat"><img width="50" height="50" src="https://img.icons8.com/color/48/cash.png" alt="cash"/></div>
                        <strong>80%</strong>
                        <span>Giảm chi phí AI hàng tháng</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat"><img width="50" height="50" src="https://img.icons8.com/nolan/64/database.png" alt="database"/></div>
                        <strong>100%</strong>
                        <span>Dữ liệu giữ lại trong nội bộ</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat"><img width="50" height="50" src="https://img.icons8.com/color/48/commercial-development-management--v1.png" alt="commercial-development-management--v1"/></div>
                        <strong>24/7</strong>
                        <span>Sẵn sàng phục vụ đội ngũ</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- 7 & 8. Bằng chứng + FAQ -->
        <section class="kh-section proof-faq container">
            <div class="proof-col">
                <div class="eyebrow">06 / Bằng chứng</div>
                <div class="proof-card">
                    <i class="ph-fill ph-quotes"></i>
                    <p>Khu vực chèn câu chuyện khách hàng thật: "Chúng tôi đã cắt ___ chi phí cloud mỗi tháng."</p>
                    <span>— Khách hàng của ROSA</span>
                </div>
                <div class="logo-strip">
                    <span class="logo-chip"><i class="ph ph-buildings"></i></span>
                    <span class="logo-chip"><i class="ph ph-buildings"></i></span>
                    <span class="logo-chip"><i class="ph ph-buildings"></i></span>
                    <span class="logo-chip"><i class="ph ph-buildings"></i></span>
                    <span class="logo-note">Logo khách hàng sẽ được cập nhật tại đây</span>
                </div>
            </div>
            <div class="faq-col">
                <div class="eyebrow">07 / Câu hỏi thường gặp</div>
                <div class="faq-list">
                    <details class="faq-item">
                        <summary>Tôi có phải thay đổi code không?</summary>
                        <p>Không. Công cụ của bạn chỉ cần trỏ vào địa chỉ máy chủ mới.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Model nội bộ có đủ mạnh không?</summary>
                        <p>Bạn tự kiểm chứng trong buổi demo. Bạn cũng chọn được model phù hợp với tốc độ, chất
                            lượng và phần cứng của mình.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Nếu máy chủ gặp sự cố thì sao?</summary>
                        <p>Chúng tôi cấu hình sẵn phương án dự phòng và khôi phục nhanh cho bạn.</p>
                    </details>
                    <details class="faq-item">
                        <summary>Có cần đội IT lớn để vận hành không?</summary>
                        <p>Không. Cài đặt một lần; thêm một máy tính mới chỉ mất một thao tác.</p>
                    </details>
                </div>
            </div>
        </section>

        <!-- 9. CTA cuối trang -->
        <section class="cta-banner container">
            <div class="cta-card kh-cta-card">
                <div class="kh-cta-icon"><i class="ph-fill ph-cloud"></i><i class="ph-fill ph-hard-drives"></i></div>
                <div class="cta-left">
                    <h2>Ngừng thuê AI. Bắt đầu sở hữu nó.</h2>
                    <p>Đặt lịch demo 20 phút — chúng tôi sẽ chạy thử ngay trên chính công cụ và bài toán của bạn.</p>
                </div>
                <div class="cta-right">
                    <a href="#" class="btn btn-primary">Đặt lịch demo miễn phí <i class="ph ph-arrow-right"></i></a>
                </div>
            </div>
        </section>

        <!-- Modal Liên Hệ -->
        <div id="contactModal" class="modal">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <button class="close-modal">&times;</button>
                <div class="modal-header">
                    <h2>Đặt lịch demo ROSA AI Bridge</h2>
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
                        <textarea id="message" name="message" rows="3" placeholder="Ví dụ: Tôi muốn demo ROSA AI Bridge cho đội Claude Code..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-full">Gửi yêu cầu</button>
                </form>
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
                <a href="#"><i class="ph ph-facebook-logo"></i></a>
                <a href="#"><i class="ph ph-linkedin-logo"></i></a>
                <a href="#"><i class="ph ph-youtube-logo"></i></a>
            </div>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.js-open-modal').forEach(function (btn) {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                document.getElementById('contactModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });
    </script>
</body>

</html>
