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
        Schema::create('movies', function (Blueprint $table) {
            $table->id();
            $table->integer('movie_id')->unique();
            $table->string('title');
            $table->string('poster_path')->nullable();
            $table->text('description')->nullable();
            $table->date('release_date')->nullable();
            $table->float('rating')->nullable();
            $table->string('origin_country')->nullable();
            $table->boolean('watched')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movies');
    }
};
