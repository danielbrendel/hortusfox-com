<!doctype html>
<html lang="{{ getLocale() }}">
    <head>
        <meta charset="utf-8"/>

        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <meta name="author" content="{{ env('APP_AUTHOR') }}">
        <meta name="description" content="{{ (isset($_meta_description)) ? $_meta_description : env('APP_DESCRIPTION') }}">

        <meta name="og:title" property="og:title" content="{{ (isset($_meta_title)) ? env('APP_NAME') . ' - ' . $_meta_title : env('APP_NAME') }}">
        <meta name="og:description" property="og:description" content="{{ (isset($_meta_description)) ? $_meta_description : env('APP_DESCRIPTION') }}">
        <meta name="og:url" property="og:url" content="{{ (isset($_meta_url)) ? $_meta_url : url('/') }}">
        <meta name="og:image" property="og:image" content="{{ asset('img/screenshots/screenshot-desktop.png') }}">
		
        <title>{{ (isset($_meta_title)) ? env('APP_NAME') . ' - ' . $_meta_title : env('APP_NAME') }}</title>

        <link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>

        @if (env('APP_DEBUG'))
        <script src="{{ asset('js/vue.js') }}"></script>
        @else
        <script src="{{ asset('js/vue.min.js') }}"></script>
        @endif
        <script src="{{ asset('js/fontawesome.js') }}"></script>
        @if (env('HELPREALM_WIDGET_ENABLE'))
        <script src="{{ env('HELPREALM_URL') . '/js/widget.js' }}"></script>
        @endif
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
                        <div class="is-margin-bottom-10">
                            @if (env('LINK_PERSONAL'))
                            &copy; {{ date('Y') }} by <a href="{{ env('LINK_PERSONAL') }}">{{ env('APP_AUTHOR') }}</a>.
                            @else
                            &copy; {{ date('Y') }} by {{ env('APP_AUTHOR') }}.
                            @endif
                        </div>

                        <div>
                            HortusFox is open-sourced software licensed under the MIT license. 
                            Feel free to support the project via <a href="{{ env('LINK_GITHUB') }}">GitHub</a>.
                            Please <a href="mailto:{{ env('APP_CONTACT') }}">contact our support</a> for any issues or feedback.
                        </div>
                    </div>

                    <div class="column is-3 is-desktop-right">
                        @if (env('LINK_GITHUB'))
                        <span>
                            <a href="{{ env('LINK_GITHUB') }}" target="_blank">
                                <i class="fab fa-github fa-2x"></i>
                            </a>
                        </span>
                        @endif

                        @if (env('LINK_DISCORD'))
                        <span>
                            <a href="{{ env('LINK_DISCORD') }}" target="_blank">
                                <i class="fab fa-discord fa-2x"></i>
                            </a>
                        </span>
                        @endif

                        @if (env('LINK_REDDIT'))
                        <span>
                            <a href="{{ env('LINK_REDDIT') }}" target="_blank">
                                <i class="fab fa-reddit fa-2x"></i>
                            </a>
                        </span>
                        @endif

                        @if (env('LINK_YOUTUBE'))
                        <span>
                            <a href="{{ env('LINK_YOUTUBE') }}" target="_blank">
                                <i class="fab fa-youtube fa-2x"></i>
                            </a>
                        </span>
                        @endif

                        @if (env('LINK_MASTODON'))
                        <span>
                            <a href="{{ env('LINK_MASTODON') }}" target="_blank">
                                <i class="fab fa-mastodon fa-2x"></i>
                            </a>
                        </span>
                        @endif

                        @if (env('LINK_INSTAGRAM'))
                        <span>
                            <a href="{{ env('LINK_INSTAGRAM') }}" target="_blank">
                                <i class="fab fa-instagram fa-2x"></i>
                            </a>
                        </span>
                        @endif

                        <span>
                            <a href="mailto:{{ env('APP_CONTACT') }}" target="_blank">
                                <i class="fas fa-envelope fa-2x"></i>
                            </a>
                        </span>
                    </div>

                    <div class="column is-3"></div>
                </div>
            </div>

            <div class="modal" :class="{'is-active': bShowPreviewImageModal}">
                <div class="modal-background"></div>

                <div class="modal-content">
                    <p class="image">
                        <a href="#">
                            <img id="preview-image-modal-img" alt="image">
                        </a>
                    </p>
                </div>

                <button class="modal-close is-large" aria-label="close" onclick="window.vue.bShowPreviewImageModal = false;"></button>
            </div>

            @if (env('HELPREALM_WIDGET_ENABLE'))
                <div id="support-widget"></div>
            @endif
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

                @if (env('HELPREALM_WIDGET_ENABLE'))
                    let widget = new HelpRealmWidget({
                        elem: '#support-widget',
                        workspace: '{{ env('HELPREALM_WORKSPACE') }}',
                        apiKey: '{{ env('HELPREALM_WIDGET_TOKEN') }}',
                        header: '{{ asset('img/background.jpg') }}',
                        logo: '{{ asset('img/logo_black_bg.png') }}',
                        button: null,
                        fileUpload: false,
                        lang: {
                            title: 'Support',
                            lblInputName: 'Enter your name',
                            lblInputEmail: 'Enter your E-Mail',
                            lblInputSubject: 'What is your topic?',
                            lblInputMessage: 'What is on your mind?',
                            lblInputFile: 'Attachment (optional)',
                            btnSubmit: 'Submit',
                            error: 'Elem {elem} is invalid or missing',
                            access: 'Access denied!',
                        },
                        ticket: {
                            type: {{ env('HELPREALM_TICKET_TYPE') }},
                            prio: {{ SupportModule::TICKET_PRIORITY_LOW }}
                        },
                    });
                @endif
            });
        </script>
    </body>
</html>