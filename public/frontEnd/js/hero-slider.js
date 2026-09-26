(function () {
    'use strict';

    var slider = document.getElementById('heroSlider');
    var track = document.getElementById('heroTrack');
    var dotsWrap = document.getElementById('heroDots');
    var prevBtn = document.getElementById('heroPrev');
    var nextBtn = document.getElementById('heroNext');

    if (!slider || !track) return;

    var slides = track.querySelectorAll('.hero-slide');
    var total = slides.length;
    var current = 0;
    var autoplayMs = 4500;
    var timer = null;
    var touchStartX = 0;
    var touchStartY = 0;
    var touchDeltaX = 0;
    var desktopHeroMaxH = 480;
    var mobileHeroMinH = 168;

    function getMobileHeroHeight(img, width) {
        var naturalFitH = mobileHeroMinH;
        if (img && img.naturalWidth > 0 && img.naturalHeight > 0) {
            naturalFitH = Math.round((img.naturalHeight / img.naturalWidth) * width);
        }
        var h = Math.max(mobileHeroMinH, naturalFitH);
        return {
            height: h,
            fill: h > naturalFitH
        };
    }

    function updateHeight() {
        if (window.innerWidth >= 901) {
            slider.classList.remove('hero-slider--fill');
            var img = slides[current] && slides[current].querySelector('.hero-img');
            if (img && img.offsetHeight) {
                slider.style.height = Math.min(img.offsetHeight, desktopHeroMaxH) + 'px';
            }
            return;
        }

        var width = slider.clientWidth || slider.offsetWidth;
        if (!width) return;
        var img = slides[current] && slides[current].querySelector('.hero-img');
        var mobile = getMobileHeroHeight(img, width);
        slider.style.height = mobile.height + 'px';
        slider.classList.toggle('hero-slider--fill', mobile.fill);
    }

    function goTo(index) {
        current = (index + total) % total;
        track.style.transform = 'translateX(-' + (current * 100) + '%)';

        if (dotsWrap) {
            dotsWrap.querySelectorAll('.hero-dot').forEach(function (dot, i) {
                dot.classList.toggle('active', i === current);
                dot.setAttribute('aria-selected', i === current ? 'true' : 'false');
            });
        }

        updateHeight();
    }

    function next() {
        goTo(current + 1);
    }

    function prev() {
        goTo(current - 1);
    }

    function startAutoplay() {
        stopAutoplay();
        timer = setInterval(next, autoplayMs);
    }

    function stopAutoplay() {
        if (timer) {
            clearInterval(timer);
            timer = null;
        }
    }

    if (dotsWrap && total > 1) {
        slides.forEach(function (_, i) {
            var dot = document.createElement('button');
            dot.type = 'button';
            dot.className = 'hero-dot' + (i === 0 ? ' active' : '');
            dot.setAttribute('role', 'tab');
            dot.setAttribute('aria-label', 'স্লাইড ' + (i + 1));
            dot.setAttribute('aria-selected', i === 0 ? 'true' : 'false');
            dot.addEventListener('click', function () {
                goTo(i);
                startAutoplay();
            });
            dotsWrap.appendChild(dot);
        });
    }

    if (prevBtn) {
        prevBtn.addEventListener('click', function () {
            prev();
            startAutoplay();
        });
    }

    if (nextBtn) {
        nextBtn.addEventListener('click', function () {
            next();
            startAutoplay();
        });
    }

    slider.addEventListener('mouseenter', stopAutoplay);
    slider.addEventListener('mouseleave', startAutoplay);

    slider.addEventListener('touchstart', function (e) {
        touchStartX = e.changedTouches[0].clientX;
        touchStartY = e.changedTouches[0].clientY;
        touchDeltaX = 0;
        stopAutoplay();
    }, { passive: true });

    slider.addEventListener('touchmove', function (e) {
        var touch = e.changedTouches[0];
        var deltaX = touch.clientX - touchStartX;
        var deltaY = touch.clientY - touchStartY;
        if (Math.abs(deltaY) > Math.abs(deltaX)) return;
        touchDeltaX = deltaX;
    }, { passive: true });

    slider.addEventListener('touchend', function (e) {
        var touch = e.changedTouches[0];
        var deltaX = touch.clientX - touchStartX;
        var deltaY = touch.clientY - touchStartY;
        if (Math.abs(deltaX) > 50 && Math.abs(deltaX) > Math.abs(deltaY)) {
            if (deltaX < 0) next();
            else prev();
        }
        startAutoplay();
    }, { passive: true });

    if (total <= 1) {
        if (prevBtn) prevBtn.style.display = 'none';
        if (nextBtn) nextBtn.style.display = 'none';
        if (dotsWrap) dotsWrap.style.display = 'none';
        updateHeight();
        return;
    }

    slides.forEach(function (slide, i) {
        var img = slide.querySelector('.hero-img');
        if (img) {
            if (img.complete) {
                if (i === 0) updateHeight();
            } else {
                img.addEventListener('load', function () {
                    updateHeight();
                });
            }
        }
    });

    window.addEventListener('resize', updateHeight);

    goTo(0);
    startAutoplay();
})();
