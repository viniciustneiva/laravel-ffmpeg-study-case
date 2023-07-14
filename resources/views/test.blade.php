<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
    <title>Teste | Laravel FFMPEG</title>
</head>
<body>
    <main>
        @if ($message = Session::get('success'))
            <div id="alert-success-card" class="alert alert-success alert-block">
                <button type="button" class="close btn btn-close" data-dismiss="alert"></button>
                <strong>{{ $message }}</strong>
            </div>
        @endif

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <button type="button" class="close btn btn-close" data-dismiss="alert"></button>
                    <strong>Whoops!</strong> There were some problems with your input.
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <section class="container">
                <div class="row">
                    <div class="col-lg-12 text-center mt-4">
                        <h1>Estudo de caso Laravel FFMPEG</h1>
                    </div>
                </div>
                <div class="row my-5">
                </div>
                @if($video)
                    <div class="row my-5">
                        <div class="col-lg-12 text-center mt-4">
                            <h1>Vídeo FFMPEG Benchmark</h1>
                        </div>
                        <div class="w-100 item-row d-flex justify-content-between align-items-center my-0">
                            <div class="video-name" style="font-size: 23px"><p>{{$video->name}}</p></div>
                            <div class="video-name" style="font-size: 23px"><p>Peso: {{$video->size}} / {{$formattedBytes}}</p></div>
                            <div class="video-name" style="font-size: 23px"><p>Formato: {{$video->format}}</p></div>
                        </div>
                        <div class="w-100 item-row py-4">
                            <div class="d-flex flex-column">
                                <h1>Converter:</h1>
                                <div class="d-flex justify-content-between align-items-center">
                                    <select class="form-control form-select w-25" name="format" id="format">
                                        <option value="">Selecione</option>
                                        <option value="mp4">MP4</option>
                                        <option value="avi">AVI</option>
                                        <option value="webm">WEBM</option>
                                    </select>
                                    <div class="float-right">
                                        <div class="btn btn-primary">Converter</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-100 item-row py-4">
                            <h1>Comprimir:</h1>
                            <div class="d-flex justify-content-between align-items-center">
                                <select class="form-control form-select w-25" name="format" id="format">
                                    <option value="">Selecione</option>
                                    <option value="360">360p</option>
                                    <option value="480">480p</option>
                                    <option value="720">720p</option>
                                </select>
                                <div class="float-right">
                                    <div class="btn btn-primary">Converter</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </section>

    </main>
</body>
<script>
    window.onload = function() {
        document.querySelector('.close').addEventListener('click', function (){
            document.querySelectorAll('.alert').forEach(item => {
                item.style.display = 'none';
            })
        })
    };
</script>
</html>
