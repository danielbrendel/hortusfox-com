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

		<script src="{{ asset('js/app.js') }}"></script>
		<script src="{{ asset('game/game.js') }}"></script>
	</head>
	
	<body>
        <script>
            gameconfig.physics.arcade.debug = {{ env('APP_DEBUG') ? 'true' : 'false' }};

	        const game = new Phaser.Game(gameconfig);
        </script>
	</body>
</html>