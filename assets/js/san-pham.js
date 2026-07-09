document.addEventListener("DOMContentLoaded", function () {

    // ==========================
    // 1. Đếm ngược khuyến mãi
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
                23, 59, 59
            );
            const conLai = Math.max(0, hetHan - now);

            const gio = Math.floor(conLai / 3600000);
            const phut = Math.floor((conLai % 3600000) / 60000);
            const giay = Math.floor((conLai % 60000) / 1000);

            const text = `${padSo(gio)}:${padSo(phut)}:${padSo(giay)}`;

            countdownEls.forEach(function (el) {
                el.textContent = text;
            });
        }

        capNhat();
        setInterval(capNhat, 1000);
    }

    // ==========================
    // 2. Xem thêm / Thu gọn (ĐÃ SỬA)
    // ==========================
    // Gán hàm vào window để HTML có thể gọi qua onclick
    window.toggleShowMore = function (btn) {
        const sidebarBlock = btn.closest('.sidebar-block');
        const hiddenItems = sidebarBlock.querySelectorAll('.filter-item.hidden-item');

        // Kiểm tra trạng thái hiện tại dựa vào class active của nút
        const isExpanded = btn.classList.contains('active');
        const newDisplay = isExpanded ? 'none' : 'list-item';

        // Ẩn hoặc hiện các item
        hiddenItems.forEach(item => {
            item.style.display = newDisplay;
        });

        // Đổi trạng thái nút
        btn.classList.toggle('active');
        const spanText = btn.querySelector('span');

        if (btn.classList.contains('active')) {
            spanText.textContent = 'Thu gọn';
        } else {
            spanText.textContent = 'Xem thêm';
        }
    };

    // ==========================
    // 3. Chuyển đổi xem dạng lưới / danh sách
    // ==========================
    const viewToggle = document.querySelector("[data-view-toggle]");
    const viewTarget = document.querySelector("[data-view-target]");

    if (viewToggle && viewTarget) {
        viewToggle.addEventListener("click", function (e) {
            const btn = e.target.closest(".view-toggle-btn");
            if (!btn) return;

            viewToggle.querySelectorAll(".view-toggle-btn").forEach(function (b) {
                b.classList.remove("active");
            });
            btn.classList.add("active");

            viewTarget.classList.toggle("list-view", btn.dataset.view === "list");
        });
    }
});