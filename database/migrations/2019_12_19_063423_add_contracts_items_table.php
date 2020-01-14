<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractsItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contract_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('contract_id')->index();
            $table->integer('ledger_id')->index();
            $table->decimal('amount', 15, 6);
            $table->integer('tax_id')->index();
            $table->double('tax_percentage');
            $table->decimal('tax_amount', 15, 6);
            $table->decimal('net_amount', 15, 6);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contract_items');
    }
}
