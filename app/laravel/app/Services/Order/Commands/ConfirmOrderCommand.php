<?php

declare(strict_types=1);

namespace App\Services\Order\Commands;

/**
 * 주문(거래) 확정 — 결제완료(p) → 거래완료(e)
 */
final readonly class ConfirmOrderCommand
{
    public function __construct(
        public int|string $orderId,
        public int $actorUserId,
    ) {}
}
