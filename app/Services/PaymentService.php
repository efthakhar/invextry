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

        if ($invoice->payment_status == 'paid') {
            return;
        }

        $payment = new Payment();
        $payment->invoice_id = $invoice->id;
        $payment->party_id = $invoice->party_id;
        $payment->account_id = $data['account_id'];
        $payment->invoice_type = $invoice->type;
        $payment->payment_method = $data['payment_method'] ?? 'cash';
        $payment->amount = $data['amount'];
        $payment->date = $data['date'];
        $payment->note = $data['note'];

        /*
            Ensure payment amount can not be greater than due amount
        */
        $payment->amount >= $invoice->due_amount ? $payment->amount = $invoice->due_amount : '';

        /*
           Determine paid amount, payment status and due amount of invoice
           depending on the payment amount
        */
        $invoice->paid_amount = $invoice->paid_amount + $payment->amount;
        $invoice->payment_status = $this->getPaymentStatus($invoice->total_amount, $invoice->paid_amount);
        $invoice->due_amount = $invoice->total_amount - $invoice->paid_amount;

        $invoice->save();

        if ($payment->amount > 0) {

            /*
                Increase or Decrease Account Balance depending on invoice type
            */

            $account = Account::find($payment->account_id);

            if ($invoice->type == 'sale' || $invoice->type == 'purchase_return') {
                $account->increment('balance', $data['amount']);
            } elseif ($invoice->type == 'purchase' || $invoice->type == 'sale_return') {
                $account->decrement('balance', $data['amount']);
            }
        }

        /*
            Adjust party's sale due, purchase due, sale return due, purchase return due
        */
        $party = Party::find($invoice->party_id);
        if ($invoice->type == 'sale') {
            $party->sale_due = Invoice::where('party_id', $party->id)->where('type', 'sale')->sum('due_amount');
        } elseif ($invoice->type == 'purchase') {
            $party->purchase_due = Invoice::where('party_id', $party->id)->where('type', 'purchase')->sum('due_amount');
        }
        $party->save();

        if ($payment->amount > 0) {
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
