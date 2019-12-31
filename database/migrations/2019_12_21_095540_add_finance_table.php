<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFinanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->integer('type');
            $table->bigInteger('number');
            $table->integer('method')->nullable();
            $table->integer('contract_id')->nullable();
            $table->integer('building_id')->nullable();
            $table->integer('flat_id')->nullable();
            $table->integer('tenant_id')->nullable();
            $table->date('cheque_date')->nullable();
            $table->string('cheque_no')->nullable();
            $table->integer('cheque_status')->nullable()->default(0); // 1 => cleared 2 => returned
            $table->integer('is_posted')->nullable()->default(1); //Posted;
            $table->integer('is_cancelled')->nullable()->default(0); //Not Cancelled;
            $table->integer('is_audited')->nullable()->default(0); //Not Audited;
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
        Schema::dropIfExists('finance');
    }
}
