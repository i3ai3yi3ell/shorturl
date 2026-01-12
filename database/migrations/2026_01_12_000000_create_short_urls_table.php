<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortUrlsTable extends Migration
{
    public function up()
    {
        Schema::create('short_urls', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->text('original_url');
            $table->unsignedBigInteger('clicks')->default(0);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();

            $table->index('code');
        });
    }

    public function down()
    {
        Schema::dropIfExists('short_urls');
    }
}
