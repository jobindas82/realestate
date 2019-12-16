<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('tenant_id');
            $table->integer('building_id');
            $table->integer('flat_id');
            $table->date('generated_date');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('util_payment');
            $table->integer('is_active')->default(1);
            $table->integer('is_renewed')->nullable();
            $table->integer('previous_contract')->nullable();
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
        Schema::dropIfExists('contracts');
    }
}
