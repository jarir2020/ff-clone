(function () {
    'use strict';

    function initPublisherSlider() {
        var track = document.getElementById('publisherTrack');
        var viewport = document.getElementById('publisherViewport');
        var slider = document.getElementById('publisherSlider');

        if (!track || !viewport) return;

        var items = track.querySelectorAll('.brand-slide-card');
        if (!items.length) return;

        var index = 0;
        var maxIndex = 0;
        var stepPx = 0;
        var autoTimer = null;
        var paused = false;
        var AUTO_MS = 3500;

        function getGap() {
            var styles = window.getComputedStyle(track);
            return parseFloat(styles.columnGap || styles.gap || '12') || 12;
        }

        function measure() {
            var gap = getGap();
            var viewportWidth = viewport.clientWidth;
            var itemWidth = items[0].getBoundingClientRect().width;
            var perView = Math.max(1, Math.floor((viewportWidth + gap) / (itemWidth + gap)));

            maxIndex = Math.max(0, items.length - perView);
            stepPx = itemWidth + gap;

            if (index > maxIndex) index = 0;
            applyTransform(false);
        }

        function applyTransform(animate) {
            var offset = index * stepPx;
            track.style.transition = animate === false ? 'none' : 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
            track.style.transform = 'translate3d(-' + offset + 'px, 0, 0)';
        }

        function nextSlide() {
            if (maxIndex <= 0) return;
            if (index >= maxIndex) index = 0;
            else index += 1;
            applyTransform(true);
        }

        function startAuto() {
            if (autoTimer) window.clearInterval(autoTimer);
            if (maxIndex <= 0) return;
            autoTimer = window.setInterval(function () {
                if (!paused) nextSlide();
            }, AUTO_MS);
        }

        if (slider) {
            slider.addEventListener('mouseenter', function () { paused = true; });
            slider.addEventListener('mouseleave', function () { paused = false; });
        }

        if (typeof ResizeObserver !== 'undefined') {
            var ro = new ResizeObserver(function () {
                measure();
                startAuto();
            });
            ro.observe(viewport);
        }

        window.addEventListener('resize', measure);
        window.addEventListener('load', function () {
            measure();
            startAuto();
        });
        measure();
        startAuto();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPublisherSlider);
    } else {
        initPublisherSlider();
    }
})();
