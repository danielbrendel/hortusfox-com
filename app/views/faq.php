<div class="page-content margin-fix">
    <h2>Frequently Asked Questions</h2>

    <p class="is-font-medium">
        Here you can find the most asked questions with answers.
        If you don't find an answer here, feel free to contact us.
    </p>

    <div class="faq">
        @foreach ($faqs as $faq)
            <div class="faq-item">
                <a name="{{ slug($faq->get('question')) }}"></a>
                <div class="faq-item-header">
                    <div class="faq-item-question"><strong><i class="fas fa-question-circle"></i>&nbsp;{{ $faq->get('question') }}</strong></div>
                    <div class="faq-item-action"><a class="is-default-link" href="javascript:void(0);" onclick="window.vue.copyToClipboard('{{ url('/faq#' . slug($faq->get('question'))) }}', 'Link to FAQ item was copied to clipboard');"><i class="far fa-copy"></i></a></div>
                </div>

                <div class="faq-item-content"><small>{!! $faq->get('answer') !!}</small></div>
            </div>
        @endforeach
    </div>

    <p class="is-font-medium">Still having questions?</p>

    <p>
        <a class="button is-link button-stretched" href="{{ ((env('HELPREALM_RESTAPI_ENABLE')) ? url('/support') : 'mailto:' . env('APP_CONTACT')) }}">Contact Us</a>
    </p>
</div>