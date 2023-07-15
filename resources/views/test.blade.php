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
                            <input type="hidden" id="videoId" value="{{$video->id}}">
                            <h1>Opções:</h1>
                            <div class="d-flex justify-content-between align-items-center w-100 my-4">

                                <div class="d-flex align-items-center w-50">
                                    <label for="format ">Formato:</label>
                                        <select class="form-control form-select w-50 mx-2" name="format" id="format">
                                            <option value="">Selecione</option>
                                            <option value="ogg">OGG</option>
                                            <option value="wmv">WMV</option>
                                            <option value="x264">X264</option>
                                            <option value="webm">WEBM</option>
                                        </select>
                                </div>
                                <div class="d-flex align-items-center w-25">
                                    <label for="format ">Qualidade:</label>
                                    <select class="form-control form-select w-50 mx-2" name="quality" id="quality">
                                        <option value="">Selecione</option>
                                        <option value="360">360p</option>
                                        <option value="480">480p</option>
                                        <option value="720">720p</option>
                                        <option value="1080">1080p</option>
                                    </select>
                                    <div class="float-right">
                                    </div>
                                </div>

                                <div class="d-flex align-items-center w-25">
                                    <label for="format ">Bitrate:</label>
                                    <select class="form-control form-select w-100 mx-2" name="bitrate" id="bitrate">
                                        <option value="">Selecione</option>
                                        <option value="250">250</option>
                                        <option value="500">500</option>
                                        <option value="750">750</option>
                                        <option value="1000">1000</option>
                                    </select>
                                    <div class="float-right">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-center align-items-center py-3">
                            <div class="btn btn-primary ffmpeg-button w-25" id="compress">Testar</div>
                        </div>
                        <div class="w-100 item-row py-4">
                            @if($video->benchmarks)
                                <div class="d-flex flex-column justify-content-between align-items-center">
                                    <h1>Benchmarks:</h1>
                                    @foreach($video->benchmarks as $benchmark)
                                        <div class="w-100 item-row d-flex flex-column justify-content-between align-items-start my-0">
                                            <div class="video-name" style="font-size: 23px">
                                               <p>Nome: {{ $benchmark->name }}</p>
                                                <p>Peso: {{ $benchmark->size }} | {{ \App\Models\Video::formatBytes($benchmark->size) }}</p>
                                                <p>{{$benchmark->format ? "Formato: $benchmark->format" : "" }}</p>
                                                <p>{{$benchmark->quality ? "Qualidade: $benchmark->quality".'p' : "" }}</p>
                                                <p>{{$benchmark->bitrate ? "Bitrate: $benchmark->bitrate" : "" }}</p>
                                                <p>Tempo gasto: {{$benchmark->time_spent}}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                                <a href="{{url('/')}}">                               <button class="btn btn-danger w-25 mt-2" style="float: left">Voltar</button></a>

                        </div>

                    </div>

                @endif


            </section>
    </main>
</body>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>

<script>
    window.onload = function() {


        document.querySelector('.ffmpeg-button').addEventListener('click', function () {
                event.preventDefault();
                let format = document.querySelector('#format').value;
                let quality = document.querySelector('#quality').value;
                let bitrate = document.querySelector('#bitrate').value;
                if(format == null && quality == null && bitrate == null)
                    return

                $.post('{!! url('/convert') !!}', {
                    'id': {!! $video->id !!},
                    'format': format ?? null,
                    'quality': quality ?? null,
                    'bitrate': bitrate ?? null,
                }).then(function (res) {
                    console.log(res)
                })

        });
    };
</script>
</html>
