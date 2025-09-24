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
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('title_id');
            $table->foreign('title_id')->references('id')->on('titles')->onDelete('cascade');
            $table->integer('season');
            $table->integer('episode_number');
            $table->string('episode_name');
            $table->text('episode_overview')->nullable();
            $table->time('episode_duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
