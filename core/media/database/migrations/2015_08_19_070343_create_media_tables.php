<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMediaTables extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_folders', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->integer('parent')->default(0);
            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('media_storage', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('name', 255);
            $table->integer('folder_id')->default(0);
            $table->string('mime_type', 120);
            $table->string('type', 120);
            $table->integer('size');
            $table->string('public_url', 255);

            $table->timestamps();
            $table->engine = 'InnoDB';
        });

        Schema::create('media_shares', function (Blueprint $table) {

            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->integer('shared_by');
            $table->integer('share_id')->default(0);
            $table->string('share_type');
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
        Schema::dropIfExists('media_folders');
        Schema::dropIfExists('media_storage');
        Schema::dropIfExists('media_shares');
    }

}
