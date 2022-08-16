<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNdisPricingguidesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ndis_pricingguides', function (Blueprint $table) {
            $table->id();
            $table->string('support_item_number')->nullable();
            $table->string('support_item_name')->nullable();
            $table->string('registration_group_number')->nullable();
            $table->string('registration_group_name')->nullable();
            $table->string('support_category_number')->nullable();
            $table->string('support_categories_id')->nullable();
            $table->string('unit')->nullable();
            $table->string('quote')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->string('ACT')->nullable();
            $table->string('NSW')->nullable();
            $table->string('NT')->nullable();
            $table->string('QLD')->nullable();
            $table->string('SA')->nullable();
            $table->string('TAS')->nullable();
            $table->string('VIC')->nullable();
            $table->string('WA')->nullable();
            $table->string('remote')->nullable();
            $table->string('very_remote')->nullable();
            $table->string('non_face_to_face_support_provision')->nullable();
            $table->string('provider_travel')->nullable();
            $table->string('short_notice_cancellations')->nullable();
            $table->string('NDIA_requested_reports')->nullable();
            $table->string('irregular_sil_supports')->nullable();
            $table->string('type')->nullable();
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
        Schema::dropIfExists('ndis_pricingguides');
    }
}
