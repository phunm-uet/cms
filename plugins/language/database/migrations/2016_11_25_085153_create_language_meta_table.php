<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLanguageMetaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('language_meta', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_id')->unsigned()->index();
            $table->text('code')->nullable();
            $table->string('reference', 120);
            $table->string('origin', 255);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('language_meta');
    }
}
