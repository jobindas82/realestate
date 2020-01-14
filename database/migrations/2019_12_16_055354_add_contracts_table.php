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
            $table->integer('tenant_id')->index();
            $table->integer('building_id')->index();
            $table->integer('flat_id')->index();
            $table->date('generated_date');
            $table->date('from_date');
            $table->date('to_date');
            $table->integer('util_payment');
            $table->integer('is_active')->default(1)->index();
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
