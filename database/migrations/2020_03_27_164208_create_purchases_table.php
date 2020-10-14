<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('provider_id');
            $table->foreign('provider_id')
                ->references('id')->on('providers')
                ->onDelete('cascade');

            $table->unsignedBigInteger('purchase_status_id');
            $table->foreign('purchase_status_id')
                ->references('id')->on('purchases_statuses')
                ->onDelete('cascade');

            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('payment_method_id')
                ->references('id')->on('payment_methods')
                ->onDelete('cascade');

            $table->integer('invoice_number');
            $table->double('price');
            $table->date('invoice_date');
            $table->date('payment_date');
            $table->date('status_changed_date');
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
        Schema::dropIfExists('purchases');
    }
}
