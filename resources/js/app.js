import 'bootstrap';
import './mapa-osm';

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
