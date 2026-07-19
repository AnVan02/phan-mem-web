<?php
$cssVersion = filemtime(__DIR__ . '/landing.css');
$khCssVersion = filemtime(__DIR__ . '/ROSA-AI-CONNECT.css');
$jsVersion = filemtime(__DIR__ . '/landing.js');
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ROSA AI Connect - AI Local không giới hạn cho doanh nghiệp</title>
    <link rel="stylesheet" href="ROSA-AI-CONNECT.css?v=<?php echo $khCssVersion; ?>">
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
                <a href="landing.php">GIẢI PHÁP</a>
                <a href="ROSA-AI-CONNECT.php">AI CONNECT</a>
                <a href="ROSA-AI-WORKSPACE.php">AI WORKSPACE</a>
                <a href="#">ỨNG DỤNG </a>
                <a href="#">SẢN PHẨM</a>
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
                    <i class="ph-fill ph-star"></i> ROSA AI Connect
                </div>
                <h1 class="kh-hero-title">AI không giới hạn,<br>ngay trong<br><span class="grad-text">văn phòng của bạn.</span></h1>
                <p class="kh-hero-desc">
                    ROSA AI Connect biến máy chủ tại công ty bạn thành nguồn AI nội bộ nhiều ứng dụng phổ biến như Claude Code, Cursor,
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
                    <img src="images/landing_6.png" alt="Sơ đồ ROSA AI Connect kết nối Claude Code, Cursor, VS Code, ComfyUI qua API nội bộ">
                </div>
                <div class="hero-features">
                    <div class="hf-item">
                        <i class="ph-fill ph-rocket-launch"></i>
                        <span>Triển khai<br>nhanh chóng</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph-fill ph-shield-check"></i>
                        <span>Nâng cao<br>hiệu xuất làm việc</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph ph-infinity"></i>
                        <span>Không giới hạn<br>Token, Quata</span>
                    </div>
                    <div class="hf-item">
                        <i class="ph-fill ph-lightning"></i>
                        <span>Tăng tốc<br>Claude Code</span>
                    </div>
                </div>
            </div>
        </section>

        <section class="kh-section problem container">
            <div class="eyebrow">01 / Vấn đề</div>

            <div class="problem-head">
                <h2 class="kh-title">
                    Bạn đang thuê AI theo từng token<br>
                    — và <span class="grad-text-sm">trả tiền mãi mãi.</span>
                </h2>

                <div class="problem-grid">
                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-trend-up"></i></div>
                        <h4>Chi phí AI tăng theo mỗi lần sử dụng.</h4>
                        <p>Càng nhiều người dùng, càng nhiều yêu cầu xử lý, hóa đơn càng phình to mỗi tháng.</p>
                    </div>

                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-shield-warning"></i></div>
                        <h4>Dữ liệu quan trọng phải gửi lên hạ tầng bên thứ ba.</h4>
                        <p>Mã nguồn, tài liệu nội bộ và dữ liệu khách hàng luôn tiềm ẩn rủi ro về quyền riêng tư.</p>
                    </div>

                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-hourglass-medium"></i></div>
                        <h4>Hết quota giữa lúc đang làm việc.</h4>
                        <p>Công việc bị gián đoạn, phải chờ nâng hạn mức hoặc mua thêm lượt sử dụng.</p>
                    </div>

                    <div class="problem-card">
                        <div class="problem-icon"><i class="ph-fill ph-lock-key"></i></div>
                        <h4>Phụ thuộc vào một nền tảng duy nhất.</h4>
                        <p>Khó chuyển đổi, chi phí khó kiểm soát và luôn bị ràng buộc vào chính sách của nhà cung cấp.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Giải pháp -->
        <section class="kh-section solution container">
            <div class="eyebrow">02 / Giải pháp</div>
            <div class="solution-grid">
                <div class="solution-top">
                    <h2 class="kh-title">ROSA AI Connect — AI của riêng bạn, dùng không giới hạn.</h2>
                    <p>
                        Thay vì thuê AI từ cloud theo từng token, bạn sở hữu nó. ROSA AI Connect là nền tảng giúp
                        bạn triển khai AI mạnh mẽ ngay trong hệ thống nội bộ — an toàn, chủ động và không giới
                        hạn.ROSA AI Connect dễ dàng kết nối các ứng dụng mô hình AI local đến nhiều ứng dụng phổ biến như : Claude AI, Cursor, Comfyui, Krita...
                    </p>
                    <div class="tool-pills">
                        <span class="tool-pill"><img src="images/claude.png" alt="Claude Code">Claude Code</span>
                        <span class="tool-pill"><img src="images/Cursor_logo.png" alt="Cursor Code">Cursor</span>
                        <span class="tool-pill"><img src="images/comfy.webp" alt="ComfyUI">ComfyUI</span>
                        <span class="tool-pill"><i class="ph ph-dots-three"></i>và hơn thế nữa...</span>
                    </div>
                </div>

                <div class="hub">
                    <div class="hub-grid">
                        <div class="hub-card hub-tl">
                            <div class="hub-avatar"><img src="images/claude.png" alt="Claude AI"></div>
                            <h4>CLAUDE AI</h4>
                            <p>Kết nối và sử dụng mô hình AI qua Claude</p>
                        </div>
                        <span class="hub-line hub-line-tl"></span>

                        <div class="hub-card hub-bl">
                            <div class="hub-avatar"><img src="images/Cursor_logo.png" alt="Cursor"></div>
                            <h4>CURSOR</h4>
                            <p>Kết nối với Cursor để hỗ trợ lập trình AI</p>
                        </div>
                        <span class="hub-line hub-line-bl"></span>

                        <div class="hub-center">
                            <div class="hub-glow"></div>
                            <div class="hub-logo"><img src="images/rosa.png" alt="ROSA"></div>
                            <div class="hub-feature"><i class="ph-fill ph-cube"></i><span>Quản lý mô hình AI</span></div>
                            <div class="hub-feature"><i class="ph ph-plug"></i><span>Kết nối &amp; API</span></div>
                            <div class="hub-feature"><i class="ph-fill ph-shield-check"></i><span>Bảo mật &amp; Quyền
                                    truy cập</span></div>
                            <div class="hub-feature"><i class="ph ph-chart-line"></i><span>Giám sát &amp; Nhật ký</span>
                            </div>
                        </div>

                        <span class="hub-line hub-line-tr"></span>
                        <div class="hub-card hub-tr">
                            <div class="hub-avatar"><img src="images/comfy.webp" alt="ComfyUI"></div>
                            <h4>COMFYUI</h4>
                            <p>Kết nối ComfyUI để tạo và xử lý hình ảnh AI</p>
                        </div>

                        <span class="hub-line hub-line-br"></span>
                        <div class="hub-card hub-br">
                            <div class="hub-avatar"><img src="images/krita.svg" alt="Krita"></div>
                            <h4>KRITA</h4>
                            <p>Kết nối Krita để hỗ trợ vẽ và chỉnh sửa AI</p>
                        </div>
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
                        <img src="images/may-chu.png" alt="Máy chủ ROSA AI Connect" class="step-illust-img">
                    </div>
                    <h4>Chọn mô hình AI bạn muốn triển khai</h4>
                    <p>ROSA hỗ trợ tối ưu mô hình AI chạy trên phần cứng máy trạm /server</p>
                </div>
                <div class="step-arrow"><i class="ph ph-arrow-right"></i></div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <div class="step-illust-wrap">
                        <img src="images/cong-cu.png" alt="Kết nối công cụ Claude Code, Cursor, ComfyUI, VS Code, API" class="step-illust-img">
                    </div>
                    <h4>Kết nối công cụ với máy chủ ROSA</h4>
                    <p>Claude Code, Cursor, ComfyUI, Hermes, VS Code hoặc API đều có thể kết nối đến cùng một máy chủ</p>
                </div>
                <div class="step-arrow"><i class="ph ph-arrow-right"></i></div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <div class="step-illust-wrap">
                        <img src="images/vo_cuc.png" alt="Không giới hạn token" class="step-illust-img">
                    </div>
                    <h4>Sử dụng không giới hạn</h4>
                    <p>Không giới hạn token, không quota, dữ liệu được xử lý an toàn ngay trong mạng nội bộ của doanh nghiệp.</p>
                </div>
            </div>

            <div class="steps-trust">
                <div class="steps-trust-item"><img width="80" height="80" src="https://img.icons8.com/officel/80/database.png" alt="get-revenue" /> Dữ liệu 100% nội bộ</div>
                <div class="steps-trust-item"><img width="80" height="80" src="https://img.icons8.com/fluency/80/cash--v1.png" alt="get-revenue" /> Không phát sinh chi phi</div>
                <div class="steps-trust-item"><img width="80" height="80" src="https://img.icons8.com/fluency/80/infinity.png" alt="get-revenue" /> Sử dụng không giới hạn </div>
            </div>
        </section>

        <!-- 5. Giá trị cốt lõi -->
        <section class="kh-section core-value container">
            <div class="eyebrow">04 / Giá trị cốt lõi</div>
            <h2 class="kh-title">Một máy chủ, mọi năng lực AI</h2>

            <div class="value-grid">
                <a href="https://claude.com/" target="_blank" rel="noopener noreferrer" class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="https://img.icons8.com/fluency/48/claude-ai.png" alt="claude-ai" /></div>
                    <h4>Claude AI</h4>
                    <p>Tương thích API Anthropic — dùng Claude ngay trên máy chủ nội bộ của bạn.</p>
                </a>
                <a href="https://cursor.com" target="_blank" rel="noopener noreferrer" class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="https://img.icons8.com/color/48/cursor-ai.png" alt="cursor" /></div>
                    <h4>Cursor</h4>
                    <p>Kết nối trực tiếp Cursor IDE với máy chủ AI nội bộ, code mượt không giới hạn.</p>
                </a>
                <a href="https://www.comfy.org" target="_blank" rel="noopener noreferrer" class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="images/comfy1.webp" alt="comfyui" /></div>
                    <h4>ComfyUI</h4>
                    <p>Chạy workflow tạo ảnh node-based ComfyUI ngay trên hạ tầng của bạn.</p>
                </a>
                <a href="https://www.adobe.com/products/photoshop.html" target="_blank" rel="noopener noreferrer" class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="https://img.icons8.com/color/48/adobe-photoshop.png" alt="photoshop" /></div>
                    <h4>Photoshop</h4>
                    <p>Plugin AI trong Photoshop trỏ thẳng về máy chủ, không qua dịch vụ ngoài.</p>
                </a>
                <a href="https://krita.org" target="_blank" rel="noopener noreferrer" class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="images/krita.svg" alt="krita" /></div>
                    <h4>Krita</h4>
                    <p>Kết hợp Krita AI Diffusion với máy chủ nội bộ để vẽ và inpaint không giới hạn.</p>
                </a>
                <a href="https://nousresearch.com" target="_blank" rel="noopener noreferrer" class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="images/hermes.png" alt="hermes" /></div>
                    <h4>Hermes</h4>
                    <p>Chạy mô hình Hermes cùng các model mã nguồn mở khác trên cùng một máy chủ.</p>
                </a>
                <a href="https://n8n.io" target="_blank" rel="noopener noreferrer" class="value-card">
                    <div class="value-icon value"><img width="60" height="60" src="https://cdn.jsdelivr.net/gh/homarr-labs/dashboard-icons/png/n8n.png" alt="n8n" /></div>
                    <h4>N8N</h4>
                    <p>Kết nối n8n với máy chủ AI nội bộ, tự động hoá quy trình không giới hạn request.</p>
                </a>
            </div>
        </section>

        <!-- 6. Bài toán chi phí -->
        <!-- <section class="kh-section cost container" id="chi-phi">
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
                        mãi mãi. Với ROSA AI Connect, bạn trả một lần cho máy chủ — và chi phí sử dụng về gần như
                        bằng 0. Đội càng đông, dùng càng nhiều, hoàn vốn càng nhanh.
                    </p>
                    <a href="#" class="btn btn-primary js-open-modal">Tính ROI ngay cho doanh nghiệp của bạn <i class="ph ph-arrow-right"></i></a>
                </div>
                <div class="cost-stats">
                    <div class="stat-card">
                        <div class="stat"><img width="50" height="50" src="https://img.icons8.com/color/48/cash.png" alt="cash" /></div>
                        <strong>80%</strong>
                        <span>Giảm chi phí AI hàng tháng</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat"><img width="50" height="50" src="https://img.icons8.com/nolan/64/database.png" alt="database" /></div>
                        <strong>100%</strong>
                        <span>Dữ liệu giữ lại trong nội bộ</span>
                    </div>
                    <div class="stat-card">
                        <div class="stat"><img width="50" height="50" src="https://img.icons8.com/color/48/commercial-development-management--v1.png" alt="commercial-development-management--v1" /></div>
                        <strong>24/7</strong>
                        <span>Sẵn sàng phục vụ đội ngũ</span>
                    </div>
                </div>
            </div>
        </section> -->

        <!-- 7 & 8. Bằng chứng + FAQ -->
        <section class="kh-section proof-faq container">
            <div class="faq-visual">
                <img src="images/ai-connect.png"
                    alt="Sơ đồ ROSA AI Connect kết nối Claude Code, Cursor, Photoshop, Krita, ComfyUI">
            </div>
            <div class="faq-col">
                <div class="eyebrow">05 / Câu hỏi thường gặp</div>
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
                <div class="kh-cta-icon">
                    <img width="90" height="80" src="https://img.icons8.com/external-soft-fill-juicy-fish/60/external-ai-contact-us-soft-fill-soft-fill-juicy-fish.png">
                </div>
        
                <div class="cta-left">
                    <h2>Đưa AI về doanh nghiệp của bạn.</h2>
                    <p>Đặt lịch demo 20 phút để trải nghiệm AI chạy ngay trên dữ liệu và quy trình làm việc của bạn.</p>
                </div>
        
                <div class="cta-right">
                    <a href="#" class="btn btn-primary">
                        Đặt lịch demo miễn phí
                        <i class="ph ph-arrow-right"></i>
                    </a>
                </div>
            </div>
        </section>

        <!-- Modal Liên Hệ -->
        <div id="contactModal" class="modal">
            <div class="modal-overlay"></div>
            <div class="modal-content">
                <button class="close-modal">&times;</button>
                <div class="modal-header">
                    <h2>Đặt lịch demo ROSA AI Connect</h2>
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
                        <textarea id="message" name="message" rows="3" placeholder="Ví dụ: Tôi muốn demo ROSA AI Connect cho đội Claude Code..."></textarea>
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
        document.querySelectorAll('.js-open-modal').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                document.getElementById('contactModal').classList.add('active');
                document.body.style.overflow = 'hidden';
            });
        });
    </script>
</body>

</html>