// Tính và hiển thị "Giá sau giảm" theo thời gian thực khi nhập Giá bán / Giảm giá.
(function () {
    var giaBan = document.getElementById('gia_ban');
    var giamGia = document.getElementById('giam_gia');
    var preview = document.getElementById('gia_sau_giam_preview');
    if (!giaBan || !giamGia || !preview) return;

    function capNhat() {
        var ban = parseInt(giaBan.value, 10) || 0;
        var giam = Math.min(100, Math.max(0, parseInt(giamGia.value, 10) || 0));
        var sau = giam > 0 ? Math.round(ban * (100 - giam) / 100) : ban;
        preview.textContent = sau.toLocaleString('vi-VN') + ' ₫';
    }

    giaBan.addEventListener('input', capNhat);
    giamGia.addEventListener('input', capNhat);
    capNhat();
})();
