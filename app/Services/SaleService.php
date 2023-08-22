<?php

namespace App\Services;

use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use App\Models\Product\Product;

class SaleService
{
    protected $stockService;

    protected $paymentService;

    public function __construct(StockService $stockService, PaymentService $paymentService)
    {
        $this->stockService = $stockService;
        $this->paymentService = $paymentService;
    }

    public function create($data)
    {
        $sale = new Invoice();
        $sale->invoice_ref = 'SALE_'.time().rand(1396, 2350);
        $sale->type = 'sale';
        $sale->invoice_status = $data['invoice_status'];
        $sale->payment_status = 'unpaid'; // initially mark it as unpaid
        $sale->invoice_date = $data['invoice_date'];
        $sale->note = $data['note'];
        $sale->created_by = auth()->user()->id;
        $sale->party_id = $data['party_id'];
        $sale->warehouse_id = $data['warehouse_id'];

        // saving wihtout grandtotal and necessary amounts with 0 value
        $sale->shipping_cost = 0;
        $sale->discount_type = 'flat';
        $sale->discount = 0;
        $sale->invoice_tax_rate = 0;
        $sale->paid_amount = 0;
        $sale->due_amount = 0;
        $sale->returned_amount = 0;
        $sale->total_amount = 0;

        $sale->save();
        // dd($sale->id);
        $invoiceItems = $data['items'];

        // saving all invoice items
        foreach ($invoiceItems as $item) {
            $invoiceItem = new InvoiceItem();
            $invoiceItem->invoice_id = $sale->id;
            $invoiceItem->product_id = $item['id'];

            // get the product from database by id
            $product = Product::find($item['id']);

            $invoiceItem->unit_id = $product->sale_unit_id;
            $invoiceItem->tax_rate = $product->tax ? $product->tax->rate : 0;
            $invoiceItem->tax_type = $product->tax_type;
            $invoiceItem->discount_type = 'flat';
            $invoiceItem->discount = 0;
            $invoiceItem->unit_price = $product->sale_price;
            $invoiceItem->product_quantity = $item['quantity'];

            $invoiceItem->save();

            $this->stockService->removeStock($data['warehouse_id'], $item['id'], $item['quantity']);
        }
        $calculation = $this->calculateInvoice($data);

        // finally save again the sale invoice after calculating grand total
        $sale->shipping_cost = $data['shipping_cost'];
        $sale->discount_type = 'flat';
        $sale->discount = $data['discount'] ?? 0;
        $sale->invoice_tax_rate = $data['invoice_tax_rate'] ?? 0;
        $sale->returned_amount = 0;
        $sale->itemsCostWithTax = $calculation['itemsCostWithTax'];
        $sale->itemsCostWithoutTax = $calculation['itemsCostWithoutTax'];
        $sale->total_invoice_tax = $calculation['total_invoice_tax'];
        $sale->total_amount = $calculation['grand_total'];
        $sale->due_amount = $calculation['grand_total'];

        $sale->save();

        $this->paymentService->createPayment([
            'invoice_id' => $sale->id,
            'account_id' => $data['account_id'],
            'payment_method' => $data['payment_method'] ?? 'cash',
            'amount' => $data['paid_amount'] ?? 0,
            'date' => $data['invoice_date'],
            'note' => $data['payment_note'] ?? '',
        ]);

        return $sale;
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

            $product_tax_rate = $product->tax ? $product->tax->rate : 0;

            if ('exclusive' == $product->tax_type) {
                $item_total_with_tax = $item['quantity'] *
                ($product->sale_price * ($product_tax_rate / 100) + $product->sale_price);
                $item_total_without_tax = $item['quantity'] * $product->sale_price;
                $itemsCostWithTax += $item_total_with_tax;
                $itemsCostWithoutTax += $item_total_without_tax;
            } else {
                $item_total_with_tax = $item['quantity'] * $product->sale_price;
                $item_total_without_tax = $item['quantity'] * ((1 / (100 - $product_tax_rate)) * $product->sale_price);
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
