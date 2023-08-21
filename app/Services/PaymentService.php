<?php

namespace App\Services;

use App\Models\Accounting\Account;
use App\Models\Invoice\Invoice;
use App\Models\Party;
use App\Models\Payment;

class PaymentService
{
    public function __construct()
    {

    }

    public function createPayment(array $data)
    {
        $invoice = Invoice::find($data['invoice_id']);
        $invoice_due_before_payment = $invoice->due_amount;

        $payment = new Payment();
        $payment->invoice_id = $invoice->id;
        $payment->party_id = $invoice->party_id;
        $payment->account_id = $data['account_id'];
        $payment->invoice_type = $invoice->type;
        $payment->payment_method = $data['payment_method'] ?? 'cache';
        $payment->amount = $data['amount'];
        $payment->date = $data['date'];
        $payment->note = $data['note'];

        /*
            Ensure payment amount can not be greater than total amount
        */
        $payment->amount >= $invoice->total_amount ? $payment->amount = $invoice->total_amount : '';


        
        $invoice->paid_amount = $invoice->paid_amount + $payment->amount;
        $invoice->payment_status = $this->getPaymentStatus($invoice->total_amount, $invoice->paid_amount);
        $invoice->due_amount = $invoice->total_amount - $invoice->paid_amount;
        
        $invoice_due_after_payment = $invoice->due_amount;
        $invoice->save();

        /*
            Increase or Decrease Account Balance depending on invoice type
        */
        $account = Account::find($payment->account_id);

        if ($invoice->type == 'sale' || $invoice->type == 'purchase_return') {
            $account->increment('balance', $data['amount']);
        } elseif ($invoice->type == 'purchase' || $invoice->type == 'sale_return') {
            $account->decrement('balance', $data['amount']);
        }

        /*
            Adjust party's sale due, purchase due, sale return due, purchase return due
        */
        $party  = Party::find($invoice->party_id);
        if ($invoice->type == 'sale') {
            $party->sale_due =  $party->sale_due -  $invoice_due_before_payment +  $invoice_due_after_payment;
        } elseif ($invoice->type == 'purchase') {
            $party->purchase_due = $party->purchase_due -  $invoice_due_before_payment +  $invoice_due_after_payment;
        }

        $party->save();

        if($payment->amount>0){
            $payment->save();
        }

        return $payment;

    }

    public function getPaymentStatus($total, $paid)
    {

        if ($paid == 0) {
            return 'unpaid';
        } elseif ($paid >= $total) {
            return 'paid';
        } else {
            return 'partial';
        }

    }
}
