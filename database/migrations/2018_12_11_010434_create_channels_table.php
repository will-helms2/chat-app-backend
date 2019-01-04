<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChannelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('channels', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('team_id')->unsigned();
            $table->string('name');
            $table->string('description');
            $table->boolean('is_private')->default(false);
            $table->boolean('is_dm')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::table('channels', function(Blueprint $table){
           $table->foreign('team_id')->references('id')->on('teams');
       });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('channels');
    }
}
