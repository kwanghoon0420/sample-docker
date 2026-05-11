<?php
/**
 * 주문 액션(취소/확정) 권한 정책
 * - 기본: 주문자 본인만 가능
 * - 예외: 관리자(현재는 user_id=1)를 허용
 */
namespace App\Domains\Services;

use App\Models\Order;

final class OrderActionPolicy
{
    public static function isAdmin(int $actorUserId): bool
    {
        return $actorUserId === 1;
    }

    public static function canCancel(int $actorUserId, Order $order): bool
    {
        return self::isAdmin($actorUserId) || $actorUserId === (int) $order->user_id;
    }

    public static function canConfirm(int $actorUserId, Order $order): bool
    {
        return self::isAdmin($actorUserId) || $actorUserId === (int) $order->user_id;
    }
}

