document.addEventListener('DOMContentLoaded', function () {
    var cartList = document.getElementById('cartItemsList');
    if (!cartList) return;

    var summaryTotal = document.getElementById('cartSummaryTotal');

    function formatTien(so) {
        return Math.round(so).toLocaleString('vi-VN') + '₫';
    }

    function tinhLaiTongTien() {
        var tong = 0;
        cartList.querySelectorAll('.cart-item').forEach(function (row) {
            var lineTotal = parseInt(row.getAttribute('data-line-total'), 10) || 0;
            tong += lineTotal;
        });
        if (summaryTotal) summaryTotal.textContent = formatTien(tong);
    }

    function getMax(input) {
        var max = parseInt(input.getAttribute('max'), 10);
        return isNaN(max) ? 999 : max;
    }

    function clamp(input, value) {
        value = parseInt(value, 10);
        if (isNaN(value)) value = 1;
        return Math.min(Math.max(value, 1), getMax(input));
    }

    function capNhatSoLuong(row, soLuongMoi) {
        var maGioHang = row.getAttribute('data-ma-gio-hang');
        var params = new URLSearchParams();
        params.append('action', 'cap_nhat');
        params.append('ma_gio_hang', maGioHang);
        params.append('so_luong', soLuongMoi);

        fetch('gio-hang-ajax.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: params.toString()
        })
            .then(function (res) { return res.json(); })
            .then(function (data) {
                if (!data.success) return;
                var input = row.querySelector('.qty-input');
                if (input) input.value = data.so_luong;

                var unitPriceText = row.querySelector('.cart-item-unit-price').textContent;
                var donGia = parseInt(unitPriceText.replace(/[^0-9]/g, ''), 10) || 0;
                var thanhTien = donGia * data.so_luong;

                row.setAttribute('data-line-total', thanhTien);
                row.querySelector('.cart-item-line-total').textContent = formatTien(thanhTien);

                if (window.capNhatBadgeGioHang) window.capNhatBadgeGioHang(data.cart_count);
                tinhLaiTongTien();
            });
    }

    cartList.querySelectorAll('.cart-item').forEach(function (row) {
        var input = row.querySelector('.qty-input');
        var lineTotalEl = row.querySelector('.cart-item-line-total');
        row.setAttribute('data-line-total', parseInt((lineTotalEl.textContent || '0').replace(/[^0-9]/g, ''), 10) || 0);

        var minusBtn = row.querySelector('.qty-minus');
        var plusBtn = row.querySelector('.qty-plus');
        var removeBtn = row.querySelector('.cart-item-remove');

        if (minusBtn) {
            minusBtn.addEventListener('click', function () {
                capNhatSoLuong(row, clamp(input, parseInt(input.value, 10) - 1));
            });
        }
        if (plusBtn) {
            plusBtn.addEventListener('click', function () {
                capNhatSoLuong(row, clamp(input, parseInt(input.value, 10) + 1));
            });
        }
        if (input) {
            input.addEventListener('change', function () {
                capNhatSoLuong(row, clamp(input, input.value));
            });
        }
        if (removeBtn) {
            removeBtn.addEventListener('click', function () {
                var maGioHang = row.getAttribute('data-ma-gio-hang');
                var params = new URLSearchParams();
                params.append('action', 'xoa');
                params.append('ma_gio_hang', maGioHang);

                fetch('gio-hang-ajax.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: params.toString()
                })
                    .then(function (res) { return res.json(); })
                    .then(function (data) {
                        if (!data.success) return;
                        if (window.capNhatBadgeGioHang) window.capNhatBadgeGioHang(data.cart_count);
                        row.remove();
                        tinhLaiTongTien();
                        if (!cartList.querySelector('.cart-item')) {
                            location.reload();
                        }
                    });
            });
        }
    });
});
