<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTaxcodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tax_code', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code', 255);
            $table->decimal('percentage', 5, 2);
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->integer('created_by')->default(0);
            $table->timestamps();
            $table->unique('code');
            $table->unique('percentage');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tax_code');
    }
}
