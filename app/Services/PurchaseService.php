<?php

namespace App\Services;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Product\Product;
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
        $purchase->note = $data['note'];
        $purchase->created_by = auth()->user()->id;
        $purchase->party_id = $data['party_id'];
        $purchase->warehouse_id = $data['warehouse_id'];

        // saving wihtout grandtotal and necessary amounts with 0 value
        $purchase->shipping_cost = 0;
        $purchase->discount_type = 'flat';
        $purchase->discount = 0;
        $purchase->invoice_tax_rate = 0;
        $purchase->paid_amount = 0;
        $purchase->due_amount = 0;
        $purchase->returned_amount = 0;
        $purchase->total_amount = 0;

        $purchase->save();

        $invoiceItems = $data['items'];

        // saving all invoice items
        foreach ($invoiceItems as $item) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $purchase->id;
            $invoiceItem->product_id = $item['id'];

            // get the product from database by id
            $product = Product::find($item['id']);

            $invoiceItem->unit_id = $product->purchase_unit_id;
            $invoiceItem->tax_id = $product->tax_id;
            $invoiceItem->tax_type = $product->tax_type;
            $invoiceItem->discount_type = 'flat';
            $invoiceItem->discount = 0;
            $invoiceItem->unit_price = $product->purchase_price;
            $invoiceItem->product_quantity = $item['quantity'];

            $invoiceItem->save();

            if (DB::table('product_stocks')->where(['product_id' => $item['id'], 'warehouse_id' => $data['warehouse_id']])->count() > 0) {

                DB::table('product_stocks')->where(['product_id' => $item['id'], 'warehouse_id' => $data['warehouse_id']])->increment('stock_quantity', $invoiceItem->product_quantity);

            } else {
                DB::table('product_stocks')
                    ->insert(
                        ['product_id' => $item['id'],
                            'warehouse_id' => $data['warehouse_id'],
                            'stock_quantity' => $invoiceItem->product_quantity, ]
                    );
            }
        }

        $calculation = $this->calculateInvoice($data);

        // finally save again the purchase invoice after calculating grand total
        $purchase->shipping_cost = $data['shipping_cost'];
        $purchase->discount_type = 'flat';
        $purchase->discount = $data['discount'] ?? 0;
        $purchase->invoice_tax_rate = $data['invoice_tax_rate'] ?? 0;
        $purchase->paid_amount = 0;
        $purchase->due_amount = 0;
        $purchase->returned_amount = 0;
        $purchase->itemsCostWithTax = $calculation['itemsCostWithTax'];
        $purchase->itemsCostWithoutTax = $calculation['itemsCostWithoutTax'];
        $purchase->total_invoice_tax = $calculation['total_invoice_tax'];
        $purchase->total_amount = $calculation['grand_total'];

        $purchase->save();

        return $purchase;

    }

    public function calculateInvoice($data)
    {
        // initialize all necessary values to calculate the final total
        $itemsCostWithTax = 0;
        $itemsCostWithoutTax = 0;
        $total_invoice_tax = 0;
        $invoice_tax_rate = $data['invoice_tax_rate'];
        $shipping_cost = $data['shipping_cost'];
        $discount = $data['discount'];

        // loop over all added items
        $items = $data['items'];

        foreach ($items as $item) {

            $product = Product::find($item['id']);

            if ($product->tax_type == 'exclusive') {

                $item_total_with_tax =
                $item['quantity'] *
                ($product->purchase_price * ($product->rate / 100) + $product->purchase_price);
                $item_total_without_tax = $item['quantity'] * $product->purchase_price;
                $itemsCostWithTax += $item_total_with_tax;
                $itemsCostWithoutTax += $item_total_without_tax;

            } else {

                $item_total_with_tax = $item['quantity'] * $product->purchase_price;
                $item_total_without_tax =
                $item['quantity'] * ((1 / (100 - $product->rate)) * $product->purchase_price);
                $itemsCostWithTax += $item_total_with_tax;
                $itemsCostWithoutTax += $item_total_without_tax;

            }
        }

        $total_invoice_tax = $itemsCostWithoutTax * ($invoice_tax_rate / 100);

        $grand_total = $shipping_cost + $itemsCostWithTax - $discount + $total_invoice_tax;

        return [
            'grand_total' => $grand_total,
            'total_invoice_tax' => $total_invoice_tax,
            'itemsCostWithTax' => $itemsCostWithTax,
            'itemsCostWithoutTax' => $itemsCostWithoutTax,
        ];

    }
}
