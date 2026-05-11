<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'item_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 상태 변경
    public function changeStatus(string $newStatus): void
    {
        // 주문 상태: i:결제대기, p:결제완료, e:거래완료, c:주문취소, r:환불완료
        $this->status = $newStatus;
        $this->save();
    }

    public function formattedOrderAmount(): string
    {
        return number_format((int) $this->order_amount) . '원';
    }

    public function formattedPaiedAmount(): string
    {
        return number_format((int) $this->paied_amount) . '원';
    }

    public function formattedUsedPoints(): string
    {
        return number_format((int) $this->used_points);
    }

    public function textStatus(): string
    {
        return match ($this->status) {
            'i' => '결제대기',
            'p' => '결제완료',
            'e' => '거래완료',
            'c' => '주문취소',
            'r' => '환불완료',
            default => '',
        };
    }
}
