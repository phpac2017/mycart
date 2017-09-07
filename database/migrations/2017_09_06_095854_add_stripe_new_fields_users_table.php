<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStripeNewFieldsUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('stripe_customer_id')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('users', function($table) {
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('stripe_customer_id');
        });
    }
}
