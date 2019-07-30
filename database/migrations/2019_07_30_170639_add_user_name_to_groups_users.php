<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserNameToGroupsUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('groups_users', function (Blueprint $table) {
            $table->string('username')->nullable();
            $table->bigInteger('group_id')->index()->unsigned()->change();
            $table->foreign('group_id')->references('id')->on('groups')->onDelete('cascade');
            $table->bigInteger('mobile_user_id')->index()->unsigned()->change();
            $table->foreign('mobile_user_id')->references('id')->on('mobile_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups_users', function (Blueprint $table) {
            //
        });
    }
}
