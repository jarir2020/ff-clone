(function () {
    'use strict';

    function initCategoryMenuSlider() {
        var track = document.getElementById('categoryMenuTrack');
        var viewport = document.getElementById('categoryMenuViewport');
        var prevBtn = document.getElementById('topCatPrev');
        var nextBtn = document.getElementById('topCatNext');

        if (!track || !viewport) return;

        var items = track.querySelectorAll('.category-menu-card');
        if (!items.length) return;

        var index = 0;
        var perView = 5;
        var maxIndex = 0;
        var stepPx = 0;

        function getGap() {
            var styles = window.getComputedStyle(track);
            return parseFloat(styles.columnGap || styles.gap || '12') || 12;
        }

        function cardsPerView() {
            var w = window.innerWidth;
            if (w >= 901) return 5;
            if (w >= 521) return 2;
            return 1;
        }

        function updateNav() {
            var hideNav = maxIndex <= 0;
            if (prevBtn) {
                prevBtn.style.display = hideNav ? 'none' : 'flex';
                prevBtn.disabled = index <= 0;
            }
            if (nextBtn) {
                nextBtn.style.display = hideNav ? 'none' : 'flex';
                nextBtn.disabled = index >= maxIndex;
            }
        }

        function measure() {
            var gap = getGap();
            perView = Math.min(cardsPerView(), items.length);
            var viewportWidth = viewport.clientWidth;
            var itemWidth = Math.floor((viewportWidth - gap * (perView - 1)) / perView);

            items.forEach(function (item) {
                item.style.flex = '0 0 ' + itemWidth + 'px';
                item.style.width = itemWidth + 'px';
                item.style.minWidth = itemWidth + 'px';
                item.style.maxWidth = itemWidth + 'px';
            });

            maxIndex = Math.max(0, items.length - perView);
            stepPx = itemWidth + gap;

            if (index > maxIndex) index = maxIndex;
            applyTransform(false);
            updateNav();
        }

        function applyTransform(animate) {
            var offset = index * stepPx;
            track.style.transition = animate === false ? 'none' : 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
            track.style.transform = 'translate3d(-' + offset + 'px, 0, 0)';
        }

        function goTo(nextIndex, animate) {
            if (maxIndex <= 0) return;
            if (nextIndex > maxIndex) index = maxIndex;
            else if (nextIndex < 0) index = 0;
            else index = nextIndex;
            applyTransform(animate !== false);
            updateNav();
        }

        if (prevBtn) {
            prevBtn.addEventListener('click', function () {
                goTo(index - 1);
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function () {
                goTo(index + 1);
            });
        }

        if (typeof ResizeObserver !== 'undefined') {
            var ro = new ResizeObserver(measure);
            ro.observe(viewport);
        }

        window.addEventListener('resize', measure);
        window.addEventListener('load', measure);
        measure();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCategoryMenuSlider);
    } else {
        initCategoryMenuSlider();
    }
})();
