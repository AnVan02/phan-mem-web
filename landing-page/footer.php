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
                    <a href="https://www.facebook.com/people/ROSA-AI-Computer/61559427752479/" target="_blank" title="Facebook" style="background: #1877F2; font-size: 24px">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="https://www.linkedin.com/in/rosa-ai-computer-20980b352/" target="_blank" title="LinkedIn" style="background: #0A66C2; font-size: 24px">
                        <i class="fab fa-linkedin"></i>
                    </a>
                     <a href="https://www.youtube.com/@rosaaicomputer" target="_blank" title="YouTube" style="background: #FF0000; font-size: 24px">
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
                    <li><a href="https://rosacomputer.vn/pages/main/index.php?page=products&brand_id=4">Sản phẩm</a></li>
                    <li><a href="/chinh-sach-bao-hanh.php">Bảo hành</a></li>
                    <li><a href="/chinh-sach-bao-mat.php">Bảo mật</a></li>
                </ul>
                <a href="http://online.gov.vn/Website/chi-tiet-135455" target="_blank">
                    <img src="https://rosacomputer.vn/image/BCT.png" alt="Bộ Công Thương" style="width:160px; height:auto;float: left">
                </a>
            </div>

            <!-- Chương trình -->
            <div class="footer-column" role="navigation" aria-label="Chương trình ROSA">
                <p class="company-name"style="font-size:22px;font-weight:bold" >Chương trình ROSA</p>
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
                    <p style="font-size: 16px; color:#FFF">Lượt truy cập duy nhất: <?php echo $unique_visitors_display; ?></p>
                    <p style="font-size: 16px; color:#FFF">Tổng lượt truy cập: <?php echo $total_count_display; ?></p>
                </div>
            </div>

            <!-- Liên hệ -->
            <div class="footer-column" role="contentinfo" aria-label="Liên hệ">
                <p class="company-name" style="font-size:22px;font-weight:bold">Liên hệ</p>
                <div class="contact-info">
                    <i class="fas fa-map-marker-alt"></i>
                    <p><strong>Chi nhánh & TTBH HCM:</strong> 150Ter Bùi Thị Xuân, Phường Bến Thành, TP.Hồ Chí Minh</p>
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
            <p>© 2024 | Bản quyền thuộc về CÔNG TY TNHH ĐIỆN TỬ VÀ TIN HỌC <a href="https://rosacomputer.vn/">TOÀN VIỆT</a> </p>
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

    <a href="https://zalo.me/909749126673606301"
       target="_blank"
       class="zalo-link">
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
// </script>

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

<style>
   /* Reset cơ bản */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }
    
    body {
         font-family: 'Montserrat';
    }
    

    /* Áp dụng font cho toàn trang */
    html, body, a, div, p {
      font-family: 'Montserrat';
    }
    
    a {
      text-decoration: none !important;
    }
    
    a:hover {
      text-decoration: none !important;
    }
    
    /* Footer */
    .rosa-footer {
        background: linear-gradient(135deg, #1a2332 0%, #2c3e50 100%);
        color: #ffffff;
        padding: 40px 0 0 0;
        font-family: 'Montserrat' !important;
        
    }
    
    .wrapper {
        max-width: 1350px;
        margin: 0 auto;
        padding: 0 20px;
    }
    
    .footer-main {
        display:grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 40px;
        padding-bottom: 30px;
    }
    
    .footer-column {
        color: #ffffff;
    }
    
    .footer-column ul {
        list-style: none;
        padding-left: 0;
        text-align: left;
    }
    
    .footer-column ul li {
        margin-bottom: 8px;
        margin-left: 0;
    }
    
    .footer-column ul li a {
        color: #fafafa;
        text-decoration: none;
        font-size: 16px;
        line-height: 1.6;
        transition: color 0.3s ease;
    }
    
    .footer-column ul li a:hover {
        color: #00aaff;
    }
    
    .footer-column p {
        font-size: 16px;
        line-height: 1.6;
        margin-bottom: 8px;
    }
    
    .footer-column p strong {
        color: #ffffff;
        font-size: 16px;
    }
    
    /* Section title giống h3 */
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 15px;
        font-family:'Montserrat' !important;
        
    }
    
    /* Logo */
    .logo_footer img {
        width: 250px;
        height: auto;
        margin-bottom: 17px;
    }
    
    /* Social icons */
    .social-icons {
        display: flex;
        gap: 15px;
        margin-top: 15px;
        font-size: 30px;
    }
    
    .social-icons a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 35px;
        height: 35px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        color: #ffffff;
        font-size: 24px;
        transition: all 0.3s ease;
    }
    
    .social-icons a:hover {
        background: #00aaff;
        transform: scale(1.1);
    }
    
    /* Contact info */
    .contact-info {
        display: flex;
        align-items: center;
        margin-bottom: 8px;
    }
    
    .contact-info i {
        margin-right: 8px;
        width: 16px;
        color: #74b9ff;
    }
    
    /* Stats truy cập */
    .stats p {
        font-size: 16px;
        color: #FFF;
        margin-bottom: 5px;
    }
    
    /* Footer bottom */
    .footer-bottom {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        padding: 10px 0;
        text-align: center;
    }
    
    .footer-bottom p {
        font-size: 16px;
    }
    
    .footer-bottom a {
        color: #0080ff;
        text-decoration: none;
    }
    
    /* Contact fixed (chatbot + social) */
    .contact-fixed {
        position: fixed;
        bottom: 100px;
        right: 30px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        z-index: 9999;
        align-items: flex-end;
    }
    
    /* Chatbot phóng to 200x200 */
    .chabot_main {
        width: 230px;
        height: 230px;
        cursor: pointer;
        transition: transform 0.3s ease;
    }
    
    .chabot_main:hover {
        transform: scale(1.05);
    }
    
    .chabot_main img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        /*border-radius: 50%;*/
        /*box-shadow: 0 4px 12px rgba(0,0,0,0.3);*/
    }
    
    /* Zalo giữ nguyên kích thước */
    .zalo-link {
        display: block;
        width: 68px;
        height: 68px;
        transition: transform 0.3s ease;
    }
    
    .zalo-link:hover {
        transform: scale(1.1);
    }
    
    .zalo-link img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 50%;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    
    /* Chatbot popup */
    #chatbot-popup {
        display: none;
        position: fixed;
        bottom: 5px;
        right: 10px;
        width: 90vw;
        max-width:650px;
        height: 80vh;
        max-height: 680px;
        border: 1px solid #ccc;
        background: #fff;
        box-shadow: 0 4px 16px rgba(0,0,0,0.2);
        border-radius: 8px;
        overflow: hidden;
        z-index: 999999999 !important;
    }
    
    #chatbot-popup div:first-child {
        background: #eee;
        padding: 5px;
        text-align: right;
        height: 45px;
    }
    
    #chatbot-popup iframe {
        border: none;
        width: 100%;
        height: 100%;
    }
    
    /* Responsive Design cho màn hình tablet/mobile thông thường */
