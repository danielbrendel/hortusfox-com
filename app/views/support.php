<div class="page-content margin-fix">
    <h2><i class="fas fa-headset"></i>&nbsp;Support</h2>

    <p class="is-font-medium">
        Are you having problems? Something is not working? Contact us!
    </p>

    @include('flashmsg.php')

    <div class="support">
        @if (env('HELPREALM_RESTAPI_ENABLE'))
        <form method="POST" action="{{ url('/support') }}" enctype="multipart/form-data">
            @csrf

            <div class="control">
                <label class="label">Your Name</label>
                <div class="field">
                    <input type="text" class="input" name="name" required/>
                </div>
            </div>

            <div class="control">
                <label class="label">Your E-Mail</label>
                <div class="field">
                    <input type="email" class="input" name="email" required/>
                </div>
            </div>

            <div class="control">
                <label class="label">Subject</label>
                <div class="field">
                    <input type="text" class="input" name="subject" required/>
                </div>
            </div>

            <div class="control">
                <label class="label">Message</label>
                <div class="field">
                    <textarea class="textarea" name="message" required></textarea>
                </div>
            </div>

            <div class="control">
                <label class="label">{{ $captcha[0] }} + {{ $captcha[1] }} = ?</label>
                <div class="field">
                    <input type="text" class="input" name="captcha" required/>
                </div>
            </div>

            <div class="control">
                <div class="field">
                    <input type="checkbox" name="consent" value="1" required>&nbsp;&nbsp;I am aware that a response can take up to 5 business days.
                </div>
            </div>

            <div class="control">
                <div class="field">
                    <button type="submit" class="button is-link">Submit</button>
                </div>
            </div>
        </form>
        @elseif (env('DISCORD_SUPPORT_ENABLE'))
            <iframe class="discord-widget" src="{{ env('DISCORD_SUPPORT_URL') }}" width="350" height="500" frameborder="0" sandbox="allow-same-origin allow-scripts allow-popups"></iframe>
        @else
            <b>Warning: misconfigured support endpoints detected</b>
        @endif
    </div>
</div>