<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTweetsTable extends Migration
{
    public function up()
    {
        Schema::create('tweets', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('account_id')->unsigned();
            $table->string('content');
            $table->dateTimeTz('schedule');
            $table->boolean('sent')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('tweets');
    }
}
