<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="author" content="{{ env('APP_AUTHOR') }}">
        <meta name="description" content="{{ env('APP_DESCRIPTION') }}">

        <meta name="og:title" property="og:title" content="{{ env('APP_NAME') }}">
        <meta name="og:description" property="og:description" content="{{ env('APP_DESCRIPTION') }}">
        <meta name="og:url" property="og:url" content="{{ url('/') }}">
        <meta name="og:image" property="og:image" content="{{ asset('img/logo.png') }}">
		
        <title>{{ env('APP_NAME') }} {{ (isset($_meta_title)) ? ' - ' . $_meta_title : '' }}</title>

        <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>

        @if (env('APP_DEBUG'))
        <script src="{{ asset('js/vue.js') }}"></script>
        @else
        <script src="{{ asset('js/vue.min.js') }}"></script>
        @endif
        <script src="{{ asset('js/fontawesome.js') }}"></script>
    </head>

    <body>
        <div id="app">
            @include('navbar.php')

            @if ((isset($show_header)) && ($show_header))
                @include('header.php')
            @endif

            <div class="container">
                <div class="columns">
                    <div class="column is-2"></div>

                    <div class="column is-8">
                        {%content%}
                    </div>

                    <div class="column is-2"></div>
                </div>
            </div>

            <div class="page-footer">
                <div class="columns">
                    <div class="column is-3"></div>

                    <div class="column is-3">
                        <div class="is-margin-bottom-10">&copy; {{ date('Y') }} by {{ env('APP_AUTHOR') }}.</div>

                        <div>
                            HortusFox is open-sourced software licensed under the MIT license. 
                            Feel free to support the project via <a href="{{ env('LINK_GITHUB') }}">GitHub</a>.
                            Please <a href="mailto:{{ env('APP_CONTACT') }}">contact our support</a> for any issues or feedback.
                        </div>
                    </div>

                    <div class="column is-3 is-desktop-right">
                        <span>
                            <a href="{{ env('LINK_GITHUB') }}" target="_blank">
                                <i class="fab fa-github fa-2x"></i>
                            </a>
                        </span>

                        <span>
                            <a href="{{ env('LINK_REDDIT') }}" target="_blank">
                                <i class="fab fa-reddit fa-2x"></i>
                            </a>
                        </span>

                        <span>
                            <a href="{{ env('LINK_YOUTUBE') }}" target="_blank">
                                <i class="fab fa-youtube fa-2x"></i>
                            </a>
                        </span>

                        <span>
                            <a href="{{ env('LINK_INSTAGRAM') }}" target="_blank">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                        </span>

                        <span>
                            <a href="mailto:{{ env('APP_CONTACT') }}" target="_blank">
                                <i class="fas fa-envelope fa-2x"></i>
                            </a>
                        </span>
                    </div>

                    <div class="column is-3"></div>
                </div>
            </div>
        </div>

        <script src="{{ asset('js/app.js', true) }}"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                window.vue.initNavbar();
                
                document.body.addEventListener('scroll', function() {
                    if ((document.body.scrollTop > document.getElementsByClassName('navbar')[0].offsetHeight + 10) || (document.documentElement.scrollTop > document.getElementsByClassName('navbar')[0].offsetHeight + 10)) {
                        document.getElementsByClassName('navbar')[0].classList.add('navbar-background-color');  
                    } else {
                        document.getElementsByClassName('navbar')[0].classList.remove('navbar-background-color');
                    }
                });
            });
        </script>
    </body>
</html>