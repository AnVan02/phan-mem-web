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
    const modalHeader = modal.querySelector('.modal-header');
    const formSuccess = document.getElementById('formSuccess');
    const formSuccessCloseBtn = document.querySelector('.form-success-close');

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
        modalHeader.style.display = '';
        document.getElementById('consultationForm').style.display = '';
        formSuccess.classList.remove('active');
    };

    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    formSuccessCloseBtn.addEventListener('click', closeModal);

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
                    form.reset();
                    modalHeader.style.display = 'none';
                    form.style.display = 'none';
                    formSuccess.classList.add('active');
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

    // Sản phẩm hero: carousel 1 ảnh lớn, tự động chuyển, dừng khi hover, có mũi tên/chấm điều hướng
    const productCarousel = document.querySelector('.product-carousel');
    if (productCarousel) {
        const carouselSlides = Array.from(productCarousel.querySelectorAll('.carousel-slide'));
        const carouselDots = Array.from(productCarousel.querySelectorAll('.carousel-dot'));
        const carouselPrev = productCarousel.querySelector('.carousel-prev');
        const carouselNext = productCarousel.querySelector('.carousel-next');
        const CAROUSEL_AUTO_MS = 3500;
        let carouselIndex = 0;
        let carouselTimer = null;

        const goToSlide = (index) => {
            carouselIndex = (index + carouselSlides.length) % carouselSlides.length;
            carouselSlides.forEach((el, i) => el.classList.toggle('active', i === carouselIndex));
            carouselDots.forEach((el, i) => el.classList.toggle('active', i === carouselIndex));
        };

        const nextSlide = () => goToSlide(carouselIndex + 1);
        const prevSlide = () => goToSlide(carouselIndex - 1);

        const startCarouselAuto = () => {
            stopCarouselAuto();
            carouselTimer = setInterval(nextSlide, CAROUSEL_AUTO_MS);
        };

        const stopCarouselAuto = () => {
            if (carouselTimer) clearInterval(carouselTimer);
            carouselTimer = null;
        };

        startCarouselAuto();
        productCarousel.addEventListener('mouseenter', stopCarouselAuto);
        productCarousel.addEventListener('mouseleave', startCarouselAuto);

        if (carouselNext) carouselNext.addEventListener('click', nextSlide);
        if (carouselPrev) carouselPrev.addEventListener('click', prevSlide);
        carouselDots.forEach((dot, i) => dot.addEventListener('click', () => goToSlide(i)));
    }
