<div class="page-content margin-fix">
    <h2>Video Tutorials</h2>

    <p class="is-font-medium">
        Here you can find video tutorials around HortusFox. 
        Want to get to know how to setup the product? Want to learn more about the usage? 
        We got you covered!
    </p>

    <div class="is-margin-bottom-10">
        <a href="{{ env('LINK_YOUTUBE') }}" target="_blank"><img src="https://img.shields.io/badge/youtube-red?style=for-the-badge" alt="social-youtube"></a>&nbsp;
        <a href="{{ env('LINK_FOSSVIDEO') }}" target="_blank"><img src="https://img.shields.io/badge/foss.video-purple?style=for-the-badge" alt="social-peertube"></a>
    </div>

    <div><hr/></div>

    <div class="tutorials-list">
        <p>Table of Contents</p>

        <ul>
            @foreach ($categories as $category)
            <li><a class="is-default-link" href="{{ url('/tutorials#' . $category->get('category')) }}">{{ $category->get('category') }}</a></li>
            @endforeach
        </ul>
    </div>

    <div class="tutorials">
        <?php $lastCat = ''; ?>

        @foreach ($videos as $install_video)
            <?php 
                if ($lastCat !== $install_video->get('category')) {
                    if ($lastCat !== '') {
                        echo '</div>';
                    }

                    $lastCat = $install_video->get('category');

                    echo '<div class="tutorial-section"><a name="' . $install_video->get('category') . '"></a><h3>' . ucfirst($install_video->get('category')) . '</h3>';
                }
            ?>

            <div class="tutorial-section-item">
                <h4>{{ $install_video->get('title') }}</h4>

                <iframe src="{{ $install_video->get('url') }}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
            </div>
        @endforeach
    </div>

    
</div>