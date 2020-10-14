<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductionsSuppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productions_supplies', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('production_id');
            $table->foreign('production_id')
                ->references('id')->on('productions')
                ->onDelete('cascade');

            $table->unsignedBigInteger('supplie_id');
            $table->foreign('supplie_id')
                ->references('id')->on('supplies')
                ->onDelete('cascade');

            $table->decimal('waste', 8, 2);
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
        Schema::dropIfExists('productions_supplies');
    }
}
