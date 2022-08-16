<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantServiceprovidersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participant_serviceproviders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('planmanager_subscriptions_id')->nullable();
            $table->unsignedBigInteger('plan_id');
            $table->unsignedBigInteger('participant_id');
            $table->unsignedBigInteger('service_provider_id');
            $table->timestamps();

            $table->foreign('planmanager_subscriptions_id', 'my_new_reference')
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
        Schema::dropIfExists('participant_serviceproviders');
    }
}
