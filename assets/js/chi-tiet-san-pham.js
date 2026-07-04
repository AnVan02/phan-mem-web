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
    var btnAddCart = document.querySelector('.btn-add-cart');

    if (!qtyInput) return;

    function getMax() {
        var max = parseInt(qtyInput.getAttribute('max'), 10);
        return isNaN(max) ? 999 : max;
    }

    function getMin() {
        var min = parseInt(qtyInput.getAttribute('min'), 10);
        return isNaN(min) ? 1 : min;
    }

    function clamp(value) {
        value = parseInt(value, 10);
        if (isNaN(value)) value = getMin();
        return Math.min(Math.max(value, getMin()), getMax());
    }

    if (qtyMinus) {
        qtyMinus.addEventListener('click', function () {
            qtyInput.value = clamp(parseInt(qtyInput.value, 10) - 1);
        });
    }

    if (qtyPlus) {
        qtyPlus.addEventListener('click', function () {
            qtyInput.value = clamp(parseInt(qtyInput.value, 10) + 1);
        });
    }

    qtyInput.addEventListener('change', function () {
        qtyInput.value = clamp(qtyInput.value);
    });

    if (btnAddCart) {
        btnAddCart.addEventListener('click', function () {
            if (btnAddCart.disabled) return;
            var soLuong = clamp(qtyInput.value);
            alert('Đã thêm ' + soLuong + ' sản phẩm vào giỏ hàng.');
        });
    }
});
