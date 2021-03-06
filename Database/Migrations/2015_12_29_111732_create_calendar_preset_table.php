<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCalendarPresetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar__presets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->required();
            $table->string('location');
            $table->text('description');
            $table->string('className');
            $table->boolean('allDay')->default(1);
            $table->timestamp('start')->required();
            $table->timestamp('end');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('calendar__presets');
    }
}
