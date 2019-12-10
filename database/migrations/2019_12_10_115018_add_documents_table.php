<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('parent_id')->nullable();
            $table->string('title', 255)->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('filename')->nullable();
            $table->integer('from')->comment('1 - from building 2 - Contracts 3 - from tenants');
            $table->enum('is_active', ['Y', 'N'])->default('Y');
            $table->integer('created_by');
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
        Schema::dropIfExists('documents');
    }
}
