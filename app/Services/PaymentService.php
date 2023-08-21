<?php

namespace App\Services;

use App\Models\Accounting\Account;
use App\Models\Invoice\Invoice;
use App\Models\Payment;

class PaymentService
{
    public function __construct()
    {

    }

    public function createPayment(array $data)
    {
        $invoice = Invoice::find($data['invoice_id']);

        $payment = new Payment();
        $payment->invoice_id = $invoice->id;
        $payment->party_id = $invoice->party_id;
        $payment->account_id = $data['account_id'];
        $payment->invoice_type = $invoice->type;
        $payment->payment_method = $data['payment_method'] ?? 'cache';
        $payment->amount = $data['amount'];
        $payment->date = $data['date'];
        $payment->note = $data['note'];
        $payment->save();

        /*
            Ensure payment amount can not be greater than total amount
            if payment amount is greater or equal to total amount, we will remove
            the extra part from the payment amount
        */
        $payment->amount >= $invoice->total_amount ? $payment->amount = $invoice->total_amount : '';

        if ($invoice) {
            $invoice->paid_amount = $invoice->paid_amount + $payment->amount;
            $invoice->payment_status = $this->getPaymentStatus($invoice->total_amount, $invoice->paid_amount);
            $invoice->due_amount = $invoice->total_amount - $invoice->paid_amount;
        }

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
