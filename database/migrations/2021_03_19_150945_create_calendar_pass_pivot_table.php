<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarPassPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendar_pass', function (Blueprint $table) {
            $table->unsignedBigInteger('calendar_id')->index();
            $table->foreign('calendar_id')->references('id')->on('calendar')->onDelete('cascade');
            $table->unsignedBigInteger('pass_id')->index();
            $table->foreign('pass_id')->references('id')->on('pass')->onDelete('cascade');
            $table->primary(['calendar_id', 'pass_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendar_pass');
    }
}
