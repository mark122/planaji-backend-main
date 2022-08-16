<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddS3KeyToPlanDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('plan_documents', function (Blueprint $table) {
            $table->string('s3_key')->after('s3_filepath');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('plan_documents', function (Blueprint $table) {
            if (Schema::hasColumn('plan_documents', 's3_key')) {
                $table->dropColumn('s3_key');
            }
        });
    }
}
