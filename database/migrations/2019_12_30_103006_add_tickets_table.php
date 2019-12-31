<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('date');
            $table->integer('tenant_id');
            $table->integer('contract_id');
            $table->integer('job_type')->default(1);
            $table->text('details');
            $table->integer('is_active')->default(1);
            $table->integer('priority');
            $table->text('remarks')->nullable();
            $table->decimal('amount', 20, 6)->nullable();
            $table->integer('created_by')->default(0);
            $table->timestamps();
        });

        Schema::table('ticket', function (Blueprint $table) {
            DB::update("ALTER TABLE ticket AUTO_INCREMENT = 1001;");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket');
    }
}
