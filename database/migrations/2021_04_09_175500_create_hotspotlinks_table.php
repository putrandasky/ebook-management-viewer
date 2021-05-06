<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHotspotlinksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hotspotlinks', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->float('area_height', 9, 2);
            $table->float('area_width', 9, 2);
            $table->float('area_edge_top', 9, 2);
            $table->float('area_edge_left', 9, 2);
            $table->float('area_center_top', 9, 2);
            $table->float('area_center_left', 9, 2);
            $table->foreignId('page_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('hotspotlinks');
    }
}
