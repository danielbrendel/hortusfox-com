<div class="page-content margin-fix">
    <h2>Frequently Asked Questions</h2>

    <p class="is-font-medium">
        Here you can find the most asked questions with answers.
    </p>

    <div class="faq">
        @foreach ($faqs as $faq)
            <div class="faq-item">
                <strong><i class="fas fa-question-circle"></i>&nbsp;{{ $faq['question'] }}</strong>
                <small>{{ $faq['answer'] }}</small>
            </div>
        @endforeach
    </div>
</div>