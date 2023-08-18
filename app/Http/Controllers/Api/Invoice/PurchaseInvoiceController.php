<?php

namespace App\Http\Controllers\Api\Invoice;

use App\Http\Controllers\Controller;
use App\Models\Invoice\Invoice;
use App\Models\Invoice\InvoiceItem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    public function createPurchase(Request $request)
    {

        try {

            DB::beginTransaction();

            $purchase = new Invoice();
            $purchase->invoice_ref = 'PUR_'.time().rand(46, 2350);
            $purchase->type = 'purchase';
            $purchase->invoice_status = $request->invoice_status;
            $purchase->payment_status = $request->payment_status;
            $purchase->invoice_date = $request->invoice_date;
            $purchase->note = $request->invoice_note;
            $purchase->created_by = auth()->user()->id;
            $purchase->party_id = $request->supplier_id;
            $purchase->warehouse_id = $request->warehouse_id;
            $purchase->discount_type = 'flat';
            $purchase->discount = $request->discount ?? 0;
            $purchase->invoice_tax_rate = $request->invoice_tax_rate ?? 0;
            $purchase->total_amount = $request->total_amount;
            $purchase->paid_amount = 0;
            $purchase->due_amount = 0;
            $purchase->returned_amount = 0;
            $purchase->save();

            $products = $request->products;

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

                if (DB::table('product_stocks')->where(['product_id' => $product['id'], 'warehouse_id' => $request->warehouse_id])->count() > 0) {

                    DB::table('product_stocks')->where(['product_id' => $product['id'], 'warehouse_id' => $request->warehouse_id])->increment('stock_quantity', $invoiceItem->product_quantity);

                } else {
                    DB::table('product_stocks')
                        ->insert(
                            ['product_id' => $product['id'],
                                'warehouse_id' => $request->warehouse_id,
                                'stock_quantity' => $invoiceItem->product_quantity, ]
                        );
                }
            }

            DB::commit();

        } catch (Exception $e) {

            DB::rollback();

            return response()->json([
                'status' => 'error',
                'message' => $e,
            ], 500);

        }

    }
}
