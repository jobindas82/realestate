<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddingTbLedgerFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('entries', function (Blueprint $table) {
            $table->integer('lv5')->after('visible')->nullable()->index();
            $table->integer('lv4')->after('visible')->nullable()->index();
            $table->integer('lv3')->after('visible')->nullable()->index();
            $table->integer('lv2')->after('visible')->nullable()->index();
            $table->integer('lv1')->after('visible')->nullable()->index();
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
