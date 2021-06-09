<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUrlChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('url_checks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('url_id');
            $table->foreign('url_id')->references('id')->on('urls');
            $table->integer('status_code')->nullable($value = true);
            $table->string('h1')->nullable($value = true);
            $table->text('keywords')->nullable($value = true);
            $table->text('description')->nullable($value = true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('url_checks');
    }
}
