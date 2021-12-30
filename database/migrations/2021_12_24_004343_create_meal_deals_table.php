<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealDealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_deals', function (Blueprint $table) {
            $table->id();
            $table->string('folio')->unique();
            $table->integer('amount');
            $table->decimal('price')->nullable()->default(0.0);
            $table->decimal('new_price')->nullable()->default(0.0);
            $table->timestamp('available')->nullable();
            $table->time('available_time')->nullable();
            $table->enum('status', ['available', 'reserved', 'delivered'])->nullable()->default('available');
            $table->unsignedBigInteger('meal_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_deals');
    }
}
