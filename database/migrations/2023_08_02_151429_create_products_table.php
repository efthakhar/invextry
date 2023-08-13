<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {

            $table->id();
            $table->string('code')->unique();

            $table->string('name')->unique();
            $table->string('slug')->unique();

            $table->string('product_type', 20);
            $table->string('barcode_symbology', 60);

            $table->unsignedDouble('stock_quantity', 20, 4)->nullable();
            $table->unsignedDouble('stock_alert_quantity', 20, 4)->nullable();

            $table->unsignedDouble('purchase_price', 20, 4);
            $table->unsignedDouble('sale_price', 20, 4);

            $table->unsignedBigInteger('parent_id')->nullable();

            $table->unsignedBigInteger('brand_id')->nullable();

            $table->unsignedBigInteger('category_id');

            $table->unsignedBigInteger('unit_id');
            $table->unsignedBigInteger('purchase_unit_id');
            $table->unsignedBigInteger('sale_unit_id');

            $table->unsignedBigInteger('tax_id')->nullable();
            $table->string('tax_type', 20);

            $table->text('description')->nullable();

            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('brands')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('unit_id')->references('id')->on('units')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('category_id')->references('id')->on('product_categories')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('tax_id')->references('id')->on('taxes')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
