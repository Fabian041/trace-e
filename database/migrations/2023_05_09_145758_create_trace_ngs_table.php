<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trace_ngs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('ng_id')->unsigned();
            $table->foreign('ng_id')->references('id')->on('trace_antennas');
            $table->string('code');
            $table->timestamp('date');
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
        Schema::dropIfExists('trace_ngs');
    }
};
