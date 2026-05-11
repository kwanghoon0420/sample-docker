<?php

declare(strict_types=1);

namespace App\Services\Order\Commands;

/**
 * 주문 취소 — orders PK 및 행위 주체
 */
final readonly class CancelOrderCommand
{
    public function __construct(
        public int|string $orderId,
        public int $actorUserId,
    ) {}
}
