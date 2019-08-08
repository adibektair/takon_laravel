<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeysToComapiesUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies_users', function (Blueprint $table) {
            $table->bigInteger('mobile_user_id')->index()->unsigned()->change();
            $table->bigInteger('company_id')->index()->unsigned()->change();
            $table->foreign('mobile_user_id')->references('id')->on('mobile_users')->onDelete('cascade');
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
        Schema::table('comapies_users', function (Blueprint $table) {
            //
        });
    }
}
