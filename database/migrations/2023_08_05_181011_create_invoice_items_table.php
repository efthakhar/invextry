<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoice_items', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('product_id');

            $table->unsignedDouble('unit_price', 20, 4);
            $table->unsignedDouble('product_quantity', 20, 4);
            $table->unsignedBigInteger('unit_id')->nullable();
            $table->unsignedBigInteger('tax_rate')->nullable();
            $table->string('tax_type', 20);
            $table->string('discount_type')->nullable(); // flat/percentage
            $table->unsignedDouble('discount', 20, 4)->nullable();
            //$table->unsignedDouble('item_total', 20, 4)->nullable();

            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('product_id')->references('id')->on('products')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')
                ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};
