<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
		
		<meta name="viewport" content="with=device-width, initial-scale=1.0"/>
		
		<style>
			html, body {
				width: 100%;
				max-width: 800px;
				background-color: rgb(31, 31, 31);
				color: rgb(150, 150, 150);
				margin: 0 auto;
				overflow-x: hidden;
				font-family: BlinkMacSystemFont, -apple-system, "Segoe UI", "Roboto", "Oxygen", "Ubuntu", "Cantarell", "Fira Sans", "Droid Sans", "Helvetica Neue", "Helvetica", "Arial", sans-serif;
			}

			.headline {
				width: 100%;
				padding: 10px;
				color: rgb(230, 230, 230);
				background-color: rgb(55, 55, 55);
			}

			.headline img {
				width: 32px;
				height: 32px;
			}

			.headline a {
				position: relative;
				top: -5px;
				font-size: 2.0em;
				text-decoration: none;
				color: rgb(111, 205, 132);
				font-family: Gabriola;
				font-weight: bold;
			}

			.headline a:hover {
				text-decoration: none;
				color: rgb(111, 205, 132);
			}

			.content {
				width: 100%;
				padding: 10px;
				color: rgb(200, 200, 200);
				background-color: rgb(31, 31, 31);
			}

			.content p {
				width: 93%;
			}

			.content a {
				color: rgb(105, 159, 202);
				text-decoration: none;
			}

			.content a:hover {
				color: rgb(105, 159, 202);
				text-decoration: underline;
			}

			.footer {
				width: 100%;
				padding: 10px;
				color: rgb(150, 150, 150);
				background-color: rgb(55, 55, 55);
				font-size: 0.76em;
			}

			.footer p {
				width: 93%;
			}

			.footer a {
				color: rgb(150, 135, 73);
				text-decoration: none;
			}

			.footer a:hover {
				color: rgb(150, 135, 73);
				text-decoration: underline;
			}
		</style>

        <title>Welcome to the HortusFox Newsletter</title>
    </head>

    <body>
		<div class="headline">
			<span><img src="{{ asset('img/logo.png') }}"/>&nbsp;&nbsp;<a href="{{ url('/') }}">hortusfox.com</a></span>

			<h1>Welcome to the HortusFox Newsletter</h1>
		</div>
		
		<div class="content">
			<p>
				You have successfully subscribed to our newsletter. We are glad that you want
				to stay updated on the development process of HortusFox. We will occassionally
				inform you about upcoming events, development milestones and any other news!
			</p>

			<p>
				Just one more thing: Please verify your e-mail address, so we can be sure that it is you that want
				to recieve the newsletter. Please click the following link in order to confirm your e-mail address.
			</p>

			<p>
				<a href="{{ url('/newsletter/confirm?confirm_token=' . $confirmation) }}">Confirm E-Mail address</a>
			</p>
			
			<p>
				Of course we greatly appreciate your support. And we welcome you to contribute as well.
				Therefore you can create issues or pull requests on <a href="{{ env('LINK_GITHUB') }}">GitHub</a>. 
                But we are also happy if you follow us on social media or join our discord.
			</p>
			
			<p>
				HortusFox is free and opensourced software licensed under the MIT license. If you like our product
				then we really appreciate it if you would <a href="https://ko-fi.com/C0C7V2ESD">buy me a coffee</a>. 
                This helps greatly in keeping on with developing the software as well as providing the required infrastructure.
			</p>
		</div>
		
		<div class="footer">
			<p>&copy; {{ date('Y') }} by Daniel Brendel</p>
			
			<p>
				HortusFox is open-sourced software licensed under the MIT license.
				You are receiving this message because you have subscribed to our newsletter.
				If you no longer want to receive messages from us, click <a href="{{ url('/newsletter/unsubscribe/' . $token) }}">here</a>
                to unsubscribe. You can resubscribe again at any time.
			</p>
			
			<p>
				@if (env('LINK_GITHUB'))
					<a href="{{ env('LINK_GITHUB') }}" target="_blank">
						GitHub
					</a>
                    &nbsp;|&nbsp;
				@endif

				@if (env('LINK_DISCORD'))
					<a href="{{ env('LINK_DISCORD') }}" target="_blank">
						Discord
					</a>
                    &nbsp;|&nbsp;
				@endif

				@if (env('LINK_REDDIT'))
					<a href="{{ env('LINK_REDDIT') }}" target="_blank">
						Reddit
					</a>
                    &nbsp;|&nbsp;
				@endif

				@if (env('LINK_YOUTUBE'))
					<a href="{{ env('LINK_YOUTUBE') }}" target="_blank">
						YouTube
					</a>
                    &nbsp;|&nbsp;
				@endif

                <a href="mailto:{{ env('APP_CONTACT') }}" target="_blank">
                    Contact
                </a>
			</p>
		</div>
    </body>
</html>