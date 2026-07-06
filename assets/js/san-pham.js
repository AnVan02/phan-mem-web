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