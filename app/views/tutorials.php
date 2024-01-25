<div class="page-content margin-fix">
    <h2>Video Tutorials</h2>

    <p class="is-font-medium">
        Here you can find video tutorials around HortusFox.
    </p>

    <div class="tutorials">
        <div class="tutorial-section">
            <h3>Setup</h3>

            @foreach ($videos['install'] as $install_video)
            <div class="tutorial-section-item">
                <h4>{{ $install_video->get('title') }}</h4>

                <iframe src="{{ $install_video->get('url') }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            @endforeach
        </div>
        
        <hr/>

        <div class="tutorial-section">
            <h3>Usage</h3>

            @foreach ($videos['usage'] as $usage_video)
            <div class="tutorial-section-item">
                <h4>{{ $usage_video->get('title') }}</h4>

                <iframe src="{{ $usage_video->get('url') }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
            @endforeach
        </div>
    </div>

    
</div>