<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePassRangesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pass_ranges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pass_id')->index();
            $table->foreign('pass_id')->references('id')->on('passes')->onDelete('cascade');
            $table->json('days');
            $table->json('dates');
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
        Schema::dropIfExists('pass_ranges');
    }
}
