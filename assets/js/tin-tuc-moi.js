document.addEventListener('DOMContentLoaded', function () {
    var PAGE_SIZE = 12;
    var tabs = document.querySelectorAll('.news-filter .news-tab');
    var cards = Array.prototype.slice.call(document.querySelectorAll('.news-grid .news-card'));
    var emptyFiltered = document.querySelector('.news-empty-filtered');
    var pagination = document.querySelector('.news-pagination');
    var currentPage = 1;

    if (!cards.length) return;

    function getMatchingCards(linh) {
        return cards.filter(function (card) {
            return linh === '' || card.getAttribute('data-linh') === linh;
        });
    }

    function buildPageList(current, total) {
        var delta = 2;
        var start = Math.max(2, current - delta);
        var end = Math.min(total - 1, current + delta);
        var pages = [1];

        if (start > 2) pages.push('...');
        for (var i = start; i <= end; i++) pages.push(i);
        if (end < total - 1) pages.push('...');
        if (total > 1) pages.push(total);

        return pages;
    }

    function renderPagination(current, total) {
        if (!pagination) return;
        pagination.innerHTML = '';
        if (total <= 1) return;

        var prevBtn = document.createElement('button');
        prevBtn.type = 'button';
        prevBtn.className = 'page-nav';
        prevBtn.innerHTML = '<i class="fa-solid fa-chevron-left"></i>';
        prevBtn.disabled = current === 1;
        prevBtn.addEventListener('click', function () { goToPage(current - 1); });
        pagination.appendChild(prevBtn);

        buildPageList(current, total).forEach(function (item) {
            if (item === '...') {
                var span = document.createElement('span');
                span.className = 'page-ellipsis';
                span.textContent = '...';
                pagination.appendChild(span);
                return;
            }

            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'page-number' + (item === current ? ' active' : '');
            btn.textContent = item;
            btn.addEventListener('click', function () { goToPage(item); });
            pagination.appendChild(btn);
        });

        var nextBtn = document.createElement('button');
        nextBtn.type = 'button';
        nextBtn.className = 'page-nav';
        nextBtn.innerHTML = '<i class="fa-solid fa-chevron-right"></i>';
        nextBtn.disabled = current === total;
        nextBtn.addEventListener('click', function () { goToPage(current + 1); });
        pagination.appendChild(nextBtn);
    }

    function goToPage(page) {
        currentPage = page;
        applyFilter(getActiveLinh());
        var grid = document.querySelector('.news-grid');
        if (grid) grid.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function getActiveLinh() {
        var activeTab = document.querySelector('.news-filter .news-tab.active');
        return activeTab ? activeTab.getAttribute('data-linh') : '';
    }

    function applyFilter(linh) {
        var matching = getMatchingCards(linh);
        var totalPages = Math.max(1, Math.ceil(matching.length / PAGE_SIZE));
        if (currentPage > totalPages) currentPage = totalPages;

        var startIdx = (currentPage - 1) * PAGE_SIZE;
        var endIdx = startIdx + PAGE_SIZE;
        var pageCards = matching.slice(startIdx, endIdx);

        cards.forEach(function (card) { card.style.display = 'none'; });
        pageCards.forEach(function (card) { card.style.display = ''; });

        if (emptyFiltered) {
            emptyFiltered.style.display = matching.length === 0 ? '' : 'none';
        }

        renderPagination(currentPage, totalPages);
    }

    tabs.forEach(function (tab) {
        tab.addEventListener('click', function () {
            tabs.forEach(function (t) { t.classList.remove('active'); });
            tab.classList.add('active');
            currentPage = 1;
            applyFilter(tab.getAttribute('data-linh'));
        });
    });

    var initialTab = document.querySelector('.news-filter .news-tab.active') || tabs[0];
    applyFilter(initialTab.getAttribute('data-linh'));
});
