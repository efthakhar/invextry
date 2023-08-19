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
        Schema::create('invoices', function (Blueprint $table) {

            $table->id();
            $table->string('invoice_ref')->unique();
            $table->string('related_invoice_ref')->nullable();
            $table->string('type', 50);
            // purchase/purchase_return/sales/sales_return
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('party_id');
            $table->unsignedBigInteger('warehouse_id');

            $table->string('discount_type')->nullable(); // flat/percentage
            $table->unsignedDouble('discount', 20, 4)->nullable();
            $table->unsignedDouble('invoice_tax_rate', 10, 4)->nullable();
            $table->unsignedDouble('total_invoice_tax', 20, 4)->nullable();

            $table->unsignedDouble('itemsCostWithTax', 20, 4)->nullable();
            $table->unsignedDouble('itemsCostWithoutTax', 20, 4)->nullable();
            //$table->unsignedBigInteger('tax_id')->nullable();

            $table->unsignedDouble('shipping_cost', 20, 4);
            $table->unsignedDouble('paid_amount', 20, 4);
            $table->unsignedDouble('returned_amount', 20, 4);
            $table->unsignedDouble('due_amount', 20, 4);
            $table->unsignedDouble('total_amount', 20, 4);
            // due_amount = total_amount - paid_amount - returned_amount

            $table->string('invoice_status'); // received/pending/ordered
            $table->string('payment_status'); // partial/paid/unpaid
            $table->string('shipping_status')->nullable(); // delivered

            $table->text('note')->nullable();
            $table->date('invoice_date');
            $table->date('payment_deadline')->nullable();

            $table->timestamps();

            $table->foreign('party_id')->references('id')->on('parties')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('created_by')->references('id')->on('users')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('warehouse_id')->references('id')->on('warehouses')
                ->cascadeOnUpdate()->restrictOnDelete();
            // $table->foreign('tax_id')->references('id')->on('taxes')
            //     ->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
