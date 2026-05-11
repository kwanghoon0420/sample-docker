<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Entities\ProductEntity;

class Product extends Model
{
    // 상품 주문 가능성 확인
    public function isAvailableForOrder(): bool
    {
        // 상태 확인
        if ($this->status !== 'a') {
            return false; // 주문 불가능한 상태
        }
        // 재고 확인
        if ($this->stock <= 0) {
            return false; // 재고가 없으면 주문 불가능
        }

        return true; // 예시로 항상 주문 가능하다고 가정
    }

    // price 값을 포맷팅하여 반환하는 액세서
    public function getFormattedPriceAttribute(): string
    {        
        return number_format($this->price) . '원';
    }

    public function productEntity(): ProductEntity
    {
        return new ProductEntity($this);
    }
}
