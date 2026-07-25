<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="UTF-8">
  <title>Phân tích hiệu quả đầu tư — ROSA AI Platform</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@500;600;700&family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
      --bg: #0A0D10;
      --panel: #12161B;
      --panel-2: #171C22;
      --line: #262D35;
      --lime: #9FE81E;
      --lime-dim: #6C9E17;
      --blue: #4FA6FF;
      --orange: #FFA23C;
      --red: #FF5C5C;
      --ink: #F2F4F6;
      --ink-dim: #9AA4AE;
      --ink-faint: #5C6670;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      background: var(--bg);
      color: var(--ink);
      font-family: 'Inter', sans-serif;
      padding: 48px 20px 80px;
    }

    .wrap {
      max-width: 1180px;
      margin: 0 auto;
    }

    /* ---------- Header ---------- */
    .eyebrow {
      display: flex;
      align-items: center;
      gap: 14px;
      margin-bottom: 18px;
    }

    .num-badge {
      width: 40px;
      height: 40px;
      background: var(--lime);
      color: #0A0D10;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Space Grotesk';
      font-weight: 700;
      font-size: 20px;
      border-radius: 8px;
      flex-shrink: 0;
    }

    .eyebrow h1 {
      font-family: 'Space Grotesk';
      font-weight: 700;
      font-size: 28px;
      letter-spacing: 0.3px;
      text-transform: uppercase;
      color: #fff;
    }

    .rule {
      height: 2px;
      background: linear-gradient(90deg, var(--lime), transparent);
      margin-bottom: 32px;
    }

    .thesis {
      font-family: 'Space Grotesk';
      font-weight: 700;
      font-size: 38px;
      line-height: 1.25;
      max-width: 880px;
      margin-bottom: 14px;
    }

    .thesis .hi {
      color: var(--lime);
    }

    .subtext {
      color: var(--ink-dim);
      font-size: 16px;
      line-height: 1.7;
      max-width: 820px;
      margin-bottom: 44px;
    }

    .subtext b {
      color: var(--ink);
      font-weight: 600;
    }

    .subtext .redtag {
      color: var(--red);
      font-weight: 700;
    }

    .subtext .bluetag {
      color: var(--lime);
      font-weight: 700;
    }

    .subtext .term {
      border-bottom: 1px dashed var(--ink-faint);
      cursor: help;
    }

    /* ---------- Quick-read strip ---------- */
    .quickread {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 16px;
      margin-bottom: 32px;
    }

    .qr-step {
      background: var(--panel);
      border: 1px solid var(--line);
      border-radius: 14px;
      padding: 20px 20px 22px;
      position: relative;
    }

    .qr-step .qr-num {
      font-family: 'Space Grotesk';
      font-size: 14px;
      font-weight: 700;
      color: var(--lime);
      letter-spacing: 1px;
      margin-bottom: 10px;
      display: block;
    }

    .qr-step .qr-icon {
      font-size: 26px;
      margin-bottom: 10px;
      display: block;
    }

    .qr-step .qr-title {
      font-family: 'Space Grotesk';
      font-size: 16px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 6px;
    }

    .qr-step .qr-desc {
      font-size: 14px;
      color: var(--ink-dim);
      line-height: 1.6;
    }

    .qr-step.qr-red .qr-title {
      color: var(--red);
    }

    .qr-step.qr-lime .qr-title {
      color: var(--lime);
    }

    @media (max-width: 760px) {
      .quickread {
        grid-template-columns: 1fr;
      }
    }

    /* ---------- Term glossary ---------- */
    .glossary {
      display: flex;
      gap: 16px;
      flex-wrap: wrap;
      margin-bottom: 44px;
    }

    .glos-item {
      flex: 1;
      min-width: 260px;
      background: var(--panel);
      border: 1px solid var(--line);
      border-radius: 12px;
      padding: 16px 18px;
    }

    .glos-item .glos-label {
      font-family: 'Space Grotesk';
      font-size: 14px;
      font-weight: 700;
      color: var(--lime);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 6px;
    }

    .glos-item p {
      font-size: 14px;
      color: var(--ink-dim);
      line-height: 1.6;
    }

    /* ---------- Chart section ---------- */
    .chart-card {
      background: var(--panel);
      border: 1px solid var(--line);
      border-radius: 16px;
      padding: 32px 36px 24px;
      margin-bottom: 56px;
    }

    .chart-head {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      flex-wrap: wrap;
      gap: 16px;
      margin-bottom: 8px;
    }

    .chart-head h2 {
      font-family: 'Space Grotesk';
      font-size: 18px;
      font-weight: 700;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .legend {
      display: flex;
      gap: 22px;
      flex-wrap: wrap;
    }

    .legend-item {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 14px;
      color: var(--ink-dim);
    }

    .legend-dot {
      width: 12px;
      height: 12px;
      border-radius: 3px;
    }

    .chart-svg {
      width: 100%;
      height: auto;
      display: block;
      margin-top: 12px;
    }

    .breakeven-callout {
      display: flex;
      align-items: center;
      gap: 14px;
      background: rgba(159, 232, 30, 0.08);
      border: 1px solid rgba(159, 232, 30, 0.35);
      border-radius: 10px;
      padding: 14px 18px;
      margin-top: 18px;
    }

    .breakeven-callout .icon {
      font-size: 20px;
    }

    .breakeven-callout p {
      font-size: 15px;
      color: var(--ink);
      line-height: 1.6;
    }

    .breakeven-callout b {
      color: var(--lime);
    }

    /* ---------- Section label ---------- */
    .section-divider {
      display: flex;
      align-items: center;
      gap: 16px;
      margin-bottom: 28px;
    }

    .section-divider::before,
    .section-divider::after {
      content: '';
      flex: 1;
      height: 1px;
      background: var(--line);
    }

    .section-divider span {
      font-family: 'Space Grotesk';
      font-size: 15px;
      font-weight: 600;
      letter-spacing: 1.5px;
      color: var(--ink-dim);
      text-transform: uppercase;
      white-space: nowrap;
    }

    /* ---------- Table ---------- */
    .table-wrap {
      background: var(--panel);
      border: 1px solid var(--line);
      border-radius: 16px;
      overflow: hidden;
      margin-bottom: 40px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    thead th {
      background: var(--panel-2);
      text-align: left;
      padding: 22px 20px;
      border-bottom: 1px solid var(--line);
      vertical-align: top;
    }

    thead th:first-child {
      width: 190px;
      background: var(--panel);
    }

    thead th .col-icon {
      width: 38px;
      height: 38px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      margin-bottom: 10px;
    }

    thead th.col-dev .col-icon {
      background: rgba(159, 232, 30, 0.15);
      color: var(--lime);
    }

    thead th.col-mkt .col-icon {
      background: rgba(79, 166, 255, 0.15);
      color: var(--blue);
    }

    thead th.col-ent .col-icon {
      background: rgba(255, 162, 60, 0.15);
      color: var(--orange);
    }

    thead th .col-title {
      font-family: 'Space Grotesk';
      font-size: 16px;
      font-weight: 700;
      color: #fff;
      margin-bottom: 6px;
      text-transform: uppercase;
      letter-spacing: 0.3px;
    }

    thead th .col-desc {
      font-size: 14px;
      color: var(--ink-dim);
      line-height: 1.6;
      font-weight: 400;
    }

    tbody td,
    tbody th {
      padding: 18px 20px;
      border-bottom: 1px solid var(--line);
      font-size: 15px;
      vertical-align: top;
      line-height: 1.6;
    }

    tbody th {
      text-align: left;
      background: var(--panel-2);
      color: var(--ink-dim);
      font-weight: 600;
      font-size: 14px;
      white-space: nowrap;
    }

    tbody th .row-icon {
      margin-right: 8px;
    }

    tbody tr:last-child td,
    tbody tr:last-child th {
      border-bottom: none;
    }

    tbody td.strong {
      font-family: 'Space Grotesk';
      font-weight: 700;
      font-size: 17px;
    }

    .col-dev-cell.strong {
      color: var(--lime);
    }

    .col-mkt-cell.strong {
      color: var(--blue);
    }

    .col-ent-cell.strong {
      color: var(--orange);
    }

    tbody td .time-tag {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      font-family: 'Space Grotesk';
      font-weight: 700;
      font-size: 16px;
      color: var(--red);
    }

    tbody tr:nth-child(odd) td,
    tbody tr:nth-child(odd) th {
      background: rgba(255, 255, 255, 0.012);
    }

    /* ---------- Closing banner ---------- */
    .closer {
      background: linear-gradient(135deg, rgba(159, 232, 30, 0.12), rgba(159, 232, 30, 0.02));
      border: 1px solid rgba(159, 232, 30, 0.3);
      border-radius: 16px;
      padding: 30px 34px;
      display: flex;
      align-items: center;
      gap: 22px;
      flex-wrap: wrap;
    }

    .closer .mark {
      font-family: 'Space Grotesk';
      font-size: 44px;
      font-weight: 700;
      color: var(--lime);
      line-height: 1;
    }

    .closer p {
      font-size: 16px;
      color: var(--ink);
      line-height: 1.7;
      max-width: 760px;
    }

    .closer b {
      color: var(--lime);
    }

    @media (max-width: 760px) {
      .thesis {
        font-size: 26px;
      }

      thead th:first-child {
        display: none;
      }

      tbody th {
        white-space: normal;
      }
    }
  </style>
</head>

<body>
  <div class="wrap">

    <!-- HEADER -->
    <div class="eyebrow">
      <div class="num-badge">5</div>
      <h1>Phân tích hiệu quả đầu tư</h1>
    </div>
    <div class="rule"></div>

    <div class="thesis">Trả phí AI Cloud <span class="hi">mãi mãi</span>, hay đầu tư một lần để <span class="hi">sở
        hữu AI vĩnh viễn</span>?</div>
    <p class="subtext">
      Một doanh nghiệp dùng <b>AI Cloud</b> cho đội ngũ phát triển, marketing và vận hành đang trả
      <span class="redtag">10–15 triệu đồng/tháng</span> — chỉ riêng chi phí token, và khoản này lặp lại vô thời
      hạn.
      Nếu thay bằng <b>ROSA AI Platform</b> kết hợp hạ tầng phần cứng AI nội bộ, tổng chi phí khoảng
      <span class="bluetag">180 triệu đồng</span> — trả một lần duy nhất, dùng vĩnh viễn, không phát sinh phí theo
      tháng.
    </p>

    <!-- QUICK READ: 3 bước hiểu ngay -->
    <div class="quickread">
      <div class="qr-step qr-red">
        <span class="qr-num">BƯỚC 1</span>
        <span class="qr-icon">🧾</span>
        <div class="qr-title">Đang thuê AI theo tháng</div>
        <div class="qr-desc">Doanh nghiệp trả 10–15 triệu/tháng để dùng AI Cloud. Không dùng nữa thì thôi, còn
          dùng là còn trả — không có điểm dừng.</div>
      </div>
      <div class="qr-step">
        <span class="qr-num">BƯỚC 2</span>
        <span class="qr-icon">⚖️</span>
        <div class="qr-title">Cộng dồn theo thời gian</div>
        <div class="qr-desc">Càng dùng lâu, tổng tiền thuê càng phình to. Đến một thời điểm, số tiền đã trả sẽ
          bằng đúng 180 triệu.</div>
      </div>
      <div class="qr-step qr-lime">
        <span class="qr-num">BƯỚC 3</span>
        <span class="qr-icon">🏆</span>
        <div class="qr-title">Mua đứt 1 lần, dùng mãi</div>
        <div class="qr-desc">Trả 180 triệu một lần cho ROSA AI Platform, sở hữu luôn — từ đó về sau không tốn
          thêm đồng nào nữa.</div>
      </div>
    </div>

    <!-- GLOSSARY: giải nghĩa thuật ngữ cho người không rành kỹ thuật -->
    <div class="glossary">
      <div class="glos-item">
        <div class="glos-label">AI Cloud là gì?</div>
        <p>Là hình thức <b style="color:var(--ink)">"thuê AI online theo tháng"</b> — giống như trả tiền mạng,
          tiền điện. Nhà cung cấp tính phí dựa trên lượng dùng (gọi là "token"), dùng càng nhiều trả càng
          nhiều, dùng vô thời hạn thì trả vô thời hạn.</p>
      </div>
      <div class="glos-item">
        <div class="glos-label">ROSA AI Platform là gì?</div>
        <p>Là <b style="color:var(--ink)">hệ thống AI cài đặt riêng tại doanh nghiệp</b> (giống mua đứt một cái
          máy). Trả tiền phần cứng + phần mềm một lần, sau đó doanh nghiệp toàn quyền sử dụng, không phải trả
          phí theo tháng nữa.</p>
      </div>
    </div>

    <!-- CHART -->
    <div class="chart-card">
      <div class="chart-head">
        <h2>Đường hòa vốn: thuê theo tháng vs. đầu tư một lần</h2>
        <div class="legend">
          <div class="legend-item"><span class="legend-dot" style="background:var(--red)"></span>Chi phí AI
            Cloud (cộng dồn theo tháng)</div>
          <div class="legend-item"><span class="legend-dot" style="background:var(--lime)"></span>Đầu tư ROSA
            AI Platform (một lần)</div>
        </div>
      </div>

      <svg class="chart-svg" viewBox="0 0 800 400" xmlns="http://www.w3.org/2000/svg">
        <!-- gridlines -->
        <g stroke="#262D35" stroke-width="1">
          <line x1="80" y1="340" x2="720" y2="340" />
          <line x1="80" y1="280" x2="720" y2="280" />
          <line x1="80" y1="220" x2="720" y2="220" />
          <line x1="80" y1="160" x2="720" y2="160" />
          <line x1="80" y1="100" x2="720" y2="100" />
          <line x1="80" y1="40" x2="720" y2="40" />
        </g>
        <!-- y axis labels (triệu đồng) -->
        <g fill="#5C6670" font-family="Inter" font-size="12">
          <text x="66" y="344" text-anchor="end">0</text>
          <text x="66" y="284" text-anchor="end">50</text>
          <text x="66" y="224" text-anchor="end">100</text>
          <text x="66" y="164" text-anchor="end">150</text>
          <text x="66" y="104" text-anchor="end">200</text>
          <text x="66" y="44" text-anchor="end">250tr</text>
        </g>
        <!-- x axis labels (months) -->
        <g fill="#5C6670" font-family="Inter" font-size="12" text-anchor="middle">
          <text x="80" y="362">T0</text>
          <text x="187" y="362">T3</text>
          <text x="293" y="362">T6</text>
          <text x="400" y="362">T9</text>
          <text x="507" y="362">T12</text>
          <text x="613" y="362">T15</text>
          <text x="720" y="362">T18</text>
        </g>

        <!-- savings zone after breakeven -->
        <polygon points="592,124 720,70 720,124" fill="#9FE81E" opacity="0.12" />

        <!-- one-time investment flat line -->
        <line x1="80" y1="124" x2="720" y2="124" stroke="#9FE81E" stroke-width="3" />
        <text x="88" y="114" fill="#9FE81E" font-family="Space Grotesk" font-size="13" font-weight="700">180
          triệu — trả 1 lần</text>

        <!-- recurring cost line -->
        <polyline points="80,340 151,310 222,280 293,250 364,220 436,190 507,160 578,130 720,70" fill="none"
          stroke="#FF5C5C" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />

        <!-- breakeven point -->
        <circle cx="592" cy="124" r="6" fill="#0A0D10" stroke="#FFFFFF" stroke-width="2" />
        <line x1="592" y1="124" x2="592" y2="340" stroke="#FFFFFF" stroke-width="1" stroke-dasharray="4 4"
          opacity="0.4" />
        <text x="592" y="356" fill="#FFFFFF" font-family="Space Grotesk" font-size="12" font-weight="700"
          text-anchor="middle">Hòa vốn ≈ T14</text>

        <!-- plain-language zone labels -->
        <text x="200" y="270" fill="#FF5C5C" font-family="Inter" font-size="12.5" font-weight="600">↓ Vùng đang
          trả tiền thuê hàng tháng</text>
        <text x="608" y="90" fill="#9FE81E" font-family="Space Grotesk" font-size="12.5" font-weight="700">Vùng
          đã hòa vốn — bắt đầu tiết kiệm →</text>
      </svg>

      <div class="breakeven-callout">
        <span class="icon">⏱</span>
        <p>Sau khoảng <b>14 tháng</b>, tổng tiền đã trả cho AI Cloud vượt qua khoản đầu tư một lần 180 triệu.
          Từ mốc đó trở đi, mỗi tháng dùng AI Cloud là một tháng <b>trả thêm tiền cho thứ lẽ ra đã miễn
            phí</b> nếu đầu tư ROSA từ đầu.</p>
      </div>
    </div>

    <!-- TABLE -->
    <div class="section-divider"><span>Chi tiết theo từng nhóm triển khai</span></div>

    <div class="table-wrap">
      <table>
        <thead>
          <tr>
            <th></th>
            <th class="col-dev">
              <div class="col-icon">💻</div>
              <div class="col-title">Lập trình viên</div>
              <div class="col-desc">Xây dựng giải pháp bán hàng thông minh với trải nghiệm cá nhân hóa
              </div>
            </th>
            <th class="col-mkt">
              <div class="col-icon">🎨</div>
              <div class="col-title">Marketing &amp; Thiết kế</div>
              <div class="col-desc">Sáng tạo chiến lược truyền thông và thiết kế, tối ưu vận hành</div>
            </th>
            <th class="col-ent">
              <div class="col-icon">🏢</div>
              <div class="col-title">Doanh nghiệp</div>
              <div class="col-desc">Tối ưu quy trình vận hành, nâng cao năng lực cạnh tranh</div>
            </th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th><span class="row-icon">👥</span>Quy mô</th>
            <td>5 – 10 người</td>
            <td>10 – 20 người</td>
            <td>20 – 50+ người</td>
          </tr>
          <tr>
            <th><span class="row-icon">📦</span>Gói AI</th>
            <td>Claude Max / Codex Pro / Cursor Pro</td>
            <td>GPT Image, Photoshop, ComfyUI API, Video AI (King AI, VEO, SORA)</td>
            <td>Chatbot, RAG, Automation đa phòng ban hoặc 1–2 nhân sự CSKH trực 24/7, nhân sự tổng hợp báo
              cáo</td>
          </tr>
          <tr>
            <th><span class="row-icon">💰</span>Chi phí AI Cloud</th>
            <td class="col-dev-cell strong">15 – 50 tr/tháng</td>
            <td class="col-mkt-cell strong">10 – 20 tr/tháng</td>
            <td class="col-ent-cell strong">20 – 30 tr/tháng</td>
          </tr>
          <tr>
            <th><span class="row-icon">📈</span>Hiệu quả kỳ vọng</th>
            <td>Đầu tư một lần ~180tr, không phát sinh chi phí token</td>
            <td>Tạo ảnh, video AI không giới hạn trên hạ tầng nội bộ</td>
            <td>AI dùng chung toàn doanh nghiệp, tư vấn CSKH 24/7, ghi âm &amp; tóm tắt cuộc gọi, không phát
              sinh phí theo người dùng hoặc token</td>
          </tr>
          <tr>
            <th><span class="row-icon">⏱</span>Thời gian thu hồi vốn</th>
            <td><span class="time-tag">&lt; 12 tháng</span></td>
            <td><span class="time-tag">&lt; 18 tháng</span></td>
            <td><span class="time-tag">&lt; 9 tháng</span></td>
          </tr>
          <tr>
            <th><span class="row-icon">🙋</span>Phù hợp với ai?</th>
            <td>Team dev cần công cụ code AI riêng, không muốn phụ thuộc gói thuê ngoài</td>
            <td>Team content/thiết kế cần tạo ảnh, video số lượng lớn, không lo giới hạn dung lượng</td>
            <td>Công ty muốn 1 hệ thống AI dùng chung toàn công ty, thu hồi vốn nhanh nhất</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- CLOSER -->
    <div class="closer">
      <div class="mark">→</div>
      <p>Dù triển khai theo nhóm nào, khoản đầu tư ~180 triệu đồng đều được thu hồi trong
        <b>dưới 18 tháng</b>. Sau mốc đó, doanh nghiệp <b>sở hữu AI vĩnh viễn</b> — không còn hóa đơn token
        hàng tháng, trong khi mô hình thuê AI Cloud tiếp tục tính phí không giới hạn thời gian.
      </p>
    </div>

  </div>
</body>

</html>