<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->integer('contract_id')->after('ledger_id')->nullable()->index();
            $table->integer('building_id')->after('ledger_id')->nullable()->index();
            $table->integer('flat_id')->after('ledger_id')->nullable()->index();
            $table->integer('tenant_id')->after('ledger_id')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('entries', function (Blueprint $table) {
            //
        });
    }
}
