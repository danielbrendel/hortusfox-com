<div class="page-header" style="background-image: url('{{ asset('img/background.jpg') }}');">
    <div class="page-header-overlay">
        <div class="page-header-content">
            <h1>HortusFox Plant Parenting & Tracking System</h1>

            <h2>Collaborative self-hosted plant tracking & management</h2>

            <div class="page-header-buttons">
                <div>
                    <a class="button is-rounded is-outlined is-large is-success" href="javascript:void(0);" onclick="window.vue.scrollTo('a[name=downloads]');">Download</a>
                </div>

                <div>
                    <a class="button is-rounded is-outlined is-large is-info" href="javascript:void(0);" onclick="window.vue.scrollTo('a[name=info]');">Read more</a>
                </div>
            </div>

            <div class="page-header-badges">
                <img src="https://img.shields.io/github/stars/{{ env('LINK_REPOSITORY') }}?style=for-the-badge&color=green" alt="repository-stars"/>
                <img src="https://img.shields.io/github/forks/{{ env('LINK_REPOSITORY') }}?style=for-the-badge&color=blue" alt="repository-forks"/>
                <img src="https://img.shields.io/github/watchers/{{ env('LINK_REPOSITORY') }}?style=for-the-badge&color=orange" alt="repository-watchers"/>
            </div>
        </div>
    </div>
</div>