@media (max-width: 768px) {
    header {
        padding: 8px 0;
    }

    .header-container {
        width: 100%;
        padding: 0 12px;
        gap: 8px;
    }

    .icon_header {
        width: 80px;
    }

    .theme-toggle button {
        width: 32px;
        height: 32px;
        font-size: 0.9625rem;
    }

    .main-chat {
        padding: 8px;
    }

    .chat-box {
        height: calc(100vh - 90px);
        min-height: 400px;
        border-radius: 16px;
    }

    .chat-header {
        padding: 10px 12px;
        gap: 8px;
    }

    .chat-header h5 {
        font-size: 0.875rem;
    }

    .icon_chatbot {
        width: 28px;
        height: 28px;
        padding: 2px;
    }

    .btn-reload {
        width: 28px;
        height: 28px;
        font-size: 0.875rem;
    }

    .chat-body {
        padding: 12px;
        gap: 8px;
    }

    .chat-body::-webkit-scrollbar {
        width: 4px;
    }

    .message {
        max-width: 85%;
        padding: 10px 14px;
        font-size: 0.875rem;
        line-height: 1.4;
        border-radius: 12px;
        margin-bottom: 4px;
    }

    .message.user {
        border-bottom-right-radius: 4px;
    }

    .message.bot {
        border-bottom-left-radius: 4px;
    }

    .chat-footer {
        padding: 55px 12px;
        gap: 8px;
    }

    .chat-footer input {
        height: 36px;
        padding: 0 14px;
        font-size: 0.875rem;
        border-radius: 18px;
    }

    .chat-footer input::placeholder {
        font-size: 0.875rem;
    }

    .chat-footer button {
        width: 36px;
        height: 36px;
        font-size: 0.875rem;
    }

    .transition-wrapper,
    .transition-box {
        display: none !important;
    }
}
    
    /* Responsive */
    @media (max-width: 768px) {
        .footer-main {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }
        .section-title {
            font-size: 16px;
            
        }
        
        /* Điều chỉnh chatbot trên mobile */
        .chabot_main {
            width: 120px;
            height: 120px;
        }
        
        .contact-fixed {
            bottom: 90px;
            right: 20px;
        }
        
        
    }
    
    
    @media (max-width: 480px) {
        .footer-main {
            grid-template-columns: 1fr;
            gap: 25px;
        }
        .section-title {
            font-size: 15px;
        }
        #chatbot-popup {
            width: 99vw !important;
            height: 70vh !important;
            right: 5px !important;
            bottom: 5px !important;
        }
        
        /* Điều chỉnh chatbot trên mobile nhỏ */
        .chabot_main {
            width: 150px;
            height: 150px;
        }
        
        .contact-fixed {
            bottom: 85px;
            right: 15px;
        }
    }
</style>
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
// </script>
