<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFlatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flats', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->integer('building_id');
            $table->integer('floor')->nullable();
            $table->string('premise_id', 255)->nullable();
            $table->string('square_feet', 255)->nullable();
            $table->double('minimum_value')->nullable();
            $table->integer('construction_type_id')->nullable();
            $table->integer('flat_type_id')->nullable();
            $table->string('plot_no', 255)->nullable();
            $table->string('owner_name', 255)->nullable();
            $table->string('landlord_name', 255)->nullable();
            $table->integer('is_available')->default(1);
            $table->integer('created_by')->default(0);
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
        Schema::dropIfExists('flats');
    }
}
