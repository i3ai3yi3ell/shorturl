<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class IncreaseCodeColumnSize extends Migration
{
    public function up()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->string('code', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('short_urls', function (Blueprint $table) {
            $table->string('code', 10)->change();
        });
    }
}
