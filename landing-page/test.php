<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ROSA AI — Trợ lý AI làm việc thay bạn, 24/7</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,400;0,500;0,600;0,700;0,800;1,500&family=Lora:ital@1&display=swap" rel="stylesheet">
<style>
  :root{
    --bg: #F5EFE2;
    --bg-card: #FDFBF5;
    --bg-card-alt: #EFE7D4;
    --ink: #2B2620;
    --ink-soft: #5B5346;
    --ink-faint: #8B826F;
    --line: #E0D5BC;
    --forest: #2E6B57;
    --forest-dim: #C7D9CE;
    --clay: #C1592E;
    --clay-soft: #E8DCC9;
    --gold: #C99A3B;
    --radius-sm: 10px;
    --radius: 18px;
    --radius-lg: 26px;
    --maxw: 1120px;
    --shadow: 0 6px 24px rgba(43,38,32,0.07);
  }
  *{box-sizing:border-box; margin:0; padding:0;}
  html{scroll-behavior:smooth;}
  body{
    background: var(--bg);
    color: var(--ink);
    font-family:'Be Vietnam Pro', sans-serif;
    font-size:16px;
    line-height:1.65;
    -webkit-font-smoothing:antialiased;
  }
  .display{ font-family:'Be Vietnam Pro', sans-serif; font-weight:800; letter-spacing:-0.01em; }
  .quote-serif{ font-family:'Lora', serif; font-style:italic; }
  a{ color:inherit; text-decoration:none; }
  .wrap{ max-width: var(--maxw); margin:0 auto; padding:0 28px; }
  img,svg{ display:block; max-width:100%; }
  ::selection{ background: var(--forest); color:#FDFBF5; }
  :focus-visible{ outline:2px solid var(--forest); outline-offset:3px; }

  .eyebrow{
    font-size:13px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase;
    color: var(--forest);
    display:flex; align-items:center; gap:10px; margin-bottom:16px;
  }
  .eyebrow::before{ content:""; width:9px; height:9px; border-radius:50%; background: var(--clay); flex-shrink:0; }

  header.nav{
    position: sticky; top:0; z-index:50;
    background: rgba(245,239,226,0.9);
    backdrop-filter: blur(8px);
    border-bottom:1px solid var(--line);
  }
  .nav-inner{ display:flex; align-items:center; justify-content:space-between; padding:16px 0; }
  .logo{ display:flex; align-items:center; gap:9px; font-family:'Be Vietnam Pro'; font-weight:800; font-size:19px; }
  .logo .mark{ width:26px; height:26px; border-radius:8px; background: var(--forest); display:flex; align-items:center; justify-content:center; color:#FDFBF5; font-size:13px; font-weight:800;}
  .nav-cta{
    font-size:14px; font-weight:600; color: var(--bg-card);
    background: var(--clay);
    padding:10px 20px; border-radius: 999px;
    transition: all .15s ease;
  }
  .nav-cta:hover{ background:#A64A25; }

  .btn{
    display:inline-flex; align-items:center; gap:9px;
    font-size:15.5px; font-weight:600;
    padding:15px 26px; border-radius:999px;
    transition: all .15s ease; cursor:pointer; border:1px solid transparent;
  }
  .btn-primary{ background: var(--clay); color:#FDFBF5; box-shadow: 0 6px 18px rgba(193,89,46,0.28); }
  .btn-primary:hover{ background:#A64A25; transform: translateY(-1px); }
  .btn-ghost{ border-color: var(--line); color: var(--ink); background: var(--bg-card); }
  .btn-ghost:hover{ border-color: var(--forest); color: var(--forest); }
  .btn-arrow{ transition: transform .15s ease; }
  .btn:hover .btn-arrow{ transform: translateX(3px); }

  /* HERO */
  .hero{ padding:90px 0 76px; }
  .hero-grid{ display:grid; grid-template-columns: 1.05fr 0.95fr; gap:56px; align-items:center; }
  .hero h1{ font-size:46px; line-height:1.12; margin-bottom:20px; }
  .hero .lede{ font-size:17.5px; color: var(--ink-soft); max-width:48ch; margin-bottom:30px; }
  .hero-ctas{ margin-bottom:26px; }
  .trust-line{ font-size:13.5px; color: var(--ink-faint); display:flex; flex-wrap:wrap; gap:6px 12px; }
  .trust-line span:not(:last-child)::after{ content:"·"; margin-left:12px; color: var(--gold); }

  .hub-frame{
    background: var(--bg-card); border:1px solid var(--line); border-radius: var(--radius-lg);
    padding:22px; box-shadow: var(--shadow);
  }
  .hub-caption{ font-size:12.5px; color: var(--ink-faint); text-align:center; margin-top:10px; }

  section{ padding:82px 0; border-top:1px solid var(--line); }
  .section-head{ max-width:640px; margin-bottom:44px; }
  .section-head h2{ font-size:32px; line-height:1.18; }
  .section-head p.desc{ color: var(--ink-soft); margin-top:14px; font-size:16px; }

  /* PROBLEM */
  .problem-grid{ display:grid; grid-template-columns: repeat(2,1fr); gap:16px; }
  .problem-card{
    background: var(--bg-card); border:1px solid var(--line); border-radius: var(--radius);
    padding:26px; display:flex; gap:16px; align-items:flex-start;
  }
  .problem-card .ic{ width:30px; height:30px; flex-shrink:0; color: var(--clay); }
  .problem-card p{ font-size:15.5px; color: var(--ink); }

  /* SOLUTION */
  .solution-wrap{ display:grid; grid-template-columns: 1.1fr 0.9fr; gap:52px; align-items:center; }
  .solution-wrap h2{ font-size:32px; margin-bottom:18px; line-height:1.2; }
  .solution-wrap p{ color: var(--ink-soft); font-size:16.5px; margin-bottom:16px; }
  .callout-line{
    font-family:'Lora', serif; font-style:italic; font-size:21px; color: var(--forest);
    border-left:3px solid var(--forest); padding-left:18px; margin-top:22px;
  }
  .brain-visual{
    background: var(--forest); border-radius: var(--radius-lg); padding:40px;
    display:flex; flex-direction:column; align-items:center; justify-content:center; gap:14px;
    color:#F5EFE2; text-align:center; min-height:280px;
  }
  .brain-visual .core{
    width:84px; height:84px; border-radius:50%; background:#F5EFE2; color: var(--forest);
    display:flex; align-items:center; justify-content:center; font-weight:800; font-size:14px;
  }
  .brain-visual .tag{ font-size:13.5px; opacity:0.85; max-width:26ch; }

  /* VALUE */
  .value-grid{ display:grid; grid-template-columns: repeat(3,1fr); gap:16px; }
  .value-card{
    background: var(--bg-card); border:1px solid var(--line); border-radius: var(--radius);
    padding:26px; min-height:190px; display:flex; flex-direction:column;
  }
  .value-card .ic{ width:28px; height:28px; color: var(--forest); margin-bottom:16px; }
  .value-card h4{ font-size:16.5px; font-weight:700; margin-bottom:8px; }
  .value-card p{ color: var(--ink-soft); font-size:14px; line-height:1.55; }

  /* DIFFERENTIATION */
  .diff-row{ display:grid; grid-template-columns: repeat(4,1fr); gap:0; border:1px solid var(--line); border-radius: var(--radius); overflow:hidden; background: var(--line);}
  .diff-item{ background: var(--bg-card); padding:28px 22px; }
  .diff-item .num{ font-family:'Lora'; font-style:italic; color: var(--gold); font-size:20px; margin-bottom:12px; display:block;}
  .diff-item h4{ font-size:15.5px; font-weight:700; margin-bottom:8px; }
  .diff-item p{ font-size:13.5px; color: var(--ink-soft); }

  /* USE CASES */
  .usecase-list{ display:flex; flex-direction:column; gap:1px; background: var(--line); border:1px solid var(--line); border-radius: var(--radius); overflow:hidden; }
  .usecase-item{ background: var(--bg-card); padding:22px 26px; display:flex; align-items:center; gap:18px; }
  .usecase-item .dot{ width:8px; height:8px; border-radius:50%; background: var(--clay); flex-shrink:0; }
  .usecase-item p{ font-size:15.5px; }

  /* PROOF */
  .proof-card{
    background: var(--bg-card-alt); border-radius: var(--radius-lg); padding:48px;
    text-align:center; max-width:720px; margin:0 auto; border:1px dashed var(--gold);
  }
  .proof-card q{ font-family:'Lora'; font-style:italic; font-size:22px; color: var(--ink-soft); display:block; margin-bottom:14px; }
  .proof-card .who{ font-size:13px; color: var(--ink-faint); }

  /* FAQ */
  .faq-list{ max-width:760px; }
  details{ border-bottom:1px solid var(--line); padding:22px 0; }
  details:first-child{ border-top:1px solid var(--line); }
  summary{ cursor:pointer; list-style:none; display:flex; justify-content:space-between; align-items:center; font-size:17.5px; font-weight:700; }
  summary::-webkit-details-marker{ display:none; }
  summary::after{ content:"+"; color: var(--clay); font-size:22px; font-weight:400; flex-shrink:0; margin-left:20px; }
  details[open] summary::after{ content:"−"; }
  details p{ color: var(--ink-soft); margin-top:14px; font-size:15px; max-width:62ch; }

  .final-cta{ text-align:center; padding:96px 0 110px; border-top:1px solid var(--line); }
  .final-cta h2{ font-size:36px; margin-bottom:14px; max-width:22ch; margin-left:auto; margin-right:auto;}
  .final-cta p{ color: var(--ink-soft); font-size:16px; margin-bottom:32px; max-width:52ch; margin-left:auto; margin-right:auto;}

  footer{ border-top:1px solid var(--line); padding:30px 0; }
  .footer-inner{ display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:12px; font-size:12.5px; color: var(--ink-faint); }

  @media (max-width:900px){
    .hero-grid, .solution-wrap{ grid-template-columns:1fr; }
    .hero h1{ font-size:34px; }
    .problem-grid, .value-grid{ grid-template-columns:1fr; }
    .diff-row{ grid-template-columns: repeat(2,1fr); }
    section{ padding:60px 0; }
  }
  @media (max-width:560px){
    .diff-row{ grid-template-columns: 1fr; }
    .hero h1{ font-size:28px; }
  }
  @media (prefers-reduced-motion: reduce){ *{ transition:none !important; } }
</style>
</head>
<body>

<header class="nav">
  <div class="wrap nav-inner">
    <div class="logo"><span class="mark">R</span>ROSA AI</div>
    <a href="#cta" class="nav-cta">Đặt lịch demo</a>
  </div>
</header>

<!-- HERO -->
<section class="hero" style="border-top:none;">
  <div class="wrap hero-grid">
    <div>
      <div class="eyebrow">Trợ lý AI cho doanh nghiệp</div>
      <h1 class="display">Trợ lý AI làm việc thay bạn — 24/7, ngay tại công ty.</h1>
      <p class="lede">ROSA AI trả lời khách hàng trên Zalo, Telegram, Facebook Messenger; ghi biên bản và giao việc sau mỗi cuộc họp; tự động hoá quy trình cùng n8n. Tất cả chạy trên AI của riêng công ty bạn — dữ liệu an toàn, không tốn phí theo từng tin nhắn.</p>
      <div class="hero-ctas">
        <a href="#cta" class="btn btn-primary">Đặt lịch demo miễn phí <span class="btn-arrow">→</span></a>
      </div>
      <div class="trust-line">
        <span>AI hiểu tiếng Việt</span>
        <span>Mỗi câu trả lời đều kiểm chứng được nguồn</span>
        <span>Dữ liệu trong mạng nội bộ</span>
      </div>
    </div>

    <div class="hub-frame">
      <svg viewBox="0 0 520 420" xmlns="http://www.w3.org/2000/svg">
        <!-- spokes -->
        <g stroke="#D8CBAA" stroke-width="1.5" fill="none">
          <path d="M260,210 C200,170 150,120 110,70"/>
          <path d="M260,210 C320,170 380,110 430,60"/>
          <path d="M260,210 C190,240 120,260 70,300"/>
          <path d="M260,210 C330,240 400,270 450,320"/>
          <path d="M260,210 C260,270 260,320 260,370"/>
        </g>
        <!-- core -->
        <g transform="translate(260,210)">
          <circle r="56" fill="#2E6B57"/>
          <circle r="56" fill="none" stroke="#C99A3B" stroke-width="2" stroke-dasharray="2 6"/>
          <text y="-4" text-anchor="middle" font-family="Be Vietnam Pro" font-weight="800" font-size="15" fill="#F5EFE2">ROSA</text>
          <text y="14" text-anchor="middle" font-family="Be Vietnam Pro" font-weight="500" font-size="10.5" fill="#F5EFE2" opacity="0.85">bộ não AI</text>
        </g>
        <!-- channel badges -->
        <g font-family="Be Vietnam Pro" font-weight="600" font-size="12" fill="#2B2620">
          <g transform="translate(48,44)">
            <rect width="128" height="40" rx="20" fill="#FDFBF5" stroke="#E0D5BC"/>
            <circle cx="22" cy="20" r="6" fill="#2E6B57"/>
            <text x="38" y="24">Zalo</text>
          </g>
          <g transform="translate(368,32)">
            <rect width="128" height="40" rx="20" fill="#FDFBF5" stroke="#E0D5BC"/>
            <circle cx="22" cy="20" r="6" fill="#2E6B57"/>
            <text x="38" y="24">Telegram</text>
          </g>
          <g transform="translate(10,278)">
            <rect width="150" height="40" rx="20" fill="#FDFBF5" stroke="#E0D5BC"/>
            <circle cx="22" cy="20" r="6" fill="#2E6B57"/>
            <text x="38" y="24">Messenger</text>
          </g>
          <g transform="translate(374,296)">
            <rect width="128" height="40" rx="20" fill="#FDFBF5" stroke="#E0D5BC"/>
            <circle cx="22" cy="20" r="6" fill="#2E6B57"/>
            <text x="38" y="24">Website</text>
          </g>
          <g transform="translate(196,362)">
            <rect width="128" height="40" rx="20" fill="#FDFBF5" stroke="#C1592E"/>
            <circle cx="22" cy="20" r="6" fill="#C1592E"/>
            <text x="38" y="24">24/7</text>
          </g>
        </g>
      </svg>
      <div class="hub-caption">một trợ lý — trả lời ở mọi kênh khách hàng của bạn</div>
    </div>
  </div>
</section>

<!-- PROBLEM -->
<section id="problem">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Vấn đề</div>
      <h2 class="display">Nhân viên của bạn đang lãng phí hàng giờ mỗi ngày.</h2>
    </div>
    <div class="problem-grid">
      <div class="problem-card">
        <svg class="ic" viewBox="0 0 30 30" fill="none"><path d="M6 10h18v12H12l-6 5V10z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M10 15h10M10 19h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        <p>Trả lời cùng một câu hỏi của khách hàng, hàng trăm lần mỗi ngày.</p>
      </div>
      <div class="problem-card">
        <svg class="ic" viewBox="0 0 30 30" fill="none"><circle cx="15" cy="15" r="11" stroke="currentColor" stroke-width="1.6"/><path d="M15 9v6l4 3" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        <p>Khách nhắn tin lúc 11 giờ đêm — không ai trả lời cho đến sáng hôm sau.</p>
      </div>
      <div class="problem-card">
        <svg class="ic" viewBox="0 0 30 30" fill="none"><rect x="6" y="6" width="18" height="14" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M11 24h8M15 20v4" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        <p>Họp xong, quyết định trôi vào quên lãng, việc giao ra không ai theo dõi.</p>
      </div>
      <div class="problem-card">
        <svg class="ic" viewBox="0 0 30 30" fill="none"><rect x="7" y="5" width="16" height="20" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M11 11h8M11 15h8M11 19h5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        <p>Tra cứu số liệu, soạn tài liệu, nhập liệu thủ công… ngày qua ngày.</p>
      </div>
    </div>
  </div>
</section>

<!-- SOLUTION -->
<section id="solution">
  <div class="wrap solution-wrap">
    <div>
      <div class="eyebrow">Giải pháp</div>
      <h2 class="display">ROSA AI — bộ não AI cho cả doanh nghiệp của bạn.</h2>
      <p>ROSA AI là trợ lý AI đặt ngay tại công ty bạn. Nó hiểu tài liệu của bạn, trả lời khách hàng trên mọi kênh, tóm tắt cuộc họp và giao việc — rồi kết nối với n8n để tự động hoá bất cứ quy trình nào.</p>
      <div class="callout-line">"Một bộ não duy nhất, làm việc ở khắp nơi trong doanh nghiệp bạn."</div>
    </div>
    <div class="brain-visual">
      <div class="core">ROSA</div>
      <div class="tag">Trả lời khách · Tóm tắt họp · Giao việc · Tự động hoá quy trình</div>
    </div>
  </div>
</section>

<!-- CORE VALUE -->
<section id="value">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Giá trị cốt lõi</div>
      <h2 class="display">Mọi thứ bạn cần để AI thực sự làm việc</h2>
    </div>
    <div class="value-grid">
      <div class="value-card">
        <svg class="ic" viewBox="0 0 28 28" fill="none"><circle cx="14" cy="14" r="4" stroke="currentColor" stroke-width="1.6"/><circle cx="5" cy="5" r="2.4" stroke="currentColor" stroke-width="1.6"/><circle cx="23" cy="5" r="2.4" stroke="currentColor" stroke-width="1.6"/><circle cx="5" cy="23" r="2.4" stroke="currentColor" stroke-width="1.6"/><circle cx="23" cy="23" r="2.4" stroke="currentColor" stroke-width="1.6"/><path d="M11.5 11.5 L7 7 M16.5 11.5 L21 7 M11.5 16.5 L7 21 M16.5 16.5 L21 21" stroke="currentColor" stroke-width="1.4"/></svg>
        <h4>Một chatbot, mọi kênh</h4>
        <p>Xây một con bot trong ROSA AI rồi đưa lên Zalo, Telegram, Facebook Messenger và website — cùng một trợ lý thông minh trả lời khách ở khắp nơi.</p>
      </div>
      <div class="value-card">
        <svg class="ic" viewBox="0 0 28 28" fill="none"><path d="M6 8h16v10H12l-4 4V8z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M10.5 13l2 2 4-4.5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h4>AI cho bạn thấy bằng chứng</h4>
        <p>Mỗi câu trả lời đều chỉ rõ nguồn trong tài liệu gốc — bạn không phải lo AI "bịa".</p>
      </div>
      <div class="value-card">
        <svg class="ic" viewBox="0 0 28 28" fill="none"><rect x="5" y="5" width="18" height="18" rx="2" stroke="currentColor" stroke-width="1.6"/><path d="M5 12h18M11 5v18" stroke="currentColor" stroke-width="1.6"/></svg>
        <h4>Hỏi Excel như hỏi kế toán</h4>
        <p>Đặt câu hỏi về file số liệu và nhận đúng từng con số, đúng định dạng tiếng Việt.</p>
      </div>
      <div class="value-card">
        <svg class="ic" viewBox="0 0 28 28" fill="none"><circle cx="14" cy="14" r="10" stroke="currentColor" stroke-width="1.6"/><path d="M10 14l2.5 2.5L18 11" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"/></svg>
        <h4>Họp xong là có biên bản + giao việc</h4>
        <p>Ghi âm hoặc tải lên cuộc họp — AI tóm tắt, rút ra quyết định, và giao việc đúng người kèm hạn chót.</p>
      </div>
      <div class="value-card">
        <svg class="ic" viewBox="0 0 28 28" fill="none"><path d="M14 4 L22 8 L22 16 L14 24 L6 16 L6 8 Z" stroke="currentColor" stroke-width="1.6" stroke-linejoin="round"/><path d="M14 12v6M11 15h6" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/></svg>
        <h4>Tự động hoá cùng n8n</h4>
        <p>Nhắc lịch hẹn, theo dõi giá đối thủ, xử lý tài liệu, đồng bộ dữ liệu — để hệ thống tự chạy.</p>
      </div>
      <div class="value-card">
        <svg class="ic" viewBox="0 0 28 28" fill="none"><rect x="4" y="9" width="8" height="15" rx="1.6" stroke="currentColor" stroke-width="1.6"/><rect x="16" y="4" width="8" height="20" rx="1.6" stroke="currentColor" stroke-width="1.6"/></svg>
        <h4>Mỗi phòng ban một AI riêng</h4>
        <p>Phân quyền theo phòng ban: bot chăm sóc khách, bot nội bộ, bot HR — mỗi bot một nhiệm vụ.</p>
      </div>
    </div>
  </div>
</section>

<!-- DIFFERENTIATION -->
<section id="diff">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Điểm khác biệt</div>
      <h2 class="display">Không chỉ là một chatbot.</h2>
    </div>
    <div class="diff-row">
      <div class="diff-item">
        <span class="num">01</span>
        <h4>Đặt tại công ty bạn</h4>
        <p>Dữ liệu không rời khỏi mạng nội bộ.</p>
      </div>
      <div class="diff-item">
        <span class="num">02</span>
        <h4>Hiểu tiếng Việt sâu sắc</h4>
        <p>Trả lời chuẩn tiếng Việt — kể cả khi khách gõ không dấu.</p>
      </div>
      <div class="diff-item">
        <span class="num">03</span>
        <h4>Trả lời có nguồn</h4>
        <p>AI đáng tin, luôn kiểm chứng được — không bịa đặt.</p>
      </div>
      <div class="diff-item">
        <span class="num">04</span>
        <h4>Chi phí cố định</h4>
        <p>Tự động hoá bao nhiêu tuỳ thích, không tính phí theo từng tin nhắn.</p>
      </div>
    </div>
  </div>
</section>

<!-- USE CASES -->
<section id="usecases">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Ứng dụng thực tế</div>
      <h2 class="display">ROSA AI có thể làm gì cho bạn?</h2>
    </div>
    <div class="usecase-list">
      <div class="usecase-item"><span class="dot"></span><p>Chăm sóc khách hàng tự động trên Zalo &amp; Facebook, 24/7.</p></div>
      <div class="usecase-item"><span class="dot"></span><p>Tổng đài hỏi–đáp nội bộ: nhân viên hỏi quy trình, chính sách, tài liệu — trả lời tức thì.</p></div>
      <div class="usecase-item"><span class="dot"></span><p>Biên bản họp và giao việc tự động.</p></div>
      <div class="usecase-item"><span class="dot"></span><p>Nhắc lịch hẹn khách hàng qua Zalo ZNS / SMS.</p></div>
      <div class="usecase-item"><span class="dot"></span><p>Theo dõi giá đối thủ, tổng hợp báo cáo tự động.</p></div>
    </div>
  </div>
</section>

<!-- PROOF -->
<section id="proof">
  <div class="wrap">
    <div class="section-head" style="margin:0 auto 44px; text-align:center;">
      <div class="eyebrow" style="justify-content:center;">Bằng chứng</div>
    </div>
    <div class="proof-card">
      <q>"Khu vực chèn câu chuyện khách hàng thật, cùng ảnh chụp màn hình bot đang chạy trên Zalo hoặc biên bản họp được tạo tự động."</q>
      <div class="who">— [ TÊN DOANH NGHIỆP ] · chờ nội dung thật</div>
    </div>
  </div>
</section>

<!-- FAQ -->
<section id="faq">
  <div class="wrap">
    <div class="section-head">
      <div class="eyebrow">Câu hỏi thường gặp</div>
    </div>
    <div class="faq-list">
      <details open>
        <summary>AI làm việc bằng tiếng Việt có tốt không?</summary>
        <p>Có. ROSA AI được tối ưu cho tiếng Việt, cả văn bản lẫn giọng nói.</p>
      </details>
      <details>
        <summary>Dữ liệu của tôi có an toàn không?</summary>
        <p>Dữ liệu công ty không rời khỏi mạng nội bộ của bạn.</p>
      </details>
      <details>
        <summary>AI có trả lời sai hay bịa không?</summary>
        <p>Nhiều lớp chống bịa, và mọi câu trả lời đều kiểm chứng được nguồn trong tài liệu gốc.</p>
      </details>
      <details>
        <summary>Chúng tôi không rành kỹ thuật, có dùng được không?</summary>
        <p>Được. Đội ROSA sẽ cài đặt và cấu hình sẵn các quy trình cho bạn.</p>
      </details>
    </div>
  </div>
</section>

<!-- FINAL CTA -->
<section id="cta" class="final-cta">
  <div class="wrap">
    <div class="eyebrow" style="justify-content:center;">Kêu gọi hành động</div>
    <h2 class="display">Để AI làm việc. Bạn tập trung phát triển doanh nghiệp.</h2>
    <p>Đặt lịch demo miễn phí — xem ROSA AI chạy thử ngay trên tài liệu và quy trình của chính bạn.</p>
    <a href="#" class="btn btn-primary">Đặt lịch demo miễn phí <span class="btn-arrow">→</span></a>
  </div>
</section>

<footer>
  <div class="wrap footer-inner">
    <div class="logo" style="font-size:15px;"><span class="mark" style="width:20px;height:20px;font-size:11px;">R</span>ROSA AI</div>
    <span>© 2026 · Trang dành cho nhóm Người vận hành / SME</span>
  </div>
</footer>

</body>
</html>