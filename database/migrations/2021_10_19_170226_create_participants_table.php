<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->string('firstname')->nullable();
            $table->string('lastname')->nullable();
            $table->string('ndis_number')->nullable();
            $table->string('aboutme')->nullable();
            $table->string('email')->nullable();
            $table->string('password')->nullable();
            $table->string('remember_token')->nullable();
            $table->string('generated_password')->nullable();
            $table->string('changed_password')->nullable();
            $table->string('password_token')->nullable();
            $table->string('dateofbirth')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('state')->nullable();
            $table->string('postcode')->nullable();
            $table->string('homenumber')->nullable();
            $table->string('phonenumber')->nullable();
            $table->string('ndis_plan_start_date')->nullable();
            $table->string('ndis_plan_end_date')->nullable();
            $table->string('ndis_plan_review_date')->nullable();
            $table->string('short_term_goals')->nullable();
            $table->string('long_term_goals')->nullable();
            $table->string('status')->nullable();
            $table->string('app_access_enabled')->nullable();
            $table->string('email_verified_at')->nullable();
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
        Schema::dropIfExists('participants');
    }
}
