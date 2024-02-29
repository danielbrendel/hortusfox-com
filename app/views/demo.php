<div class="page-content margin-fix">
    <h2>Live Demo</h2>

    <p class="is-font-medium">
        <strong>Do you want to try HortusFox in order to get an impression of the app?</strong>
    </p>

    <p><i>No Problem!</i></p>

    <p>You can try our demo by navigating to our demo workspace:</p>

    <p>
        <a class="button is-link" href="{{ env('DEMO_URL') }}" target="_blank">Launch Demo</a>
    </p>

    <div class="demo-list">
        <h3>In order to keep the demo sound and safe for everyone, the following applies:</h3>

        <ul>
            <li>Data will be reset every 24 hours (12:00am CET)</li>
            <li>Uploaded images will also be deleted then</li>
            <li>Admin feature is not available in the demo</li>
            <li>Do not post data or media that is considered illegal or harmful</li>
        </ul>
    </div>

    <div class="demo-list">
        <h3>You can login via the following {{ count($accounts) }} users:</h3>

        <table>
			<thead>
				<tr>
					<td>Login</td>
					<td>Password</td>
				</tr>
			</thead>
			<tbody>
				@foreach ($accounts as $account)
				<tr>
					<td><strong>{{ $account['email'] }}</strong></td>
					<td>{{ $account['password'] }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
    </div>
</div>