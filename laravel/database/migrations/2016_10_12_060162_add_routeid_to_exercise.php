<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRouteidToExercise extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
            Schema::table('exercises', function(Blueprint $table) {
               $table->integer('route_id')->unsigned()->nullable();
            });
            Schema::table('exercises', function(Blueprint $table) {
                $table->foreign('route_id')->references('id')->on('routes');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
       Schema::table('exercises', function($table) {
        $table->dropColumn('route_id');
       });
    }
}
