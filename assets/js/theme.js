// Init AOS
if (typeof AOS !== 'undefined') {
    AOS.init({
        once: true,
        offset: 50,
        duration: 800,
        easing: 'ease-out-cubic'
    });
}

// FAQ Accordion
document.querySelectorAll('.faq-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var item = this.closest('.faq-item');
        item.classList.toggle('-active');
    });
});

// Intro "read more" toggle — reveals hidden text within the same section
document.querySelectorAll('.intro-toggle').forEach(function(btn) {
    btn.addEventListener('click', function() {
        var section = this.closest('.section-intro');
        if (!section) return;
        var open = section.classList.toggle('is-open');
        this.setAttribute('aria-expanded', open ? 'true' : 'false');
        if (this.dataset.labelOpen && this.dataset.labelClose) {
            this.textContent = open ? this.dataset.labelClose : this.dataset.labelOpen;
        }
    });
});

// Counter animation with easeOutCubic
document.querySelectorAll('.stat-number[data-count]').forEach(function(el) {
    var target = parseInt(el.dataset.count);
    var suffix = el.dataset.suffix || '';
    var counted = false;
    var duration = 2000; // ms

    function easeOutCubic(t) {
        return 1 - Math.pow(1 - t, 2);
    }

    var observer = new IntersectionObserver(function(entries) {
        if (entries[0].isIntersecting && !counted) {
            counted = true;
            var start = performance.now();

            function animate(now) {
                var progress = Math.min((now - start) / duration, 1);
                var eased = easeOutCubic(progress);
                var current = Math.round(eased * target);
                el.textContent = current + suffix;
                if (progress < 1) requestAnimationFrame(animate);
            }

            requestAnimationFrame(animate);
        }
    }, { threshold: 0.5 });

    observer.observe(el);
});

document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        const href = this.getAttribute('href');
        const target = document.querySelector(href);
        if (target) {
            e.preventDefault();
            const topOffset = 150;
            const elementPosition = target.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.scrollY - topOffset;
            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    });
});

(function($){
    $(document).ready(function() {

        var isSafari = /^((?!chrome|android).)*safari/i.test(navigator.userAgent);
        if (isSafari) {
            document.body.classList.add('safari');
        }

        contactPopup();

        function contactPopup() {
            var $popup = $('#contactPopup');
            var $body = $('body');

            // Open popup
            $(document).on('click', '[data-popup="contact"]', function(e) {
                e.preventDefault();
                $popup.removeClass('-confirmed');
                $popup.addClass('-active');
                $body.addClass('popup-active');
            });

            function closePopup() {
                if ($popup.hasClass('-confirmed')) {
                    // After form was submitted, reload to reset form
                    window.location.reload();
                } else {
                    $popup.removeClass('-active');
                    $body.removeClass('popup-active');
                }
            }

            // Close popup - X button
            $popup.find('.popup-contact-close').on('click', closePopup);

            // Close popup - overlay click
            $popup.find('.popup-contact-overlay').on('click', closePopup);

            // Close popup - Escape key
            $(document).on('keydown', function(e) {
                if (e.key === 'Escape' && $popup.hasClass('-active')) {
                    closePopup();
                }
            });

            // Select color change on value pick
            $popup.on('change', 'select', function() {
                if ($(this).val()) {
                    $(this).addClass('gf-value-selected');
                } else {
                    $(this).removeClass('gf-value-selected');
                }
            });

            // After Gravity Forms submission - show confirmation state
            $(document).on('gform_confirmation_loaded', function() {
                $popup.addClass('-confirmed');
            });
        }

	//console.clear();
        console.log("%c ".concat("Handcrafted with ❤️️ by vDisain - www.vdisain.lv"," "),"\n padding: 0.5em;\n border-radius: 0.4em;\n color: white;\n background: black;\n font-size: 19px;\n font-weight: bold;\n font-family: Courier;\n line-height: 1.5em;\n");
    });
}(window.jQuery));