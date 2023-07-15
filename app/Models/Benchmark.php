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
}
