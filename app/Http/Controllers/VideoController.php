<?php

namespace App\Http\Controllers;

use App\Models\Benchmark;
use App\Models\Video;

use FFMpeg\Format\Video\Ogg;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\X264;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use League\Csv\Writer;
use Illuminate\Support\Collection;

class VideoController extends Controller {

    public function index(): View {
        $videos = Video::orderBy('created_at', 'desc')->get();
        return view('welcome',[
            'videos' => $videos
        ]);
    }


    public function uploadVideo(Request $request): RedirectResponse {
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

    public function test($id): View {
        $video = Video::getVideoWithBenchmarkWithId($id);

        return view('test', [
            'video' => $video,
            'formattedBytes' => Video::formatBytes($video->size)
        ]);
    }

    public function delete($id): RedirectResponse {

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





    public function convert(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:video,id|numeric',
            'format' => [
                'nullable',
                Rule::in(['wmv', 'webm', 'ogg', 'x264'])
            ],
            'quality' => [
                'nullable',
                'numeric',
                Rule::in(['360', '480', '720', '1080'])
            ],
            'bitrate' => [
                'nullable',
                'numeric',
                Rule::in(['250','500', '750', '1000'])
            ]
        ]);

        $video = Video::select('video.*')->where('id', $request->get('id'))->first();

        $format = $request->get('format');
        $quality = $request->get('quality');
        $bitrate = $request->get('bitrate');
        $ffmpegResponse = Video::ffmpegResolver($video, $format, $quality, $bitrate);

        if($ffmpegResponse) {
            $size = Storage::disk('public')->size($ffmpegResponse['newPath']);
            Benchmark::create([
                'video_id' => $video->id,
                'name' => $ffmpegResponse['newName'],
                'path' => $ffmpegResponse['newPath'],
                'size' => $size,
                'format' => $ffmpegResponse['newFormat'],
                'quality' => $ffmpegResponse['newQuality'],
                'bitrate' => $ffmpegResponse['newBitrate'],
                'url' => Storage::disk('public')->url($ffmpegResponse['newPath']),
                'time_spent' => $ffmpegResponse['executionTime']
            ]);

            $returnFormat = $ffmpegResponse['newFormat'] ? "[".$ffmpegResponse['newFormat']."] " : '';
            $returnCompress = $ffmpegResponse['newQuality'] ? "[".$ffmpegResponse['newQuality']."] " : '';
            $returnBitrate = $ffmpegResponse['newBitrate'] ? "[".$ffmpegResponse['newBitrate']."] " : '';
            return $returnFormat. $returnCompress . $returnBitrate . "Execution time of script = ".$ffmpegResponse['executionTime']." sec";
        }
        return back()->with('error','Não é possível converter ou comprimir o vídeo');
    }

    public function export($id) {
        $video = Video::where('id', $id)->select('video.name')->first();
        $benchmarks = Benchmark::getBenchmarksByVideoId($id);

        $csvData = [];
        $csvData[] = ['Benchmark ID', 'Benchmark Name', 'New Size', 'New Format',  'Old Format', 'Bitrate', 'Time Spent', 'Quality', 'Old Size'];

        foreach ($benchmarks as $b) {
            $csvData[] = [
                $b->id, $b->name, $b->size, $b->format, $b->old_format, $b->bitrate, $b->time_spent, $b->quality, $b->old_size
            ];
        }

        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        $filename = $video->name . '.csv';
        $filePath = 'benchmarks/' . $filename;

        Storage::disk('public')->put($filePath, $csvContent);

        return response()->download(storage_path('app/public/'.$filePath));
    }
}
