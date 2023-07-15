<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Benchmark extends Model
{
    use HasFactory;

    protected $table = 'benchmark';

    protected $fillable = [
        'id',
        'video_id',
        'name',
        'size',
        'format',
        'path',
        'url',
        'bitrate',
        'time_spent',
        'quality'
    ];


    public static function getBenchmarksByVideoId($id) {
        return self::join('video', 'video.id', '=', 'benchmark.video_id')
            ->where('benchmark.video_id', $id)
            ->select('benchmark.id', 'benchmark.name', 'benchmark.size',
                'benchmark.format', 'video.format as old_format',
                'benchmark.bitrate', 'benchmark.time_spent', 'benchmark.quality', 'video.size as old_size')
            ->get();
    }
}
