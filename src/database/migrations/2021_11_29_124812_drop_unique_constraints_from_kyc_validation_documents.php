<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropUniqueConstraintsFromKycValidationDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::disableForeignKeyConstraints();
        Schema::table('kyc_validation_documents', function (Blueprint $table) {
            $table->dropUnique('kyc_validation_documents_kyc_validation_id_unique');
        });
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kyc_validation_documents', function (Blueprint $table) {
            $table->unique('kyc_validation_id');
        });
    }
}
