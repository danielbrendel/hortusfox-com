<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <title>{{ $photo->get('title') }}</title>

        <meta name="og:title" property="og:title" content="Personal workspace photo">
        <meta name="og:description" property="og:description" content="{{ $photo->get('title') }}">
        <meta name="og:url" property="og:url" content="{{ url('/p/' . $photo->get('slug')) }}">
        <meta name="og:image" property="og:image" content="{{ asset('img/photos/' . $photo->get('thumb')) }}">

        <style>
            html, body {
                width: 100%;
                height: 100%;
                margin: 0 auto;
                background-color: rgb(50, 50, 50);
            }

            .photo-outter {
                position: relative;
                width: 100%;
                height: 100%;
                text-align: center;
            }

            .photo-inner {
                position: relative;
                top: 50%;
                transform: translateY(-50%);
            }

            .photo-title {
                position: absolute;
                top: -20px;
                left: 20px;
                color: rgb(200, 200, 200);
                font-family: Gabriola, Verdana, Arial;
            }

            .photo-image {
                position: relative;
                width: 100%;
                height: 100%;
            }

            .photo-image img:hover {
                box-shadow: 0 0 20px 0 rgba(105, 165, 85, 0.95);
            }

            @media screen and (max-width: 768px) {
                .photo-image img {
                    width: 100%;
                }
            }
        </style>
    </head>
    <body>
        <div class="photo-outter">
            <div class="photo-title">
                <h1>{{ $photo->get('title') }}</h1>
            </div>

            <div class="photo-inner">
                <div class="photo-image">
                    <a href="{{ asset('img/photos/' . $photo->get('full')) }}">
                        <img src="{{ asset('img/photos/' . $photo->get('thumb')) }}" alt="{{ $photo->get('title') }}"/>
                    </a>
                </div>
            </div>
        </div>
    </body>
</html>