<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeysToComapiesServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies_services', function (Blueprint $table) {
            $table->bigInteger('service_id')->index()->unsigned()->change();
            $table->bigInteger('company_id')->index()->unsigned()->change();
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies_services', function (Blueprint $table) {
            //
        });
    }
}
