// Đếm ngược khung giờ khuyến mãi trên thẻ sản phẩm (san-pham.php), kết thúc vào cuối ngày hiện tại.
document.addEventListener('DOMContentLoaded', function () {
    var elements = document.querySelectorAll('[data-countdown-endofday]');
    if (!elements.length) return;

    function padSo(n) {
        return String(n).padStart(2, '0');
    }

    function capNhat() {
        var now = new Date();
        var hetHan = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 23, 59, 59);
        var conLai = Math.max(0, hetHan - now);
        var gio = Math.floor(conLai / 3600000);
        var phut = Math.floor((conLai % 3600000) / 60000);
        var giay = Math.floor((conLai % 60000) / 1000);
        var text = padSo(gio) + ':' + padSo(phut) + ':' + padSo(giay);
        elements.forEach(function (el) { el.textContent = text; });
    }

    capNhat();
    setInterval(capNhat, 1000);
});
