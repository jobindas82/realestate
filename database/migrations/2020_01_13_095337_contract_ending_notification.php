<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class ContractEndingNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW contract_notification AS SELECT C.id AS contract_id, C.to_date AS end_date FROM contracts C WHERE C.is_active=1 AND YEARWEEK(C.to_date, 1) = YEARWEEK(CURDATE(), 1)");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW contract_notification");
    }
}
