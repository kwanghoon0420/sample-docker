<?php

declare(strict_types=1);

namespace App\Services\Order\Commands;

/**
 * 상품 주문(유스케이스 입력)
 */
final readonly class PlaceOrderCommand
{
    public function __construct(
        public int $userId,
        public int $productId,
        public int $quantity,
        public string $usedPoints,
    ) {}
}
