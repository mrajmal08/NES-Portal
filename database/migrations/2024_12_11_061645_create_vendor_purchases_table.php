<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorPurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_purchases', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id');
            $table->bigInteger('product_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->dateTime('date')->nullable();
            $table->text('invoice_photo')->nullable();
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
        Schema::dropIfExists('vendor_purchases');
    }
}
