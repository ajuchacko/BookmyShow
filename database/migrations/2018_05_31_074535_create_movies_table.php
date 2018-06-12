<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMoviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->string('image')->nullable();
            $table->string('trailer')->nullable();
            $table->string('language');
            $table->string('genre');
            $table->integer('ticket_price');
            $table->date('release_date');
            $table->unsignedBigInteger('duration');
            $table->mediumText('summary');
            $table->datetime('published_at')->nullable();
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
        Schema::dropIfExists('movies');
    }
}
