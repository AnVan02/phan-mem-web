document.addEventListener('DOMContentLoaded', function () {
    // Nút 3 gạch (mobile): mở/đóng menu chính
    var navToggle = document.querySelector('.nav-toggle');
    var mainNav = document.querySelector('.main-nav');
    var siteHeader = document.querySelector('.site-header');
    if (navToggle && mainNav) {
        navToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = mainNav.classList.toggle('is-open');
            navToggle.classList.toggle('is-active', isOpen);
            navToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
            if (isOpen && siteHeader) {
                mainNav.style.top = siteHeader.offsetHeight + 'px';
            } else {
                mainNav.style.top = '';
            }
            document.body.style.overflow = isOpen ? 'hidden' : '';
        });

        // Bấm ra ngoài menu thì đóng lại
        document.addEventListener('click', function (e) {
            if (mainNav.classList.contains('is-open') && !e.target.closest('.main-nav') && !e.target.closest('.nav-toggle')) {
                mainNav.classList.remove('is-open');
                navToggle.classList.remove('is-active');
                navToggle.setAttribute('aria-expanded', 'false');
                mainNav.style.top = '';
                document.body.style.overflow = '';
            }
        });
    }

    var dropdownItems = document.querySelectorAll('.has-submenu, .has-megamenu');

    function closeAll(except) {
        dropdownItems.forEach(function (li) {
            if (li !== except) li.classList.remove('is-open');
        });
    }

    dropdownItems.forEach(function (li) {
        var trigger = li.querySelector(':scope > a');
        trigger.addEventListener('click', function (e) {
            var isOpen = li.classList.contains('is-open');
            e.preventDefault(); // bấm lần đầu chỉ mở/đóng menu con, không chuyển trang
            closeAll(li);
            li.classList.toggle('is-open', !isOpen);
        });
    });

    // Bấm ra ngoài thì đóng hết
    document.addEventListener('click', function (e) {
        if (!e.target.closest('.has-submenu, .has-megamenu')) {
            closeAll(null);
        }
    });

    // Nhấn Esc để đóng
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAll(null);
    });

    // Badge số lượng giỏ hàng
    var cartBadge = document.getElementById('cartCountBadge');

    window.capNhatBadgeGioHang = function (soLuong) {
        if (!cartBadge) return;
        soLuong = parseInt(soLuong, 10) || 0;
        cartBadge.textContent = soLuong;
        cartBadge.style.display = soLuong > 0 ? 'flex' : 'none';
    };

    if (cartBadge) {
        fetch('gio-hang-ajax.php?action=dem')
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (data.success) window.capNhatBadgeGioHang(data.cart_count);
            })
            .catch(function () {});
    }

    // Toast thông báo giỏ hàng
    window.hienThongBaoGioHang = function (message) {
        var toast = document.querySelector('.toast-gio-hang');
        if (!toast) {
            toast = document.createElement('div');
            toast.className = 'toast-gio-hang';
            document.body.appendChild(toast);
        }
        toast.textContent = message;
        clearTimeout(toast._hideTimer);
        requestAnimationFrame(function () { toast.classList.add('show'); });
        toast._hideTimer = setTimeout(function () {
            toast.classList.remove('show');
        }, 2200);
    };
});
