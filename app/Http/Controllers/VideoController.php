<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoController extends Controller {

    public function index() {
        $videos = Video::orderBy('created_at', 'desc')->get();
        return view('welcome',[
            'videos' => $videos
        ]);
    }


    public function uploadVideo(Request $request) {
        $this->validate($request, [
            'video' => 'required|file|mimetypes:video/*',
        ]);

        $fileName = $request->video->getClientOriginalName();
        $extension = pathinfo($fileName, PATHINFO_EXTENSION);

        $randomName = 'videos/' .uniqid() . '.' . $extension;

        $isFileUploaded = Storage::disk('public')->put($randomName, file_get_contents($request->video));


        $url = Storage::disk('public')->url($randomName);

        if ($isFileUploaded) {
            $video = new Video();
            $video->name = $fileName;
            $video->format = $extension;
            $video->path = $randomName;
            $video->url = $url;
            $video->size = $request->video->getSize();
            $video->save();

            return back()
                ->with('success','O Video foi enviado com sucesso.');
        }

        return back()
            ->with('error','Um erro inexperado ocorreu.');

    }

    public function test($id) {
        $video = Video::select('video.*')->where('id', $id)->first();

        return view('test', [
            'video' => $video,
            'formattedBytes' => $this->formatBytes($video->size)
        ]);
    }

    public function delete($id) {

        $video = Video::select('video.*')->where('id', $id)->first();
        $filePath = 'videos/' . $video->path;

        if($video->delete()) {
            Storage::disk('public')->delete($filePath);
            return back()
                ->with('success','O Vídeo foi removido com sucesso.');
        }else{
            return back()
                ->with('error','Não foi possível deletar o vídeo de id '.$video->id);
        }
    }


    function formatBytes($bytes, $decimals = 2) {
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);

        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
    }


    public function convert(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:video,id|numeric',
            'format' => [
                'required',
                Rule::in(['wmv', 'webm', 'ogg', 'x264'])
            ],
        ]);

        $video = Video::select('video.*')->where('id', $request->get('id'))->first();
        $extension = pathinfo($video->name, PATHINFO_EXTENSION);
        $fileName = pathinfo($video->name, PATHINFO_FILENAME);

        $randomName = 'videos/' .$fileName . '_formatted';
        $start_time = microtime(true);
        $ffmpeg = FFMpeg::fromFilesystem(Storage::disk('public'))->open($video->path)->export()
            ->toDisk(Storage::disk('public'));

        $format = $request->get('format');
        if($format !== $video->format) {
            switch ($format) {
                case "ogg":
                    $randomName = 'videos/' .$fileName . '_formatted' . '.ogg';
                    $ffmpeg->inFormat(new \FFMpeg\Format\Video\Ogg)
                        ->save($randomName);
                    break;
                case "x264":
                    $randomName = 'videos/' .$fileName . '_formatted' .'.mkv';
                    $ffmpeg->inFormat(new \FFMpeg\Format\Video\X264)
                        ->save($randomName);
                    break;
                case "webm":
                    $randomName = 'videos/' .$fileName . '_formatted' .'.webm';
                    $ffmpeg->inFormat(new \FFMpeg\Format\Video\WebM)
                        ->save($randomName);
                    break;
                case "wmv":
                    $randomName = 'videos/' .$fileName . '_formatted' .'.wmv';
                    $ffmpeg->inFormat(new \FFMpeg\Format\Video\WMV)
                        ->save($randomName);
                    break;
                default:
                    return back()
                        ->with('error','Não definir uma conversão para o formato: '. $format);
            }
            $end_time = microtime(true);
            $execution_time = ($end_time - $start_time);
            return "[" .$format."] Execution time of script = ".$execution_time." sec";
        }
        return back()
            ->with('error','Não é possível formatar o vídeo para o mesmo formato');

    }
}
