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

    // Nút kính lúp: mở/đóng ô tìm kiếm
    var searchToggle = document.querySelector('.search-toggle');
    var searchBox = document.querySelector('.search-box');
    if (searchToggle && searchBox) {
        searchToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = searchBox.classList.toggle('is-open');
            if (isOpen) {
                var input = searchBox.querySelector('input[name="q"]');
                if (input) input.focus();
            }
        });

        document.addEventListener('click', function (e) {
            if (searchBox.classList.contains('is-open') && !e.target.closest('.search-box') && !e.target.closest('.search-toggle')) {
                searchBox.classList.remove('is-open');
            }
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') searchBox.classList.remove('is-open');
        });
    }

    // Gợi ý tìm kiếm tự động (autocomplete) khi gõ vào ô tìm kiếm
    var searchInput = document.getElementById('headerSearchInput');
    var searchSuggest = document.getElementById('headerSearchSuggest');
    if (searchInput && searchSuggest) {
        var debounceTimer = null;
        var activeIndex = -1;
        var currentItems = [];

        function dongGoiY() {
            searchSuggest.classList.remove('is-open');
            searchSuggest.innerHTML = '';
            activeIndex = -1;
            currentItems = [];
        }

        function taoHtmlItem(item) {
            var a = document.createElement('a');
            a.className = 'search-suggest-item';
            a.href = item.url;
            a.innerHTML =
                '<img src="' + item.hinh_anh + '" alt="" loading="lazy" onerror="this.onerror=null;this.src=\'assets/image/pc.webp\';">' +
                '<span class="search-suggest-item-info">' +
                '<span class="search-suggest-item-name"></span>' +
                '<span class="search-suggest-item-meta"></span>' +
                '</span>' +
                '<span class="search-suggest-item-price"></span>';
            a.querySelector('.search-suggest-item-name').textContent = item.ten_san_pham;
            a.querySelector('.search-suggest-item-meta').textContent = item.ten_thuong_hieu || '';
            a.querySelector('.search-suggest-item-price').textContent = item.gia_display;
            return a;
        }

        function hienGoiY(items, tuKhoa) {
            searchSuggest.innerHTML = '';
            currentItems = items;
            activeIndex = -1;

            if (!items.length) {
                var empty = document.createElement('div');
                empty.className = 'search-suggest-empty';
                empty.textContent = 'Không tìm thấy sản phẩm phù hợp';
                searchSuggest.appendChild(empty);
                searchSuggest.classList.add('is-open');
                return;
            }

            items.forEach(function (item) {
                searchSuggest.appendChild(taoHtmlItem(item));
            });

            var viewAll = document.createElement('a');
            viewAll.className = 'search-suggest-viewall';
            viewAll.href = 'tim-kiem.php?q=' + encodeURIComponent(tuKhoa);
            viewAll.textContent = 'Xem tất cả kết quả cho "' + tuKhoa + '"';
            searchSuggest.appendChild(viewAll);

            searchSuggest.classList.add('is-open');
        }

        function capNhatActive() {
            var items = searchSuggest.querySelectorAll('.search-suggest-item');
            items.forEach(function (el, i) {
                el.classList.toggle('is-active', i === activeIndex);
            });
            if (activeIndex >= 0 && items[activeIndex]) {
                items[activeIndex].scrollIntoView({ block: 'nearest' });
            }
        }

        searchInput.addEventListener('input', function () {
            var tuKhoa = searchInput.value.trim();
            clearTimeout(debounceTimer);

            if (tuKhoa.length < 2) {
                dongGoiY();
                return;
            }

            debounceTimer = setTimeout(function () {
                fetch('tim-kiem-ajax.php?action=suggest&q=' + encodeURIComponent(tuKhoa))
                    .then(function (res) { return res.json(); })
                    .then(function (items) { hienGoiY(items, tuKhoa); })
                    .catch(function () { dongGoiY(); });
            }, 250);
        });

        searchInput.addEventListener('keydown', function (e) {
            var items = searchSuggest.querySelectorAll('.search-suggest-item');
            if (!items.length) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                activeIndex = (activeIndex + 1) % items.length;
                capNhatActive();
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                activeIndex = (activeIndex - 1 + items.length) % items.length;
                capNhatActive();
            } else if (e.key === 'Enter' && activeIndex >= 0) {
                e.preventDefault();
                items[activeIndex].click();
            }
        });

        document.addEventListener('click', function (e) {
            if (!e.target.closest('.search-box')) dongGoiY();
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
