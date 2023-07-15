<?php

namespace App\Models;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Format\Video\Ogg;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\X264;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

/**
 * @method static orderBy(string $string, string $string1)
 */
class Video extends Model
{
    use HasFactory;

    protected $table = 'video';

    protected $fillable = [
        'id',
        'name',
        'size',
        'format',
        'path',
        'url'
    ];

    public function benchmarks() {
        return $this->hasMany(Benchmark::class, 'video_id', 'id');
    }

    public static function formatBytes($bytes, $decimals = 2): string {
        $size = ['B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
    }

    public static function getVideoWithBenchmarkWithId($id) {
        return self::with('benchmarks')->select('video.*')->where('id', $id)->first();
    }

    public static function ffmpegResolver($video, $format, $quality, $bitrate): bool|array|\Illuminate\Http\RedirectResponse
    {
        $ffmpeg = FFMpeg::fromFilesystem(Storage::disk('public'))->open($video->path)->export()
            ->toDisk(Storage::disk('public'));
        $randomName = uniqid() . '_' . $video->id . '_formatted';

        $start_time = microtime(true);

        if($format == null && $quality == null && $bitrate == null) {
            return false;
        }

        if ($format != null && $format !== $video->format) {
            switch ($format) {
                case "ogg":
                    $newFormat = '.ogg';
                    $ffmpeg->inFormat(self::getFormatWithBitrate(Ogg::class, $bitrate));
                    break;
                case "x264":
                    $newFormat = '.mkv';
                    $ffmpeg->inFormat(self::getFormatWithBitrate(X264::class, $bitrate));
                    break;
                case "webm":
                    $newFormat = '.webm';
                    $ffmpeg->inFormat(self::getFormatWithBitrate(WebM::class, $bitrate));
                    break;
                case "wmv":
                    $newFormat ='.wmv';
                    $ffmpeg->inFormat(self::getFormatWithBitrate(WMV::class, $bitrate));
                    break;
                default:
                    return false;
            }
        }else{
            $arr = explode('.', $video->path);
            $newFormat = $arr[count($arr) -1];
        }

        if($quality != null) {
            switch ($quality) {
                case "360":
                    $randomName = $randomName . '_360';
                    $ffmpeg->resize(480, 360);
                    break;
                case "480":
                    $randomName = $randomName . '_480';
                    $ffmpeg->resize(640,480);
                    break;
                case "720":
                    $randomName = $randomName . '_720';
                    $ffmpeg->resize(1280, 720);
                    break;
                case "1080":
                    $randomName = $randomName . '_1080';
                    $ffmpeg->resize(1920,1080);
                    break;
                default:
                    return false;
            }
        }
        $newName = $randomName .($bitrate != null ? "_". $bitrate. $newFormat : $newFormat) ;
        $newPath = 'videos/' . $newName;

        $ffmpeg->save($newPath);
        $end_time = microtime(true);
        $execution_time = ($end_time - $start_time);

        return [
            'executionTime' => $execution_time,
            'newPath' => $newPath,
            'newName' => $newName,
            'newFormat' => $newFormat,
            'newBitrate' => $bitrate,
            'newQuality' => $quality
        ];
    }


    public static function getFormatWithBitrate($format, $bitrate = null) {
        return match ($bitrate) {
            '250' => (new $format)->setKiloBitrate(250),
            '500' => (new $format)->setKiloBitRate(500),
            '750' => (new $format)->setKiloBitrate(750),
            '1000' => (new $format)->setKiloBitRate(1000),
            default => (new $format),
        };
    }

    public static function formatarTempo($segundos) {
        $horas = floor($segundos / 3600);
        $minutos = floor(($segundos % 3600) / 60);
        $segundos = round($segundos % 60, 2); // Arredonda os segundos para duas casas decimais

        return sprintf('%02d:%02d:%05.2f', $horas, $minutos, $segundos);
    }
}
