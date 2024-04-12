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
                <input type="text" class="input" name="subject" value="{{ ($app_settings->get('newsletter_subject')) ?? '' }}"/>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <textarea class="textarea" name="content">{{ ($app_settings->get('newsletter_content')) ?? '' }}</textarea>
            </div>
        </div>

        <div class="field">
            <div class="control">
                <input type="submit" class="button is-success" value="Go"/>
            </div>
        </div>
    </form>
</div>