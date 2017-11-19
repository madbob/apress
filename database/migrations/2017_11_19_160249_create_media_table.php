<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaTable extends Migration
{
    public function up()
    {
        Schema::create('media', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('tweet_id')->unsigned();
            $table->string('path');
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
    }
}
