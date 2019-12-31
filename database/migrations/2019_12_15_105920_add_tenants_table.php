<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTenantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 255);
            $table->string('emirates_id')->nullable();
            $table->string('land_phone', 255)->nullable();
            $table->string('mobile', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('passport_number')->nullable();
            $table->string('trn_number')->nullable();
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
        Schema::dropIfExists('tenants');
    }
}
