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

        <div class="column is-3 is-desktop-right is-span-margin-bottom">
            <div>
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

                @if (env('LINK_FOSSVIDEO'))
                <span>
                    <a href="{{ env('LINK_FOSSVIDEO') }}" target="_blank">
                        <i class="fas fa-video fa-2x"></i>
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

                @if (env('LINK_PIXELFED'))
                <span>
                    <a href="{{ env('LINK_PIXELFED') }}" target="_blank">
                        <i class="fas fa-camera fa-2x"></i>
                    </a>
                </span>
                @endif

                <span>
                    <a href="mailto:{{ env('APP_CONTACT') }}" target="_blank">
                        <i class="fas fa-envelope fa-2x"></i>
                    </a>
                </span>
            </div>

            <div class="page-footer-resources">
                <div>
                    <a href="{{ url('/support') }}">Support</a>
                </div>

                <div>
                    <a href="{{ url('/partnership') }}">Partnership</a>
                </div>
            </div>
        </div>

        <div class="column is-3"></div>
    </div>
</div>