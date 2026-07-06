document.addEventListener("DOMContentLoaded", function () {

    // ==========================
    // Đếm ngược khuyến mãi
    // ==========================
    const countdownEls = document.querySelectorAll("[data-countdown-endofday]");

    if (countdownEls.length > 0) {

        function padSo(n) {
            return String(n).padStart(2, "0");
        }

        function capNhat() {

            const now = new Date();

            const hetHan = new Date(
                now.getFullYear(),
                now.getMonth(),
                now.getDate(),
                23,
                59,
                59
            );

            const conLai = Math.max(0, hetHan - now);

            const gio = Math.floor(conLai / 3600000);
            const phut = Math.floor((conLai % 3600000) / 60000);
            const giay = Math.floor((conLai % 60000) / 1000);

            const text =
                padSo(gio) + ":" +
                padSo(phut) + ":" +
                padSo(giay);

            countdownEls.forEach(function (el) {
                el.textContent = text;
            });
        }

        capNhat();
        setInterval(capNhat, 1000);

    }

});

// banner 
document.addEventListener('DOMContentLoaded', function () {
    const slider = document.querySelector('.hero-slider');
    if (!slider) return;

    const slides = slider.querySelectorAll('.hero-slide');
    const prevBtn = slider.querySelector('.hero-arrow-prev');
    const nextBtn = slider.querySelector('.hero-arrow-next');
    let currentIndex = 0;
    let autoPlayTimer = null;

    if (slides.length === 0) return;

    function goToSlide(index) {
        slides[currentIndex].classList.remove('active');
        currentIndex = (index + slides.length) % slides.length;
        slides[currentIndex].classList.add('active');
        updateDots();
    }

    function nextSlide() {
        goToSlide(currentIndex + 1);
    }

    function prevSlide() {
        goToSlide(currentIndex - 1);
    }

    function startAutoPlay() {
        stopAutoPlay();
        autoPlayTimer = setInterval(nextSlide, 5000); // 5 giây tự chuyển
    }

    function stopAutoPlay() {
        if (autoPlayTimer) clearInterval(autoPlayTimer);
    }

    // Tạo chấm tròn chỉ số (nếu muốn dùng)
    let dotsWrapper = slider.querySelector('.hero-dots');
    if (!dotsWrapper && slides.length > 1) {
        dotsWrapper = document.createElement('div');
        dotsWrapper.className = 'hero-dots';
        slider.appendChild(dotsWrapper);
        slides.forEach((_, i) => {
            const dot = document.createElement('button');
            dot.className = 'hero-dot' + (i === 0 ? ' active' : '');
            dot.addEventListener('click', () => {
                goToSlide(i);
                startAutoPlay();
            });
            dotsWrapper.appendChild(dot);
        });
    }

    function updateDots() {
        if (!dotsWrapper) return;
        dotsWrapper.querySelectorAll('.hero-dot').forEach((dot, i) => {
            dot.classList.toggle('active', i === currentIndex);
        });
    }

    // Sự kiện nút prev/next
    if (nextBtn) {
        nextBtn.addEventListener('click', () => {
            nextSlide();
            startAutoPlay();
        });
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', () => {
            prevSlide();
            startAutoPlay();
        });
    }

    // Ẩn nút mũi tên nếu chỉ có 1 banner
    if (slides.length <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
    } else {
        startAutoPlay();
    }
});