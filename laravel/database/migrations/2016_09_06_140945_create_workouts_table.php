<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workouts', function(Blueprint  $table)
        {
            $table->increments('id');
            $table->dateTime('created_at');
            $table->string('name');
            $table->string('expenditure');
            $table->string('time');
            $table->string('fittime')->nullable();
            $table->string('fattime')->nullable();
            $table->string('heart_max')->nullable();
            $table->string('heart_avg')->nullable();
            $table->integer('user_id')->unsigned()->default(1);
        });
          Schema::table('workouts', function(Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('workouts');
    }
}
