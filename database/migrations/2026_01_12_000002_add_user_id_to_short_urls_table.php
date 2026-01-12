<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToShortUrlsTable extends Migration
{
    public function up()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->string('title')->nullable()->after('original_url');

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index('user_id');
        });
    }

    public function down()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['user_id', 'title']);
        });
    }
}
