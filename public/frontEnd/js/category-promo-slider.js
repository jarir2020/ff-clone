(function () {
    'use strict';

    function initCategoryPromoSlider() {
        var track = document.getElementById('categoryPromoTrack');
        var viewport = document.getElementById('categoryPromoViewport');
        var slider = document.getElementById('categoryPromoSlider');
        var prevBtn = document.getElementById('categoryPromoPrev');
        var nextBtn = document.getElementById('categoryPromoNext');

        if (!track || !viewport) return;

        var items = track.querySelectorAll('.category-promo-item');
        if (items.length <= 1) return;

        var index = 0;
        var maxIndex = 0;
        var stepPx = 0;
        var touchStartX = 0;
        var touchStartY = 0;

        function getGap() {
            var styles = window.getComputedStyle(track);
            return parseFloat(styles.columnGap || styles.gap || '10') || 10;
        }

        function cardsPerView() {
            if (window.innerWidth >= 901) return Math.min(4, items.length);
            return Math.min(2, items.length);
        }

        function updateNav() {
            var hideNav = maxIndex <= 0 || window.innerWidth >= 901;
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
            if (window.innerWidth >= 901) {
                track.style.transform = 'none';
                track.style.transition = 'none';
                items.forEach(function (item) {
                    item.style.width = '';
                    item.style.flex = '';
                    item.style.minWidth = '';
                    item.style.maxWidth = '';
                });
                updateNav();
                return;
            }

            var viewportWidth = viewport.clientWidth;
            if (!viewportWidth) {
                requestAnimationFrame(measure);
                return;
            }

            var gap = getGap();
            var perView = cardsPerView();
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
            track.style.transition = animate === false ? 'none' : 'transform 0.45s cubic-bezier(0.4, 0, 0.2, 1)';
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

        if (slider) {
            slider.addEventListener('touchstart', function (e) {
                touchStartX = e.changedTouches[0].clientX;
                touchStartY = e.changedTouches[0].clientY;
            }, { passive: true });

            slider.addEventListener('touchend', function (e) {
                if (window.innerWidth >= 901) return;
                var touch = e.changedTouches[0];
                var deltaX = touch.clientX - touchStartX;
                var deltaY = touch.clientY - touchStartY;
                if (Math.abs(deltaX) < 40 || Math.abs(deltaX) <= Math.abs(deltaY)) return;
                if (deltaX < 0) goTo(index + 1);
                else goTo(index - 1);
            }, { passive: true });
        }

        items.forEach(function (item) {
            var img = item.querySelector('img');
            if (img && !img.complete) {
                img.addEventListener('load', measure);
            }
        });

        if (typeof ResizeObserver !== 'undefined') {
            var ro = new ResizeObserver(measure);
            ro.observe(viewport);
        }

        window.addEventListener('resize', measure);
        window.addEventListener('load', function () {
            measure();
            setTimeout(measure, 150);
        });

        measure();
        setTimeout(measure, 150);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initCategoryPromoSlider);
    } else {
        initCategoryPromoSlider();
    }
})();
