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
                    <form action="{{ url('/upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="d-flex flex-column justify-content-between align-items-center">
                        <input name="video" type="file" class="form-control form-control-lg" id="inputGroupFile" aria-describedby="inputGroupFileAddon" aria-label="Upload" accept="video/*">
                    </div>
                        <div class="col-md-6 form-group">
                            <button type="submit" class="btn btn-success">Save</button>
                        </div>
                    </form>
                </div>
            </div>
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
