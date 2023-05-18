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
        Schema::create('trace_kanbans', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('master_id')->unsigned()->nullable();
            $table->foreign('master_id')->references('id')->on('trace_kanban_masters');
            $table->string('serial_number');
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
        Schema::dropIfExists('trace_kanbans');
    }
};
