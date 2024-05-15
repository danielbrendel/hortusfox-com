<div class="page-content margin-fix">
    <h2>Video Tutorials</h2>

    <p class="is-font-medium">
        Here you can find video tutorials around HortusFox.
    </p>

    <div class="tutorials">
        <?php $lastCat = ''; ?>

        @foreach ($videos as $install_video)
            <?php 
                if ($lastCat !== $install_video->get('category')) {
                    if ($lastCat !== '') {
                        echo '</div>';
                    }

                    $lastCat = $install_video->get('category');

                    echo '<div class="tutorial-section"><h3>' . ucfirst($install_video->get('category')) . '</h3>';
                }
            ?>

            <div class="tutorial-section-item">
                <h4>{{ $install_video->get('title') }}</h4>

                <iframe src="{{ $install_video->get('url') }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        @endforeach
    </div>

    
</div>