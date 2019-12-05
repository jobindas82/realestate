<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConstructionType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('construction_type', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->integer('tax_code')->default(0);
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->integer('created_by')->default(0);
            $table->timestamps();
            $table->unique('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('construction_type');
    }
}
