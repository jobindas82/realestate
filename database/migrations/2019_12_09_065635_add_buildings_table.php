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
            $table->string('owner_name', 255)->nullable();
            $table->string('landlord_name', 255)->nullable();
            $table->date('purchase_date')->nullable();
            $table->double('depreciation_percentage')->nullable();
            $table->integer('floor_count')->nullable();
            $table->text('address')->nullable();
            $table->integer('country_id')->nullable()->index();
            $table->integer('location_id')->nullable()->index();
            $table->integer('is_available')->index();
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
