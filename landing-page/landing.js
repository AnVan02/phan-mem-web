    // Menu di động (hamburger)
    const navToggle = document.querySelector('.nav-toggle');
    const navLinks = document.querySelector('.nav-links');

    navToggle.addEventListener('click', () => {
        const isOpen = navLinks.classList.toggle('active');
        navToggle.setAttribute('aria-expanded', isOpen);
        navToggle.innerHTML = isOpen ? '<i class="ph ph-x"></i>' : '<i class="ph ph-list"></i>';
    });

    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('active');
            navToggle.setAttribute('aria-expanded', 'false');
            navToggle.innerHTML = '<i class="ph ph-list"></i>';
        });
    });

    const modal = document.getElementById('contactModal');
    const closeBtn = document.querySelector('.close-modal');
    const overlay = document.querySelector('.modal-overlay');
    
    // Lấy tất cả các nút có nội dung Liên hệ hoặc Nhận tư vấn
    const contactButtons = document.querySelectorAll('a[href="#"], .btn-primary, .btn-outline');

    contactButtons.forEach(btn => {
        if (btn.closest('#contactModal')) return; // Bỏ qua các nút bên trong modal (vd: nút Gửi)
        if (btn.innerText.includes('Liên hệ') || btn.innerText.includes('tư vấn') || btn.innerText.includes('demo')) {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                modal.classList.add('active');
                document.body.style.overflow = 'hidden'; // Ngăn cuộn trang khi mở modal
            });
        }
    });

    // Đóng modal
    const closeModal = () => {
        modal.classList.remove('active');
        document.body.style.overflow = 'auto';
    };

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);

    // Xử lý gửi Form
    document.getElementById('consultationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Đang gửi...';

        fetch('luu-lien-he.php', {
            method: 'POST',
            body: new FormData(form)
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert('Cảm ơn bạn! ROSA đã nhận được thông tin và sẽ liên hệ sớm.');
                    form.reset();
                    closeModal();
                } else {
                    alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                }
            })
            .catch(() => {
                alert('Không thể gửi yêu cầu, vui lòng kiểm tra kết nối và thử lại.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
    });
