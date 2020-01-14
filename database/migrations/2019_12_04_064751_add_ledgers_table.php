<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->default(0)->index();
            $table->string('name', 255);
            $table->integer('level')->default(0);
            $table->enum('type', ['A', 'L', 'I', 'E'])->default('A');
            $table->string('class', 10)->nullable();
            $table->enum('is_parent', ['Y', 'N'])->default('N')->index();
            $table->enum('is_generated', ['Y', 'N'])->default('N');
            $table->enum('is_expandable', ['Y', 'N'])->default('Y');
            $table->enum('is_active', ['Y', 'N'])->default('Y');
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
        Schema::table('ledgers', function (Blueprint $table) {
            //
        });
    }
}
