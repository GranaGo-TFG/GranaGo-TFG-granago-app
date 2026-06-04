import * as bootstrap from 'bootstrap';
import './mapa-osm';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', function () {
    var navbar = document.querySelector('.navbar');

    if (!navbar) {
        return;
    }

    var lastScrollY = window.scrollY;
    var ticking = false;
    var minScrollBeforeHide = 90;
    var scrollDelta = 8;

    var hasOpenNavigation = function () {
        return Boolean(
            navbar.querySelector('.navbar-collapse.show') ||
            navbar.querySelector('.dropdown-menu.show')
        );
    };

    var updateNavbar = function () {
        var currentScrollY = window.scrollY;
        var distance = currentScrollY - lastScrollY;

        if (hasOpenNavigation() || currentScrollY < minScrollBeforeHide) {
            navbar.classList.remove('is-hidden');
            lastScrollY = currentScrollY;
            ticking = false;
            return;
        }

        if (Math.abs(distance) < scrollDelta) {
            ticking = false;
            return;
        }

        if (distance > 0) {
            navbar.classList.add('is-hidden');
        } else {
            navbar.classList.remove('is-hidden');
        }

        lastScrollY = currentScrollY;
        ticking = false;
    };

    window.addEventListener('scroll', function () {
        if (ticking) {
            return;
        }

        window.requestAnimationFrame(updateNavbar);
        ticking = true;
    }, { passive: true });

    navbar.addEventListener('focusin', function () {
        navbar.classList.remove('is-hidden');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var themeToggles = document.querySelectorAll('.theme-toggle');

    if (!themeToggles.length) {
        return;
    }

    var root = document.documentElement;

    var applyTheme = function (theme) {
        root.setAttribute('data-theme', theme);
        root.style.colorScheme = theme;

        themeToggles.forEach(function (themeToggle) {
            themeToggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
            themeToggle.setAttribute(
                'aria-label',
                theme === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'
            );
        });
    };

    var currentTheme = root.getAttribute('data-theme') || 'light';
    applyTheme(currentTheme);

    themeToggles.forEach(function (themeToggle) {
        themeToggle.addEventListener('click', function () {
            var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';

            localStorage.setItem('granago-theme', nextTheme);
            applyTheme(nextTheme);
        });
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var revealItems = document.querySelectorAll('.reveal-item');

    if (!revealItems.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });

        return;
    }

    var revealObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) {
                return;
            }

            entry.target.classList.add('is-visible');
            observer.unobserve(entry.target);
        });
    }, {
        rootMargin: '0px 0px -8% 0px',
        threshold: 0.16,
    });

    revealItems.forEach(function (item) {
        revealObserver.observe(item);
    });
});

document.addEventListener('DOMContentLoaded', function () {
    var counterItems = document.querySelectorAll(
        '.profile-stats strong, .ranking-spotlight aside strong, .ranking-top-card em, .ranking-row em'
    );

    if (!counterItems.length || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        return;
    }

    var parseCounter = function (text) {
        var trimmed = text.trim();
        var match = trimmed.match(/^([#x+]?)(\d+(?:[.,]\d+)?)(.*)$/);

        if (!match) {
            return null;
        }

        var value = Number(match[2].replace(',', '.'));

        if (!Number.isFinite(value)) {
            return null;
        }

        return {
            prefix: match[1] || '',
            value: value,
            suffix: match[3] || '',
            decimals: match[2].includes(',') || match[2].includes('.') ? 2 : 0,
            decimalSeparator: match[2].includes(',') ? ',' : '.',
        };
    };

    var animateCounter = function (element) {
        var parsed = parseCounter(element.textContent);

        if (!parsed || parsed.value === 0) {
            return;
        }

        var start = null;
        var duration = 720;

        var step = function (timestamp) {
            if (start === null) {
                start = timestamp;
            }

            var progress = Math.min((timestamp - start) / duration, 1);
            var eased = 1 - Math.pow(1 - progress, 3);
            var current = parsed.value * eased;
            var output = parsed.decimals ? current.toFixed(parsed.decimals) : Math.round(current).toString();

            element.textContent = parsed.prefix + output.replace('.', parsed.decimalSeparator) + parsed.suffix;

            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };

        window.requestAnimationFrame(step);
    };

    if (!('IntersectionObserver' in window)) {
        counterItems.forEach(animateCounter);
        return;
    }

    var counterObserver = new IntersectionObserver(function (entries, observer) {
        entries.forEach(function (entry) {
            if (!entry.isIntersecting) {
                return;
            }

            animateCounter(entry.target);
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.6,
    });

    counterItems.forEach(function (item) {
        counterObserver.observe(item);
    });
});
