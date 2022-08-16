<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('participant_id');
            $table->string('plan_contract')->nullable();
            $table->string('status')->nullable();
            $table->date('plan_date_start')->nullable();
            $table->date('plan_date_end')->nullable();
            $table->date('plan_date_review')->nullable();
            $table->string('total_funding')->default('0');
            $table->string('capacity_budget')->default('0');
            $table->string('core_budget')->default('0');
            $table->string('capital_budget')->default('0');

            $table->string('capacity_remaining')->default('0');
            $table->string('core_remaining')->default('0');
            $table->string('capital_remaining')->default('0');

            $table->string('total_allocated')->default('0');
            $table->string('total_remaining')->default('0');
            $table->string('total_delivered')->default('0');
            $table->string('total_claimed')->default('0');
            $table->string('total_unclaimed')->default('0');
            
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
        Schema::dropIfExists('plans');
    }
}
