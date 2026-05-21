import 'bootstrap';
import './mapa-osm';

document.addEventListener('DOMContentLoaded', function () {
    var themeToggle = document.getElementById('theme-toggle');

    if (!themeToggle) {
        return;
    }

    var root = document.documentElement;

    var applyTheme = function (theme) {
        root.setAttribute('data-theme', theme);
        root.style.colorScheme = theme;
        themeToggle.setAttribute('aria-pressed', theme === 'dark' ? 'true' : 'false');
        themeToggle.setAttribute(
            'aria-label',
            theme === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro'
        );
    };

    var currentTheme = root.getAttribute('data-theme') || 'light';
    applyTheme(currentTheme);

    themeToggle.addEventListener('click', function () {
        var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';

        localStorage.setItem('granago-theme', nextTheme);
        applyTheme(nextTheme);
    });
});
