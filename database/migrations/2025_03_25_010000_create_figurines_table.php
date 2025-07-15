<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFigurinesTable extends Migration
{
    public function up()
    {
        Schema::create('figurines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('series');
            $table->string('edition');
            $table->string('rarity');
            $table->string('image_url');
            $table->string('category');
            $table->date('release_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('figurines');
    }
}
