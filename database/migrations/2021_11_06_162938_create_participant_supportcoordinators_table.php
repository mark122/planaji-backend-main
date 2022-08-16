<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantSupportcoordinatorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_supportcoordinators', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planmanager_subscriptions_id');
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('support_coordinator_id');
            $table->timestamps();

            $table->foreign('planmanager_subscriptions_id', 'support_coordinators_reference')
            ->references('id')
            ->on('planmanager_subscriptions')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participant_supportcoordinators');
    }
}
