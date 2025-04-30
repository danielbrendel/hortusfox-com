@if (AffiliateModel::getCount() > 0)
    <div class="affiliates" id="affiliates">
        <h3>Affiliates & Partners</h3>

        @foreach (AffiliateModel::getList() as $affiliate)
            <div class="affiliate" onclick="window.open('{{ $affiliate->get('link') }}');">
                <div class="affiliate-logo">
                    <img src="{{ asset('img/affiliates/' . $affiliate->get('logo')) }}" alt="logo"/>
                </div>

                <div class="affiliate-info">
                    <div class="affiliate-label">{{ $affiliate->get('label') }}</div>
                    <div class="affiliate-description">{{ $affiliate->get('description') }}</div>
                </div>
            </div>
        @endforeach

        <div class="affiliate-moreinfo">
            <a href="{{ url('/partnership') }}">More on partnership</a>
        </div>
    </div>
@endif