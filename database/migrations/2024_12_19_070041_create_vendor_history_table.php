<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->nullable();
            $table->decimal('purchase_price', 10, 2)->nullable();
            $table->decimal('paid_price', 10, 2)->nullable();
            $table->decimal('payable', 10, 2)->nullable();
            $table->dateTime('pay_date')->nullable();
            $table->string('status')->nullable();
            $table->string('discount')->nullable();
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
        Schema::dropIfExists('vendor_history');
    }
}
