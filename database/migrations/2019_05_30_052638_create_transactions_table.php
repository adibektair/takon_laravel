<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('p_s_id')->nullable()->comment("partner sender id");
            $table->bigInteger('c_s_id')->nullable()->comment("company sender id");
            $table->bigInteger('u_s_id')->nullable()->comment("user sender id");
            $table->bigInteger('c_r_id')->nullable()->comment("company reciever id");
            $table->bigInteger('u_r_id')->nullable()->comment("user reciever id");
            $table->bigInteger('amount');
            $table->bigInteger('service_id');
            $table->bigInteger('price');
            $table->bigInteger('type')->comment("1 - перевод, 2 - проибретение, 3 - использование, 4 - безвозмездно");
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
        Schema::dropIfExists('transactions');
    }
}
