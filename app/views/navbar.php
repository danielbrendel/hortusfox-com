<nav class="navbar is-fixed-top" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item navbar-item-brand is-font-title" href="{{ url('/') }}">
            <img src="{{ asset('img/logo.png') }}" alt="Logo"/>&nbsp;{{ env('APP_NAME') }}
        </a>

        <a role="button" class="navbar-burger burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-end">
            @if (env('DEMO_ENABLE'))
            <div class="navbar-item">
                <a class="button is-success" href="{{ url('/demo') }}">
                    Live Demo
                </a>
            </div>
            @endif 

            @if (env('APP_ENABLE_PHOTO_SHARE'))
            <div class="navbar-item">
                <a class="button is-link" href="{{ url('/community') }}">
                    Community
                </a>
            </div>
            @endif

            <a class="navbar-item" href="{{ url('/screenshots') }}">
                Screenshots
            </a>

            <a class="navbar-item" href="{{ url('/themes') }}">
                Themes
            </a>

            <a class="navbar-item" href="{{ env('LINK_DOCUMENTATION') }}">
                Documentation
            </a>

            <a class="navbar-item" href="{{ url('/tutorials') }}">
                Tutorials
            </a>

            <a class="navbar-item" href="{{ url('/faq') }}">
                FAQ
            </a>

            <a class="navbar-item" href="{{ env('LINK_GITHUB') }}">
                GitHub
            </a>

            @if (env('HELPREALM_RESTAPI_ENABLE'))
            <a class="navbar-item" href="{{ url('/support') }}">
                <i class="fas fa-headset"></i>&nbsp;Support
            </a>
            @endif
        </div>
    </div>
</nav>