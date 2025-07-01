<?php
namespace App\Http\Resources;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class PlanResource extends JsonResource
{
    public function toArray($request)
    {
        $vouchers = $this->vouchers
            ? $this->vouchers->filter(function ($voucher) {
                return $voucher->used_count < $voucher->max_uses;
            })->values()
            : [];
        if (empty($request->user_uuid)) {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description,
                'price' => $this->price,
                'duration_days' => $this->duration_days,
                'max_resolution' => $this->max_resolution,
                'vouchers' => $vouchers,
            ];
        }

        $newVouchers = [];

        foreach ($vouchers as $voucher) {
            if ($voucher->only_once_per_user) {
                $isUsed = Subscription::query()
                    ->where('user_uuid', $request->user_uuid)
                    ->whereHas('vouchers', function ($query) use ($voucher) {
                        $query->where('voucher_id', $voucher->id);
                    })
                    ->exists();
                if (!$isUsed) $newVouchers[] = $voucher;
                continue;
            }

            $newVouchers[] = $voucher;
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'duration_days' => $this->duration_days,
            'max_resolution' => $this->max_resolution,
            'vouchers' => collect($newVouchers)->values(),
        ];
    }

}
