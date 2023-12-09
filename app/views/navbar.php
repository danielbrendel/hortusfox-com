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
            <a class="navbar-item" href="{{ url('/') }}">
                Download
            </a>

            <a class="navbar-item" href="{{ url('/') }}">
                Screenshots
            </a>

            <a class="navbar-item" href="{{ url('/') }}">
                Documentation
            </a>

            <a class="navbar-item" href="{{ url('/') }}">
                FAQ
            </a>

            <a class="navbar-item" href="{{ url('/') }}">
                GitHub
            </a>
        </div>
    </div>
</nav>