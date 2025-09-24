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
        Schema::create('titles', function (Blueprint $table) {
            $table->id();
            $table->integer('id_title_tmdb');
            $table->boolean('is_movie')->default(true);
            $table->string('name');
            $table->text('tagline')->nullable();
            $table->string('poster_path')->nullable();
            $table->text('overview')->nullable();
            $table->date('release_date')->nullable();
            $table->float('rating')->nullable();
            $table->string('origin_country')->nullable();
            $table->integer('duration')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('titles');
    }
};
