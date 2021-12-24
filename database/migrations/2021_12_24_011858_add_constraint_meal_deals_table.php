<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddConstraintMealDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meal_deals', function (Blueprint $table) {
            $table->foreign('meal_id')
                ->on('meals')
                ->references('id')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->on('users')
                ->references('id')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meal_deals', function (Blueprint $table) {
            $table->dropForeign('meal_deals_meal_id_foreign');
            $table->dropForeign('meal_deals_user_id_foreign');
        });
    }
}
