<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DoRetweets extends Migration
{
    public function up()
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->string('retweet')->default('');
        });
    }

    public function down()
    {
        Schema::table('tweets', function (Blueprint $table) {
            $table->dropColumn('retweet');
        });
    }
}
