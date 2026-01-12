<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlClicksTable extends Migration
{
    public function up()
    {
        Schema::create('url_clicks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('short_url_id');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('referer')->nullable();
            $table->string('country', 2)->nullable();
            $table->string('city')->nullable();
            $table->string('device')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->timestamp('clicked_at');

            $table->foreign('short_url_id')->references('id')->on('short_urls')->onDelete('cascade');
            $table->index(['short_url_id', 'clicked_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('url_clicks');
    }
}
