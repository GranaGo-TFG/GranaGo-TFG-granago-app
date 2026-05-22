(function () {
    var storageKey = 'granago-theme';
    var root = document.documentElement;
    var savedTheme = localStorage.getItem(storageKey);
    var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    var theme = savedTheme || (prefersDark ? 'dark' : 'light');

    var applyTheme = function (nextTheme) {
        root.setAttribute('data-theme', nextTheme);
        root.style.colorScheme = nextTheme;

        var toggle = document.getElementById('auth-theme-toggle');

        if (!toggle) {
            return;
        }

        toggle.setAttribute('aria-pressed', nextTheme === 'dark' ? 'true' : 'false');
        toggle.setAttribute('aria-label', nextTheme === 'dark' ? 'Cambiar a modo claro' : 'Cambiar a modo oscuro');
    };

    applyTheme(theme);

    document.addEventListener('DOMContentLoaded', function () {
        var toggle = document.getElementById('auth-theme-toggle');

        if (!toggle) {
            return;
        }

        applyTheme(root.getAttribute('data-theme') || theme);

        toggle.addEventListener('click', function () {
            var nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';

            localStorage.setItem(storageKey, nextTheme);
            applyTheme(nextTheme);
        });
    });
}());
