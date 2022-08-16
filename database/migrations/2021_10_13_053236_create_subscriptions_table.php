<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->integer('no_of_users')->unsigned()->nullable();
            $table->integer('no_of_participants')->unsigned()->nullable();
            $table->integer('no_of_service_providers')->unsigned()->nullable();
            $table->integer('no_of_support_coordinators')->unsigned()->nullable();
            $table->string('custom_url')->nullable();
            $table->string('custom_email')->nullable();
            $table->string('price')->nullable();
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
        Schema::dropIfExists('subscriptions');
    }
}
