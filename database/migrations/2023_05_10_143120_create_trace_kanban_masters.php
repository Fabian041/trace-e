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
        Schema::create('trace_kanban_masters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('model_code')->unique();
            $table->string('back_number');
            $table->string('part_number');
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
        Schema::dropIfExists('trace_kanban_masters');
    }
};
