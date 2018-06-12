<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCastMoviePivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cast_movie', function (Blueprint $table) {
            $table->unsignedInteger('movie_id')->index();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');

            $table->unsignedInteger('cast_id')->index();
            $table->foreign('cast_id')->references('id')->on('casts')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cast_movie');
    }
}
