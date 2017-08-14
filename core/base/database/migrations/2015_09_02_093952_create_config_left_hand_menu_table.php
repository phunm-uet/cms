<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigLeftHandMenuTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('config_menu_left_hand')) {
            Schema::dropIfExists('config_menu_left_hand');
        }

        Schema::create('config_menu_left_hand', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('parent_id')->nullable();
            $table->integer('lft')->nullable();
            $table->integer('rgt')->nullable();
            $table->integer('depth')->nullable();
            $table->string('kind', 20);
            $table->string('default_name', 255);
            $table->string('name', 255);
            $table->integer('feature_id')->nullable();
            $table->string('icon', 50)->nullable();

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
        Schema::dropIfExists('config_menu_left_hand');
    }

}
