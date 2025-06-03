<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends ApiController
{
    public function createPayment(Request $request)
    {
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        $vnp_ReturnUrl = config('vnpay.vnp_ReturnUrl') . '?user_uuid=' . $request->user_uuid . '&plan_id=' . $request->plan_id;

        $vnp_TxnRef = rand(10000, 99999); // mã đơn hàng
        $vnp_OrderInfo = $request->order_name;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = $request->amount * 100; // Số tiền * 100 (đơn vị là VNĐ)
        $vnp_Locale = 'vn';
        $vnp_BankCode = 'VNBANK';
        $vnp_IpAddr = $request->ip();
        $vnp_CreateDate = now()->format('YmdHis');

        $inputData = [
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => $vnp_CreateDate,
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_BankCode" => $vnp_BankCode,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_ReturnUrl,
            "vnp_TxnRef" => $vnp_TxnRef,
        ];

        // Sắp xếp theo key
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;

        DB::beginTransaction();
        try {
            $subscription = Subscription::query()
                ->where('user_uuid', $request->user_uuid)
                ->where('plan_id', $request->plan_id)
                ->first();

            if (!$subscription) {
                // Chưa có -> tạo mới
                $subscription = Subscription::create([
                    'user_uuid' => $request->user_uuid,
                    'plan_id' => $request->plan_id,
                    'start_date' => now(),
                    'next_bill_at' => now(),
                    'status' => Subscription::STATUS_PENDING,
                ]);
            }

            Payment::create([
                'user_uuid' => $request->user_uuid,
                'subscription_id' => $subscription->id,
                'amount' => $request->amount,
                'payment_date' => $vnp_CreateDate,
                'transaction_id' => $vnp_TxnRef,
                'bank_code' => $vnp_BankCode,
                'status' => Payment::STATUS_PENDING,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            // Handle exception
            DB::rollBack();
            return $this->errorResponse('Payment creation failed: ' . $exception->getMessage(), 500);
        }

        return $this->successResponse([
            'url' => $vnp_Url,
        ], 'Generated payment URL successfully');
    }

    public function paymentReturn(Request $request)
    {
        $inputData = [];

        foreach ($request->query() as $key => $value) {
            if (str_starts_with($key, 'vnp_')) {
                $inputData[$key] = $value;
            }
        }

        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? null;
        unset($inputData['vnp_SecureHash']);
        ksort($inputData);

        $i = 0;
        $hashData = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashData .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashData .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, $vnp_HashSecret);
        $transaction_id = $inputData['vnp_TxnRef'] ?? null;
        $responseCode = $inputData['vnp_ResponseCode'] ?? null;

        if ($secureHash == $vnp_SecureHash) {
            DB::beginTransaction();
            try {
                $payment = Payment::query()->where([
                    'transaction_id' => $transaction_id,
                    'status' => Payment::STATUS_PENDING,
                ])->first();

                if ($payment) {
                    if ($responseCode == '00') {
                        // Giao dịch thành công
                        $payment->status = Payment::STATUS_SUCCESS;
                        $payment->save();
                        Log::info($request->user_uuid);
                        $currentSubscriptions = Subscription::query()
                            ->where('user_uuid', $request->user_uuid)
                            ->where('id', '!=', $payment->subscription_id)
                            ->get();
                        Log::warning($currentSubscriptions);
                        foreach ($currentSubscriptions as $subscription) {
                            // Hủy các subscription khác
                            $subscription->status = Subscription::STATUS_CANCELED;
                            $subscription->save();
                        }

                        $subscription = Subscription::query()->where('id', $payment->subscription_id)
                            ->first();

                        if ($subscription) {
                            $subscription->status = Subscription::STATUS_ACTIVE;
                            $subscription->start_date = now();
                            $subscription->next_bill_at = now()->addMonth();
                            $subscription->save();
                        }
                    } else {
                        $payment->status = Payment::STATUS_FAILED;
                        $payment->save();
                    }
                }

                DB::commit();
            } catch (\Exception $e) {
                DB::rollBack();
            }
        }

        return redirect()->away('http://localhost:5173/payment/return?' . http_build_query($request->query()));
    }

}
