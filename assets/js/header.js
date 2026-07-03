document.addEventListener('DOMContentLoaded', function () {
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
});
