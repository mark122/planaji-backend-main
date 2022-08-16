<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlandetailsStateditemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plandetails_stateditems', function (Blueprint $table) {
            $table->id();
            $table->string('plan_details_id')->nullable();
            $table->string('ndis_pricingguides_id')->nullable();
            $table->string('stated_items_id')->nullable();
            $table->string('stated_item_budget')->nullable();
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
        Schema::dropIfExists('plandetails_stateditems');
    }
}
