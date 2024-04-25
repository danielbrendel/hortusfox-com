<div class="page-content admin-area margin-fix">
    <h2>Admin area</h2>

    @include('flashmsg.php')

    <p class="is-font-medium">
        Here you can manage admin settings.
    </p>

    <h3>Software version</h3>

    <form method="POST" action="{{ url('/admin/softver/save?token=' . $_GET['token']) }}">
        @csrf

        <div class="field">
            <div class="control">
                <input type="text" class="input" name="software_version" value="{{ $app_settings->get('software_version') }}"/>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="Save"/>
            </div>
        </div>
    </form>

    <hr/>

    <h3>Newsletter</h3>

    <form method="POST" action="{{ url('/admin/newsletter/go?token=' . $_GET['token']) }}">
        @csrf

        <div class="field">
            <div class="control">
                <input type="text" class="input" name="subject" id="newsletter-subject" value="{{ ($app_settings->get('newsletter_subject')) ?? '' }}"/>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <textarea class="textarea" name="content" id="newsletter-content">{{ ($app_settings->get('newsletter_content')) ?? '' }}</textarea>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="Go"/>
                &nbsp;<a class="newsletter-preview" href="javascript:void(0);" onclick="window.vue.previewNewsletter(document.getElementById('newsletter-subject'), document.getElementById('newsletter-content'), '{{ $_GET['token'] }}');">Preview</a>
            </div>
        </div>
    </form>

    <hr/>

    <p>Toggle Newsletter process</p>

    <p>
        <a href="{{ url('/admin/newsletter/toggle?token=' . $_GET['token']) }}" class="button {{ ((app('newsletter_enable')) ? 'is-danger' : 'is-success') }}">{{ ((app('newsletter_enable')) ? 'Disable' : 'Enable') }}</a>
    </p>
</div>