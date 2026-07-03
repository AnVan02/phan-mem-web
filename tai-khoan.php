
<link rel="stylesheet" href="assets/css/tai-khoan.css">
   <div class="main-content layout-contact">
      <div class="page-header contact-header">
         <h1 class="page-title">Đăng nhập để nhận thông báo khuyến mãi</h1>
         <p class="page-subtitle">
            Đăng nhập vào tài khoản để nhận thông tin về sản phẩm mới, chương trình khuyến mãi và các ưu đãi hấp dẫn từ cửa hàng.
         </p>
      </div>

   <?php if (isset($success_message) && $success_message): ?>
      <div style="background-color: #ecfdf5; border: 1px solid #a7f3d0; padding: 16px 20px; border-radius: 12px; margin-bottom: 30px; display: flex; align-items: center; gap: 12px; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);">
         <i class="fa-solid fa-circle-check" style="color: #10b981; font-size: 24px;"></i>
         <span style="color: #064e3b; font-size: 16px; font-weight: 500;">
            Đăng nhập thành công! Bạn sẽ nhận được thông báo về các chương trình khuyến mãi và ưu đãi mới nhất.
         </span>
      </div>
   <?php endif; ?>
   <div class="contact-grid">
      <!-- Left Column: Form -->
      <div class="contact-card form-card">
         <div class="card-title">
            <i class="fa-regular fa-envelope"></i> Đăng ký tài khoản 
         </div>
         
         <form class="contact-form" action="" method="POST">
            <div class="form-row">
               <div class="form-group">
                  <label for="customer_name">Họ và tên</label>
                  <input type="text" name="customer_name" id="customer_name" placeholder="Tên khách hàng" required>
               </div>
               <div class="form-group">
                  <label for="customer_email">Email</label>
                  <input type="email" name="customer_email" id="customer_email" placeholder="Email" required>
               </div>
            </div>
            
            <div class="form-group">
               <label for="customer_phone">Số điện thoại</label>
               <input type="text" name="customer_phone" id="customer_phone" placeholder="Số điện thoại của bạn" required>
            </div>
            
            <div class="form-group">
               <label for="customer_address">Nội dung / Địa chỉ</label>
               <textarea name="customer_address" id="customer_address" rows="6" placeholder="Nhập nội dung chi tiết..." required></textarea>
            </div>
            
            <button type="submit" name="customer_add" class="btn-submit">
               Đăng ký &nbsp;<i class="fa-regular fa-paper-plane"></i>
            </button>
         </form>
      </div>

      <!-- Right Column -->
      <div class="contact-right-col">
         <!-- Contact Info -->
         <div class="contact-card info-card">
             <div class="card-title">
                <i class="fa-regular fa-calendar-lines" style="color: #2563EB;"></i> Thông tin liên hệ
             </div>
             
             <div class="info-list">
                <!-- Dia chi -->
                <div class="info-item">
                   <div class="info-icon">
                      <i class="fa-solid fa-location-dot"></i>
                   </div>
                   <div class="info-content">
                      <div class="info-label">Địa chỉ</div>
                      <div class="info-text">150Ter Bùi Thị Xuân, Phường Bến Thành,<br>Quận 1, TP.HCM</div>
                   </div>
                </div>
                
                <!-- Email -->
                <div class="info-item">
                   <div class="info-icon">
                      <i class="fa-regular fa-envelope"></i>
                   </div>
                   <div class="info-content">
                      <div class="info-label">Email hỗ trợ</div>
                      <div class="info-text">support@vietsontdc.com</div>
                   </div>
                </div>
                
                <!-- Hotline -->
                <div class="info-item">
                   <div class="info-icon">
                      <i class="fa-solid fa-phone"></i>
                   </div>
                   <div class="info-content">
                      <div class="info-label">Hotline</div>
                      <div class="info-text">(028) 3929 3770<br>(028) 3929 3765</div>
                   </div>
                </div>
             </div>
         </div>
         
         <!-- Map Card -->
         <div class="contact-card map-card">
            <div class="map-container" style="padding: 0; background: transparent;">
               <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.5496399842823!2d106.68491307573582!3d10.769150259326477!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752f55d6772055%3A0xc89a20fe4db883fa!2zQ8O0bmcgdHkgQ-G7lSBQaOG6p24gVGluIEjhu41jIFZp4bq_dCBTxqFu!5e0!3m2!1svi!2s!4v1772254314094!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
         </div>
      </div>
   </div>
</div>

