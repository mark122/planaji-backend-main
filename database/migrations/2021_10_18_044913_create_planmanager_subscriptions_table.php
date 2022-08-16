<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreatePlanmanagerSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('planmanager_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('subscription_id');
            $table->unsignedBigInteger('plan_manager_id');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->date('renewal_date')->nullable();
            $table->string('custom_logo')->nullable();
            $table->string('custom_url')->nullable();
            $table->string('dashboard_side_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('header_color')->nullable();
            $table->string('fontheader_color')->nullable();
            $table->string('subscription_no')->nullable();
            $table->timestamps();
            
            $table->foreign('subscription_id')
            ->references('id')
            ->on('subscriptions')
            ->onUpdate('cascade');
            
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
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('planmanager_subscriptions');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
