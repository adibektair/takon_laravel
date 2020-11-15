<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('conversions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('first_service_id')->unsigned()->index();
            $table->foreign('first_service_id')->references('id')->on('services')->onDelete('cascade');
            $table->bigInteger('second_service_id')->unsigned()->index();
            $table->foreign('second_service_id')->references('id')->on('services')->onDelete('cascade');
            $table->double('coefficient');
            $table->boolean('is_available');
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
        Schema::dropIfExists('conversions');
    }
}
