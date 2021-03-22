<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassSlotPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pass_slot', function (Blueprint $table) {
            $table->unsignedBigInteger('pass_id')->index();
            $table->foreign('pass_id')->references('id')->on('pass')->onDelete('cascade');
            $table->unsignedBigInteger('slot_id')->index();
            $table->foreign('slot_id')->references('id')->on('slot')->onDelete('cascade');
            $table->primary(['pass_id', 'slot_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pass_slot');
    }
}
