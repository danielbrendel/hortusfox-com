<div class="page-content">
	<a name="info"></a>

	@include('flashmsg.php')

	<h2>Welcome to HortusFox</h2>

	<p>
		HortusFox is a free and open-sourced self-hosted plant manager system that you can use to manage, keep track and
		journal your home plants. It is designed in a collaborative way, so you can manage your home plants with your
		partner, friends, family & more! By shipping the software as a self-hosted product, you are always master of your
		own personal data and thus are in full control over them. HortusFox is open-sourced MIT licensed software, so you
		can contribute to the software or make your own version of it.
	</p>

	<p>
		HortusFox provides you with important features such as managing your home locations and assiging added plants to them.
		You can set various details about your plants such as specific attributes, preview photo, tags, notes, gallery photos
		and many more! The system also provides you with the opportunity to manage your inventory that is needed to care for
		your beloved plants. The tasks feature helps you to keep track of what you have to do in order to care about your 
		plants. Also there is a collaborative group chat for your users to exchange important hints about what someone has
		done or what needs to be done. 
	</p>

	@if (env('APP_ENABLENEWSLETTER'))
		<div class="newsletter">
			<strong>Want to stay up-to-date? Subscribe to our newsletter!</strong>

			<form method="POST" action="{{ url('/newsletter/subscribe') }}" id="frmNewsletter">
				@csrf 

				<div class="field has-addons newsletter-prompt">
					<div class="control newsletter-prompt">
						<input class="input" type="email" name="email" placeholder="Enter your E-Mail address...">
					</div>
					<div class="control">
						<a class="button is-info" href="javascript:void(0);" onclick="document.querySelector('#frmNewsletter').submit();">Subscribe</a>
					</div>
				</div>
			</form>
		</div>
	@endif

	<hr/>

	<h2>Lightweight But Effective Features</h2>

	<p class="is-font-medium">
		HortusFox provides you with simple but effective features
	</p>

	<div class="page-features">
		<div class="page-features-block">
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Dashboard</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Unlimited locations</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Unlimited plants</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Plant attributes</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Thumbnails & Gallery</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Tasks system</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Inventory management</div>
		</div>

		<div class="page-features-block page-features-block-fix">
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Group Chat</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Profile management</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Tags system</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Search feature</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Plants history</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Log history</div>
			<div class="page-feature-item"><i class="fas fa-star"></i>&nbsp;Admin section</div>
		</div>
	</div>

	<hr/>

	<h2>Ready for both mobile and desktop</h2>

	<p class="is-font-medium">
		Get an impression from screenshots
	</p>

	<div class="page-screenshots">
		<div class="page-screenshot-item screenshot-desktop">
			<img src="{{ asset('img/screenshots/screenshot-desktop.png') }}" alt="screenshot"/>
		</div>

		<div class="page-screenshot-item screenshot-mobile">
			<img src="{{ asset('img/screenshots/Screenshot_20231023_123009_HortusFox.jpg') }}" alt="screenshot"/>
		</div>

		<div class="page-screenshot-item screenshot-mobile">
			<img src="{{ asset('img/screenshots/Screenshot_20231023_123202_HortusFox.jpg') }}" alt="screenshot"/>
		</div>

		<div class="page-screenshot-item screenshot-mobile">
			<img src="{{ asset('img/screenshots/Screenshot_20231023_123229_HortusFox.jpg') }}" alt="screenshot"/>
		</div>
	</div>

	<p>
		<a class="button is-link button-stretched" href="{{ url('/screenshots') }}">View more</a>
	</p>

	<hr/>

	<h2>Download HortusFox</h2>

	<a name="downloads"></a>

	<p class="is-font-medium">
		Download the latest version here.
	</p>

	<div class="page-downloads">
		<table>
			<thead>
				<tr>
					<td>Type</td>
					<td>Version</td>
					<td class="is-centered">Download</td>
				</tr>
			</thead>
			<tbody>
				@foreach ($downloads as $download)
				<tr>
					<td><strong>{{ $download->get('name') }}</strong></td>
					<td>{{ $download->get('version') }}</td>
					<td class="is-centered"><a class="button is-link" href="{{ $download->get('resource') }}"><i class="fas fa-download"></i>&nbsp;Download</a></td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>

	@if (env('DEMO_ENABLE'))
		<p class="is-font-medium">
			Do you want to try the app before installing?
		</p>

		<p>
			<a class="button is-success" href="{{ url('/demo') }}">Try Live Demo</a>
		</p>
	@endif
</div>
