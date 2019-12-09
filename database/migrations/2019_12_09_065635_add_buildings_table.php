<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBuildingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('buildings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255)->unique();
            $table->enum('ownership', ['O', 'L'])->default('O');
            $table->string('owner_name', 255);
            $table->string('landlord_name', 255);
            $table->date('purchase_date');
            $table->decimal('depreciation_percentage', 3, 2);
            $table->integer('floor_count');
            $table->text('address');
            $table->integer('country_id');
            $table->integer('location_id');
            $table->integer('is_available');
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
        Schema::dropIfExists('buildings');
    }
}
