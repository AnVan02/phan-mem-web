document.addEventListener('DOMContentLoaded', function () {
    var mainImage = document.getElementById('mainProductImage');
    var thumbs = Array.prototype.slice.call(document.querySelectorAll('.product-gallery-thumbs .thumb'));
    var prevBtn = document.querySelector('.gallery-nav-prev');
    var nextBtn = document.querySelector('.gallery-nav-next');
    var galleryImages = thumbs.map(function (t) { return t.getAttribute('data-src'); });
    var currentIndex = 0;

    function setActiveIndex(index) {
        if (!mainImage || galleryImages.length === 0) return;
        currentIndex = (index + galleryImages.length) % galleryImages.length;
        mainImage.src = galleryImages[currentIndex];
        thumbs.forEach(function (t, i) { t.classList.toggle('active', i === currentIndex); });
    }

    thumbs.forEach(function (thumb, i) {
        thumb.addEventListener('click', function () { setActiveIndex(i); });
    });

    if (prevBtn) prevBtn.addEventListener('click', function () { setActiveIndex(currentIndex - 1); });
    if (nextBtn) nextBtn.addEventListener('click', function () { setActiveIndex(currentIndex + 1); });

    var qtyInput = document.querySelector('.qty-input');
    var qtyMinus = document.querySelector('.qty-minus');
    var qtyPlus = document.querySelector('.qty-plus');
    var btnAddCart = document.querySelector('.btn-add-cart') || document.querySelector('.btn-add-cart-outline') || document.querySelector('.btn-buy-now');

    function getMax() {
        if (!qtyInput) return 999;
        var max = parseInt(qtyInput.getAttribute('max'), 10);
        return isNaN(max) ? 999 : max;
    }

    function getMin() {
        if (!qtyInput) return 1;
        var min = parseInt(qtyInput.getAttribute('min'), 10);
        return isNaN(min) ? 1 : min;
    }

    function clamp(value) {
        value = parseInt(value, 10);
        if (isNaN(value)) value = getMin();
        return Math.min(Math.max(value, getMin()), getMax());
    }

    if (qtyMinus && qtyInput) {
        qtyMinus.addEventListener('click', function () {
            qtyInput.value = clamp(parseInt(qtyInput.value, 10) - 1);
        });
    }

    if (qtyPlus && qtyInput) {
        qtyPlus.addEventListener('click', function () {
            qtyInput.value = clamp(parseInt(qtyInput.value, 10) + 1);
        });
    }

    if (qtyInput) {
        qtyInput.addEventListener('change', function () {
            qtyInput.value = clamp(qtyInput.value);
        });
    }

    if (btnAddCart) {
        btnAddCart.addEventListener('click', function () {
            if (btnAddCart.disabled) return;
            var soLuong = qtyInput ? clamp(qtyInput.value) : 1;
            var maSanPham = btnAddCart.getAttribute('data-ma-san-pham');
            if (!maSanPham) return;

            btnAddCart.disabled = true;
            var params = new URLSearchParams();
            params.append('action', 'them');
            params.append('ma_san_pham', maSanPham);
            params.append('so_luong', soLuong);

            fetch('gio-hang-ajax.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: params.toString()
            })
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    btnAddCart.disabled = false;
                    if (data.success) {
                        if (window.capNhatBadgeGioHang) window.capNhatBadgeGioHang(data.cart_count);
                        if (window.hienThongBaoGioHang) {
                            window.hienThongBaoGioHang(data.message || 'Đã thêm vào giỏ hàng.');
                        } else {
                            alert(data.message || 'Đã thêm vào giỏ hàng.');
                        }
                    } else {
                        alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại.');
                    }
                })
                .catch(function () {
                    btnAddCart.disabled = false;
                    alert('Có lỗi xảy ra, vui lòng thử lại.');
                });
        });
    }

    // Modal Specs Logic
    var btnOpenSpecsModal = document.getElementById('btnOpenSpecsModal');
    var btnCloseSpecsModal = document.getElementById('btnCloseSpecsModal');
    var specsModal = document.getElementById('specsModal');

    if (btnOpenSpecsModal && specsModal) {
        btnOpenSpecsModal.addEventListener('click', function () {
            specsModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }

    if (btnCloseSpecsModal && specsModal) {
        btnCloseSpecsModal.addEventListener('click', function () {
            specsModal.classList.remove('show');
            document.body.style.overflow = '';
        });
    }

    if (specsModal) {
        specsModal.addEventListener('click', function (e) {
            if (e.target === specsModal) {
                specsModal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    }

    // Compare Modal Logic
    var btnOpenCompareModal = document.getElementById('btnOpenCompareModal');
    var btnCloseCompareModal = document.getElementById('btnCloseCompareModal');
    var compareModal = document.getElementById('compareModal');
    var compareSearchInput = document.getElementById('compareSearchInput');
    var compareSearchResults = document.getElementById('compareSearchResults');
    var compareTableWrapper = document.getElementById('compareTableWrapper');
    var comparePlaceholder = document.getElementById('comparePlaceholder');

    function closeCompareModal() {
        if (!compareModal) return;
        compareModal.classList.remove('show');
        document.body.style.overflow = '';
    }

    if (btnOpenCompareModal && compareModal) {
        btnOpenCompareModal.addEventListener('click', function () {
            compareModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        });
    }

    if (btnCloseCompareModal) {
        btnCloseCompareModal.addEventListener('click', closeCompareModal);
    }

    if (compareModal) {
        compareModal.addEventListener('click', function (e) {
            if (e.target === compareModal) closeCompareModal();
        });
    }

    if (compareSearchInput && compareModal) {
        var currentId = compareModal.getAttribute('data-current-id');
        var danhMuc = compareModal.getAttribute('data-danh-muc');
        var searchTimer = null;

        function escapeHtml(str) {
            var div = document.createElement('div');
            div.textContent = str || '';
            return div.innerHTML;
        }

        function renderResults(items) {
            if (!compareSearchResults) return;
            if (!items.length) {
                compareSearchResults.innerHTML = '<div class="compare-no-result">Không tìm thấy sản phẩm phù hợp.</div>';
                compareSearchResults.classList.add('show');
                return;
            }
            compareSearchResults.innerHTML = items.map(function (item) {
                return '<div class="compare-result-item" data-id="' + item.id + '">' +
                    '<img src="' + escapeHtml(item.hinh_anh) + '" alt="">' +
                    '<div class="compare-result-info">' +
                        '<span class="compare-result-name">' + escapeHtml(item.ten_san_pham) + '</span>' +
                        '<span class="compare-result-price">' + escapeHtml(item.gia_display) + '</span>' +
                    '</div>' +
                '</div>';
            }).join('');
            compareSearchResults.classList.add('show');

            Array.prototype.slice.call(compareSearchResults.querySelectorAll('.compare-result-item')).forEach(function (el) {
                el.addEventListener('click', function () {
                    selectCompareProduct(el.getAttribute('data-id'));
                });
            });
        }

        function selectCompareProduct(id) {
            fetch('so-sanh-ajax.php?action=detail&id=' + encodeURIComponent(id))
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    if (data.error) return;

                    var imgCell = document.getElementById('compareOtherImage');
                    if (imgCell) {
                        imgCell.innerHTML = '<a href="' + escapeHtml(data.url) + '"><img src="' + escapeHtml(data.hinh_anh) + '" alt="">' +
                            '<div class="compare-product-name">' + escapeHtml(data.ten_san_pham) + '</div></a>';
                    }
                    var brandCell = document.getElementById('compareOtherBrand');
                    if (brandCell) brandCell.textContent = data.ten_thuong_hieu || '';

                    var capacityCell = document.getElementById('compareOtherCapacity');
                    if (capacityCell) capacityCell.textContent = data.ten_dung_luong || '';

                    var priceCell = document.getElementById('compareOtherPrice');
                    if (priceCell) priceCell.textContent = data.gia_display || '';

                    var specsCell = document.getElementById('compareOtherSpecs');
                    if (specsCell) specsCell.innerHTML = data.thong_so || '';

                    if (comparePlaceholder) comparePlaceholder.style.display = 'none';
                    if (compareTableWrapper) compareTableWrapper.style.display = 'block';
                    if (compareSearchResults) compareSearchResults.classList.remove('show');
                    compareSearchInput.value = data.ten_san_pham;
                });
        }

        compareSearchInput.addEventListener('input', function () {
            var q = compareSearchInput.value.trim();
            clearTimeout(searchTimer);
            if (q.length < 2) {
                if (compareSearchResults) compareSearchResults.classList.remove('show');
                return;
            }
            searchTimer = setTimeout(function () {
                var url = 'so-sanh-ajax.php?action=search&q=' + encodeURIComponent(q) +
                    '&exclude=' + encodeURIComponent(currentId) + '&danh_muc=' + encodeURIComponent(danhMuc);
                fetch(url)
                    .then(function (res) { return res.json(); })
                    .then(renderResults);
            }, 300);
        });

        document.addEventListener('click', function (e) {
            if (compareSearchResults && !compareSearchResults.contains(e.target) && e.target !== compareSearchInput) {
                compareSearchResults.classList.remove('show');
            }
        });
    }

    // Desc Read More Logic
    var btnReadMoreDesc = document.getElementById('btnReadMoreDesc');
    var descContent = document.getElementById('descContent');
    var descFade = document.getElementById('descFade');

    if (btnReadMoreDesc && descContent) {
        btnReadMoreDesc.addEventListener('click', function () {
            descContent.classList.add('expanded');
            if (descFade) descFade.style.display = 'none';
            btnReadMoreDesc.parentElement.style.display = 'none';
            
            // Adjust parent wrapper style if necessary (padding bottom removal)
            var descBox = descContent.parentElement;
            if(descBox) {
                descBox.style.paddingBottom = "24px"; 
            }
        });
    }
});
