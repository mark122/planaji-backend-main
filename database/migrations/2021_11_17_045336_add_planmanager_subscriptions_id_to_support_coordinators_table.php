<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPlanmanagerSubscriptionsIdToSupportCoordinatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_coordinators', function (Blueprint $table) {
            $table->unsignedBigInteger('planmanager_subscriptions_id')->nullable()->after('id');

            $table->foreign('planmanager_subscriptions_id')
            ->references('id')
            ->on('planmanager_subscriptions')
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
        Schema::table('support_coordinators', function (Blueprint $table) {
            //
        });
    }
}
