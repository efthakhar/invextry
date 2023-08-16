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
        Schema::create('parties', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();

            $table->string('name');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();

            $table->string('company_name')->nullable();
            $table->string('tax_number')->unique()->nullable();

            $table->string('country', 50)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('postal_code', 50)->nullable();
            $table->text('address')->nullable();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();

            $table->string('status', 20);
            $table->boolean('isCustomer')->nullable();
            $table->boolean('isSupplier')->nullable();

            $table->unsignedDouble('purchase_due', 20, 4)->nullable();
            $table->unsignedDouble('purchase_return_due', 20, 4)->nullable();
            $table->unsignedDouble('sale_due', 20, 4)->nullable();
            $table->unsignedDouble('sale_return_due', 20, 4)->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
