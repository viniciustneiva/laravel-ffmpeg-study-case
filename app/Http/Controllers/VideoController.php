<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
}
