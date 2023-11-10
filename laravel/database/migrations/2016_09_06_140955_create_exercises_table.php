<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExercisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exercises', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('reps')->nullable();
            $table->integer('sets')->nullable();
            $table->integer('total')->nullable();
            $table->string('weight')->nullable();
            $table->string('duration')->nullable();
            $table->string('distance')->nullable();
            $table->integer('type_id')->unsigned();
            $table->integer('workout_id')->unsigned()->default(1);
            $table->integer('user_id')->unsigned()->default(1);
        });
        Schema::table('exercises', function(Blueprint $table) {
            $table->foreign('type_id')->references('id')->on('exercisetypes');
            $table->foreign('workout_id')->references('id')->on('workouts');
            $table->foreign('user_id')->references('id')->on('workouts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::drop('exercises');
    }
}
