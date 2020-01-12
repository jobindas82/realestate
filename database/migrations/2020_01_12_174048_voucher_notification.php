<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

class VoucherNotification extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("CREATE OR REPLACE VIEW voucher_notification AS
        SELECT 'bg-light-green' AS background, 'trending_up' AS icon, CONCAT('Cheque #' , R.cheque_no) AS msg FROM finance R WHERE R.method =2 AND R.type =1 AND R.cheque_status=0 AND R.is_posted='Y' AND DATE(R.cheque_date)=DATE(NOW())
        UNION
        SELECT 'bg-red' AS background, 'trending_down' AS icon, CONCAT('Cheque #' , P.cheque_no) AS msg FROM finance P WHERE P.method =2 AND P.type =2 AND P.cheque_status=0 AND P.is_posted='Y' AND DATE(P.cheque_date)=DATE(NOW())");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("DROP VIEW voucher_notification");
    }
}
