<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id');
            $table->string('plan_supportreference_id')->nullable();
            $table->string('category_budget')->nullable();
            $table->string('has_stated_item')->nullable();
            $table->string('details')->nullable();
            $table->string('support_payment')->nullable();
            $table->string('has_quarantine_fund')->nullable();
            $table->string('participant_serviceproviders_id')->nullable();
            $table->string('participant_supportcoordinators_id')->nullable();
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
        Schema::dropIfExists('plan_details');
    }
}
