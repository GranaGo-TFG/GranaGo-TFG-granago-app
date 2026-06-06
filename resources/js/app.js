import * as bootstrap from 'bootstrap';
import './mapa-osm';

window.bootstrap = bootstrap;

document.addEventListener('DOMContentLoaded', function () {
    var floatingGuide = document.querySelector('[data-floating-guide]');

    if (!floatingGuide) {
        return;
    }

    var toggle = floatingGuide.querySelector('.floating-guide-toggle');
    var card = floatingGuide.querySelector('.floating-guide-card');
    var answer = floatingGuide.querySelector('[data-floating-guide-answer]');
    var questionButtons = floatingGuide.querySelectorAll('[data-question]');
    var close = floatingGuide.querySelector('[data-floating-guide-close]');

    if (!toggle || !card) {
        return;
    }

    var answers = {
        retos: 'Los retos son pruebas por Granada. Entra en uno, lee la descripcion y completa lo que pide antes de que caduque.',
        pruebas: 'Cuando estes en el detalle de un reto, sube una foto clara del lugar o elemento pedido. La validacion queda pendiente hasta que se revise.',
        puntos: 'Los puntos se suman cuando una prueba queda validada. El ranking ordena a los usuarios por sus puntos guardados en la base de datos.',
        planes: 'La app tiene una version gratuita y plantea planes premium para mejorar la experiencia con rutas, retos exclusivos o ventajas para grupos.',
    };
    var setOpen = function (isOpen) {
        floatingGuide.classList.toggle('is-open', isOpen);
        floatingGuide.classList.toggle('is-docked', !isOpen);
        toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        toggle.setAttribute('aria-label', isOpen ? 'Cerrar asistente' : 'Abrir asistente');
        card.setAttribute('aria-hidden', isOpen ? 'false' : 'true');
    };

    floatingGuide.classList.add('is-docked');

    toggle.addEventListener('click', function () {
        setOpen(!floatingGuide.classList.contains('is-open'));
    });

    questionButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var questionKey = button.getAttribute('data-question');

            if (!answer || !answers[questionKey]) {
                return;
            }

            questionButtons.forEach(function (item) {
                item.classList.toggle('is-active', item === button);
            });

            answer.classList.remove('is-updated');
            answer.textContent = answers[questionKey];
            window.requestAnimationFrame(function () {
                answer.classList.add('is-updated');
            });
        });
    });

    if (close) {
        close.addEventListener('click', function () {
            setOpen(false);
        });
    }

    document.addEventListener('click', function (event) {
        if (!floatingGuide.classList.contains('is-open') || floatingGuide.contains(event.target)) {
            return;
        }

        setOpen(false);
    });

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            setOpen(false);
        }
    });

    if (window.location.pathname === '/home') {
        window.setTimeout(function () {
            setOpen(true);
        }, 650);
    }
});

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
