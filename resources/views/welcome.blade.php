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
                <div class="col-lg-12">
                    <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data" class="d-flex w-100 justify-content-between align-items-center">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center w-100">
                            <input name="video" type="file" class="form-control form-control-lg" id="inputGroupFile" aria-describedby="inputGroupFileAddon" aria-label="Upload" accept="video/*" style="border-top-right-radius: 0; border-bottom-right-radius: 0;">
                            <button type="submit" class="btn btn-success" style="padding: 12px;border-top-left-radius: 0;border-bottom-left-radius: 0;">Salvar</button>
                        </div>
                        <div class="form-group">

                        </div>
                    </form>
                </div>
            </div>
            @if($videos)
                <div class="row my-5">
                    <div class="col-lg-12 text-center mt-4">
                        <h1>Vídeos</h1>
                    </div>
                    <div class="d-flex flex-column justify-content-between align-items-center">
                        @foreach($videos as $video)

                            <div class="w-100 item-row d-flex justify-content-between align-items-center my-0">
                                <div class="video-name" style="font-size: 13px"><p>{{$video->name}}</p></div>
                                <div class="video-settings d-flex align-items-center justify-content-between">
                                    <div class="w-50">
                                        <a href="/test/{!! $video->id  !!}">
                                            <i class="fa-solid fa-magnifying-glass-chart mx-2 text-white" style="color: white !important;font-size: 20px !important;"></i>
                                        </a>

                                    </div>
                                    <div class="w-50 ">
                                        <a href="/remove/{!!  $video->id !!}">
                                            <i class="fa-solid fa-trash text-white mx-2" style="color: red !important;font-size: 20px !important;"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach

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
