<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderManagementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_managements', function (Blueprint $table) {
            $table->id();
            $table->string('car_picture')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->bigInteger('mechanic_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->string('vehicle_no')->nullable();
            $table->string('client_name')->nullable();
            $table->string('client_phone')->nullable();
            $table->string('status')->nullable();
            $table->string('total_price')->nullable();
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('order_managements');
    }
}
