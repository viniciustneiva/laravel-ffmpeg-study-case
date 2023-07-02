<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @vite(['resources/scss/app.scss', 'resources/js/app.js'])
        <title>Laravel FFMPEG</title>
    </head>
    <body>
    <main>
        <section class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1>Estudo de caso Laravel FFMPEG</h1>
                </div>
            </div>
        </section>
    </main>
    </body>
</html>
