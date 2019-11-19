<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWalletPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wallet_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('service_id')->unsigned()->index();
            $table->bigInteger('mobile_user_id')->unsigned()->index();
	        $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
	        $table->foreign('mobile_user_id')->references('id')->on('mobile_users')->onDelete('cascade');
	        $table->bigInteger('amount');
            $table->bigInteger('price');
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
        Schema::dropIfExists('wallet_payments');
    }
}
