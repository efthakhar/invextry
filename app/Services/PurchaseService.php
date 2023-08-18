<?php

namespace App\Services;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use Illuminate\Support\Facades\DB;

class PurchaseService
{
    public function __construct()
    {
    }

    public function create($data)
    {
        $purchase = new Invoice();
        $purchase->invoice_ref = 'PUR_'.time().rand(46, 2350);
        $purchase->type = 'purchase';
        $purchase->invoice_status = $data['invoice_status'];
        $purchase->payment_status = $data['payment_status'];
        $purchase->invoice_date = $data['invoice_date'];
        $purchase->note = $data['invoice_note'];
        $purchase->created_by = auth()->user()->id;
        $purchase->party_id = $data['supplier_id'];
        $purchase->warehouse_id = $data['warehouse_id'];
        $purchase->discount_type = 'flat';
        $purchase->discount = $data['discount'] ?? 0;
        $purchase->invoice_tax_rate = $data['invoice_tax_rate'] ?? 0;
        $purchase->total_amount = $data['total_amount'];
        $purchase->paid_amount = 0;
        $purchase->due_amount = 0;
        $purchase->returned_amount = 0;
        $purchase->save();

        $products = $data['products'];

        foreach ($products as $product) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $purchase->id;
            $invoiceItem->product_id = $product['id'];
            $invoiceItem->unit_id = $product['purchase_unit_id'];
            $invoiceItem->tax_id = $product['tax_id'];
            $invoiceItem->tax_type = $product['tax_type'];
            $invoiceItem->discount_type = 'flat';
            $invoiceItem->discount = 0;
            $invoiceItem->unit_price = $product['purchase_price'];
            $invoiceItem->product_quantity = $product['quantity'];
            $invoiceItem->save();

            if (DB::table('product_stocks')->where(['product_id' => $product['id'], 'warehouse_id' => $data['warehouse_id']])->count() > 0) {

                DB::table('product_stocks')->where(['product_id' => $product['id'], 'warehouse_id' => $data['warehouse_id']])->increment('stock_quantity', $invoiceItem->product_quantity);

            } else {
                DB::table('product_stocks')
                    ->insert(
                        ['product_id' => $product['id'],
                            'warehouse_id' => $data['warehouse_id'],
                            'stock_quantity' => $invoiceItem->product_quantity, ]
                    );
            }
        }

        return $purchase;

    }
}
