<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddKeysToPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
//            $table->bigInteger('service_id')->index()->unsigned()->change();
//            $table->bigInteger('mobile_user_id')->index()->unsigned()->change();
//            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
//            $table->foreign('mobile_user_id')->references('id')->on('mobile_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            //
        });
    }
}
