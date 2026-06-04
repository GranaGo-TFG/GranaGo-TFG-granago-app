<footer class="site-footer">
    <div class="container">
        <div class="site-footer-panel">
            <div class="site-footer-main">
                <a href="{{ auth()->check() ? route('home') : url('/') }}" class="site-footer-brand">
                    <img src="{{ asset('images/Logo.png') }}" alt="" aria-hidden="true">
                    <span>GranaGO!</span>
                </a>
                <p>Retos urbanos, comunidad y recompensas para descubrir Granada de forma participativa.</p>
            </div>

            <div class="site-footer-group">
                <span>Legal</span>
                <nav class="site-footer-links" aria-label="Enlaces legales">
                    <a href="{{ route('legal.privacidad') }}">Privacidad</a>
                    <a href="{{ route('legal.aviso-legal') }}">Aviso legal</a>
                    <a href="{{ route('legal.contacto') }}">Contacto</a>
                </nav>
            </div>

            <div class="site-footer-group">
                <span>Redes</span>
                <div class="site-footer-social" aria-label="Redes sociales">
                    <a href="#" aria-label="Instagram">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <rect x="4" y="4" width="16" height="16" rx="5"></rect>
                            <circle cx="12" cy="12" r="3.2"></circle>
                            <path d="M16.8 7.2h.01"></path>
                        </svg>
                    </a>
                    <a href="#" aria-label="X">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M4 4l16 16"></path>
                            <path d="M20 4L4 20"></path>
                        </svg>
                    </a>
                    <a href="#" aria-label="TikTok">
                        <svg viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M14 4v10.2a4.2 4.2 0 1 1-4.2-4.2"></path>
                            <path d="M14 4c.6 2.7 2.4 4.3 5 4.6"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <div class="site-footer-bottom">
                <span class="site-footer-beta">
                    GranaGO!
                    <span class="site-footer-beta-mark" aria-hidden="true">β</span>
                </span>
                <span>Version 0.1 beta</span>
            </div>
        </div>
    </div>
</footer>
