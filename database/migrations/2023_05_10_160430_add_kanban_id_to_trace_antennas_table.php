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
        Schema::table('trace_antennas', function (Blueprint $table) {
            $table->bigInteger('kanban_id')->unsigned()->after('id');
            $table->foreign('kanban_id')->references('id')->on('trace_kanbans');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trace_antennas', function (Blueprint $table) {
            //
        });
    }
};
