<?php

declare(strict_types=1);

namespace App\Services\Order;

use App\Domains\Entities\OrderEntity;
use App\Domains\Entities\ProductEntity;
use App\Domains\Entities\UserPointEntity;
use App\Exceptions\DomainException;
use App\Models\Order as OrderModel;
use App\Models\User;
use App\Services\Order\Commands\CancelOrderCommand;
use App\Services\Order\Commands\ConfirmOrderCommand;
use App\Services\Order\Commands\PlaceOrderCommand;
use Illuminate\Support\Facades\DB;
use App\Domains\Services\OrderActionPolicy;

/**
 * 주문 유스케이스 조율 (애플리케이션 서비스) — 무상태, 커맨드 단위
 */
final class OrderApplicationService
{
    public function placeOrder(PlaceOrderCommand $command): void
    {
        $user = User::find($command->userId);
        if (!$user) {
            throw new DomainException('사용자를 찾을 수 없습니다.');
        }

        $userPointEntity = new UserPointEntity($user);
        $productEntity = ProductEntity::init($command->productId);

        DB::transaction(function () use ($command, $userPointEntity, $productEntity): void {
            $productEntity->checkAvailableForOrder($command->quantity);
            $productEntity->decreaseStock($command->quantity);

            $unitPrice = (int) $productEntity->model()->price;
            $orderAmount = $unitPrice * $command->quantity;
            $usedPoints = (int) $command->usedPoints;
            $amountToPay = bcsub((string) $orderAmount, (string) $usedPoints);

            $orderEntity = OrderEntity::orderProduct(
                userId: $command->userId,
                productId: $command->productId,
                quantity: $command->quantity,
                orderAmount: $orderAmount,
                amountToPay: (int) $amountToPay,
                usedPoints: $usedPoints,
            );

            $result = $userPointEntity->use($usedPoints, (string) $orderEntity->model()->order_id);
            $orderEntity->model()->used_points_changed_log_id = $result['changed_log_id'];
            $orderEntity->model()->save();

            // 결제 확정 처리
            $orderEntity->payConfirm((string) $amountToPay);
        });
    }

    public function cancelOrder(CancelOrderCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $locked = OrderModel::query()->whereKey($command->orderId)->lockForUpdate()->first();
            if (!$locked) {
                throw new DomainException('주문을 찾을 수 없습니다.');
            }
            
            $orderEntity = new OrderEntity($locked);
            if (!$orderEntity->isCancelable()) {
                throw new DomainException('주문 취소 가능한 상태가 아닙니다.');
            }
            
            $user = User::find($locked->user_id);
            if (!$user) {
                throw new DomainException('사용자를 찾을 수 없습니다.');
            }

            if (!OrderActionPolicy::canCancel($command->actorUserId, $locked)) {
                throw new DomainException('주문을 취소할 권한이 없습니다.');
            }

            $userPointEntity = new UserPointEntity($user);
            $productEntity = ProductEntity::init((int) $locked->item_id);

            $usedPoints = (int) $locked->used_points;
            if ($usedPoints > 0) {
                $userPointEntity->refund($locked->used_points_changed_log_id);
                $orderEntity->changeStatus('r');
            } else {
                $orderEntity->changeStatus('c');
            }

            $productEntity->increaseStock((int) $locked->quantity);
        });
    }

    public function confirmOrder(ConfirmOrderCommand $command): void
    {
        DB::transaction(function () use ($command): void {
            $locked = OrderModel::query()->whereKey($command->orderId)->lockForUpdate()->first();
            if (!$locked) {
                throw new DomainException('주문을 찾을 수 없습니다.');
            }

            $orderEntity = new OrderEntity($locked);
            if (!$orderEntity->isConfirmable()) {
                throw new DomainException('주문을 확정할 수 있는 상태가 아닙니다.');
            }

            $user = User::find($locked->user_id);
            if (!$user) {
                throw new DomainException('사용자를 찾을 수 없습니다.');
            }

            if (!OrderActionPolicy::canConfirm($command->actorUserId, $locked)) {
                throw new DomainException('주문을 확정할 권한이 없습니다.');
            }

            if ((int) $locked->used_points > 0) {
                (new UserPointEntity($user))->confirm($locked->order_id);
            }

            $orderEntity->completePurchase();
        });
    }
}
