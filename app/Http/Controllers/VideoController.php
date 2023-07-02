<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller {


    public function uploadVideo(Request $request) {
        $this->validate($request, [
            'video' => 'required|file|mimetypes:video/mp4',
        ]);

        $fileName = $request->video->getClientOriginalName();
        $filePath = 'videos/' . $fileName;

        $isFileUploaded = Storage::disk('public')->put($filePath, file_get_contents($request->video));

        $url = Storage::disk('public')->url($filePath);

        if ($isFileUploaded) {
            $video = new Video();
            $video->name = $fileName;

            $arrSplittedDots = explode('.', $fileName);
            $video->format = $arrSplittedDots[count($arrSplittedDots) - 1];
            $video->path = $filePath;
            $video->size = $request->video->getSize();
            $video->save();

            return back()
                ->with('success','Video has been successfully uploaded.');
        }

        return back()
            ->with('error','Unexpected error occured');

    }
}
