<div class="page-content margin-fix">
    <h2>Community</h2>

    <p class="is-font-medium">
        Curious about the plants of our community?
        Then feel free to view shared photos of community workspaces.
    </p>

    @if (isset($_GET['tag']))
    <div class="community-filter">
        <div class="community-filter-tag">{{ $_GET['tag'] }}</div>

        <div class="community-filter-clear">
            <a class="is-default-link" href="{{ url('/community') }}">Clear</a>
        </div>
    </div>
    @endif

    <div class="community" id="community-content"></div>
</div>