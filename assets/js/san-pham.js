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

    // ==========================
    // Filter sản phẩm
    // ==========================
    const buttons = document.querySelectorAll(".filter-circle");

    buttons.forEach(function (button) {

        button.addEventListener("click", function () {

            const sectionId = this.dataset.section;
            const filter = this.dataset.filter;

            const section = document.getElementById(sectionId);

            if (!section) return;

            // Active button
            const strip = this.closest(".category-strip");

            strip.querySelectorAll(".filter-circle").forEach(function (btn) {
                btn.classList.remove("active");
            });

            this.classList.add("active");

            // Hiện / ẩn sản phẩm
            const cards = section.querySelectorAll(".product-card");

            cards.forEach(function (card) {

                if (filter === "all") {

                    card.style.display = "";

                    return;

                }

                if (card.dataset.filter.toLowerCase() === filter.toLowerCase()) {

                    card.style.display = "";

                } else {

                    card.style.display = "none";

                }

            });

        });

    });

    // ==========================
    // Mặc định click "Tất cả"
    // ==========================
    document.querySelectorAll(".category-strip").forEach(function (strip) {

        const first = strip.querySelector(".filter-circle");

        if (first) {
            first.click();
        }

    });

});