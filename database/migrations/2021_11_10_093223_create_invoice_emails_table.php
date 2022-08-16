<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_emails', function (Blueprint $table) {
            $table->id();
            $table->integer('uuid')->nullable();
            $table->unsignedBigInteger('plan_manager_id');
            $table->string('subject')->nullable();
            $table->string('body')->nullable();
            $table->string('attachment')->nullable();
            $table->string('attachment_url')->nullable();
            $table->string('attachment2')->nullable();
            $table->string('attachment2_url')->nullable();
            $table->string('received_date')->nullable();
            $table->string('from_email')->nullable();
            $table->timestamps();

            $table->foreign('plan_manager_id')
            ->references('id')
            ->on('plan_managers')
            ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_emails');
    }
}
