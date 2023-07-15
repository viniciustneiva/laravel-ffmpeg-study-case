<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('benchmark', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('video_id');
            $table->string('name');
            $table->string('size');
            $table->string('format');
            $table->string('bitrate');
            $table->string('path');
            $table->string('url');
            $table->double('time_spent');
            $table->string('quality')->nullable();
            $table->foreign('video_id')->references('id')->on('video')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('benchmark');
    }
};
