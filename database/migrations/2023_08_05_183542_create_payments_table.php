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
        Schema::create('payments', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('account_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->string('invoice_type', 50)->nullable(); // sales/purchase/sales_return/purchase_return
            //$table->unsignedBigInteger('payment_method_id');
            $table->string('payment_method', 50);
            $table->unsignedDouble('amount', 20, 4);
            $table->date('date');
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('party_id')->references('id')->on('parties')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('account_id')->references('id')->on('accounts')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('invoice_id')->references('id')->on('invoices')
                ->cascadeOnUpdate()->restrictOnDelete();
            // $table->foreign('payment_method_id')->references('id')->on('payment_methods')
            //     ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
