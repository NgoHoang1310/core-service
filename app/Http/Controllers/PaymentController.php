<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Models\Voucher;
use App\Services\Queue\Producers\SocketProducer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends ApiController
{
    public SocketProducer $socketProducer;
    public function __construct(SocketProducer $socketProducer)
    {
        $this->socketProducer = $socketProducer;
    }

    public function createPayment(Request $request)
    {
        $vnp_TmnCode = config('vnpay.vnp_TmnCode');
        $vnp_HashSecret = config('vnpay.vnp_HashSecret');
        $vnp_Url = config('vnpay.vnp_Url');
        Log::info($request->user_id);
        $vnp_ReturnUrl = config('vnpay.vnp_ReturnUrl') . '?user_uuid=' . $request->user_uuid . '&plan_id=' . $request->plan_id . '&user_id=' . $request->user_id;

        $vnp_TxnRef = rand(10000, 99999); // mã đơn hàng
        $vnp_OrderInfo = $request->order_name;
        $vouchers = json_decode($request->vouchers, true) ?? [];
        $durationDays = $request->duration_days ?? 30; // Mặc định là 30 ngày nếu không có
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
                    'start_date' => Carbon::today(),
                    'next_bill_at' => Carbon::today()->addDays($durationDays)->setTime(23, 59, 59),
                    'status' => Subscription::STATUS_PENDING,
                ]);

                if (!empty($vouchers)) {
                    $voucherIds = array_keys($vouchers);

                    // ✅ Chỉ 1 truy vấn lấy tất cả voucher liên quan
                    $voucherList = Voucher::whereIn('id', $voucherIds)
                        ->lockForUpdate() // nếu cần tránh race condition
                        ->get()
                        ->keyBy('id'); // giúp truy cập nhanh hơn

                    $syncData = [];

                    foreach ($vouchers as $voucherId => $discountAmount) {
                        $voucher = $voucherList->get($voucherId);

                        if (!$voucher || $voucher->used_count >= $voucher->max_uses) {
                            Log::error("Voucher with ID $voucherId is not valid or has reached its usage limit.");
                            continue;
                        }

                        $syncData[$voucherId] = ['discount_amount' => $discountAmount];
                    }

                    // Ghi vào bảng pivot
                    $subscription->vouchers()->sync($syncData);

                    // Cập nhật used_count (tối ưu: dùng updateMany nếu cần)
                    foreach ($voucherList as $voucher) {
                        $voucher->increment('used_count');
                    }
                }

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
        Log::warning($request);
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
                        $currentSubscriptions = Subscription::query()
                            ->where('user_uuid', $request->user_uuid)
                            ->where('id', '!=', $payment->subscription_id)
                            ->get();
                        foreach ($currentSubscriptions as $subscription) {
                            // Hủy các subscription khác
                            $subscription->status = Subscription::STATUS_CANCELED;
                            $subscription->save();
                        }

                        $subscription = Subscription::query()->where('id', $payment->subscription_id)
                            ->first();

                        if ($subscription) {
                            $subscription->status = Subscription::STATUS_ACTIVE;
                            $subscription->save();

                            if (!empty($request->user_id)) {
                                $notification = new Notification();
                                $notification->title = 'Thanh toán thành công';
                                $notification->content = 'Thanh toán cho gói ' . $subscription->plan->name . ' thành công';
                                $notification->type = Notification::PERSONAL;
                                $notification->target_id = $request->user_id;
                                $notification->payload = json_encode($subscription);
                                $notification->save();

                                $this->socketProducer->processSocket(Notification::resolveRoom(Notification::PERSONAL, $request->user_id), $notification->toArray());
                            }
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
