<?php

namespace App\Http\Controllers\Api\Payment;

use App\Http\Controllers\Controller;
use App\Http\Requests\Payment\CreatePaymentRequest;
use App\Services\PaymentService;
use Exception;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    function __construct() {

        $this->paymentService = new PaymentService();
    }

    public function store(CreatePaymentRequest $request)
    {
        try {

            $this->paymentService->createPayment($request->validated());

        } catch (Exception $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'failed to create payment',
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'payment created succesfully',
        ], 201);
    }

}
