<!doctype html>
<html lang="{{ getLocale() }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-with, initial-scale=1.0">
		
		<meta name="og:title" property="og:title" content="{{ (isset($_meta_title)) ? env('APP_NAME') . ' - ' . $_meta_title : env('APP_NAME') }}">
        <meta name="og:description" property="og:description" content="{{ (isset($_meta_description)) ? $_meta_description : env('APP_DESCRIPTION') }}">
        <meta name="og:url" property="og:url" content="{{ (isset($_meta_url)) ? $_meta_url : url('/') }}">
        <meta name="og:image" property="og:image" content="{{ asset('img/screenshots/screenshot-desktop.png') }}">
		
        <title>{{ (isset($_meta_title)) ? env('APP_NAME') . ' - ' . $_meta_title : env('APP_NAME') }}</title>

		<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}"/>
        <link rel="stylesheet" type="text/css" href="{{ asset('css/bulma.css') }}"/>

		<style>
			.action-go-back {
				position: fixed;
				z-index: 5;
				top: 10px;
				left: 10px;
			}

			.action-go-back-inner {
				padding-top: 10px;
				padding-left: 15px;
				padding-right: 15px;
				padding-bottom: 10px;
				background-color: rgb(80, 80, 80);
				border-radius: 50%;
			}

			.action-go-back-inner a {
				color: rgb(200, 200, 200);
			}

			.action-go-back-inner a:hover {
				color: rgb(230, 230, 230);
			}
		</style>

		<script src="{{ asset('js/fontawesome.js') }}"></script>
		<script src="{{ asset('js/app.js') }}"></script>
		<script src="{{ asset('game/game.js') }}"></script>
	</head>
	
	<body>
		<div class="action-go-back">
			<div class="action-go-back-inner">
				<a href="{{ url('/') }}">
					<i class="fas fa-arrow-left fa-2x"></i>
				</a>
			</div>
		</div>

        <script>
            gameconfig.physics.arcade.debug = {{ env('APP_DEBUG') ? 'true' : 'false' }};

	        const game = new Phaser.Game(gameconfig);
        </script>
	</body>
</html>