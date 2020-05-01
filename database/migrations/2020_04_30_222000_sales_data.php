<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SalesData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_data', function(Blueprint $table) {
            $table->id();
            $table->bigInteger('order_id');
            $table->dateTime('purchase_date', 0);
            $table->string('po_number');
            $table->integer('cust_order');
            $table->string('currency');
            $table->string('cust_fname');
            $table->string('cust_city');
            $table->string('cust_country');
            $table->string('cust_province');
            $table->double('tax');
            $table->double('shipping');
            $table->double('grand_total');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sales_data');
    }
}
