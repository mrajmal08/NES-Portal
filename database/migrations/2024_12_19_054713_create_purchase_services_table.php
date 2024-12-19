<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseServicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_services', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('purchase_id')->nullable();
            $table->bigInteger('service_id')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('qty')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('purchase_services');
    }
}
