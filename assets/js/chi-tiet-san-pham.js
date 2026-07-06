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
            alert('Đã thêm ' + soLuong + ' sản phẩm vào giỏ hàng.');
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
