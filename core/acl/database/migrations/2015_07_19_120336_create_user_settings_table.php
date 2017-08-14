<?php

use Illuminate\Database\Migrations\Migration;

class CreateUserSettingsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function ($table) {

            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users')->index();
            $table->string('setting')->nullable();
            $table->string('value')->nullable();

            $table->engine = 'InnoDB';
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }

}
