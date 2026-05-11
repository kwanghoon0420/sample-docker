<?php
/**
 * 주문 애그리거트 도메인 모델
 */
namespace App\Domains\Entities;

use App\Models\Order as OrderModel;
use App\Exceptions\DomainException;
use App\Models\Product as ProductModel;
use App\Support\Helper;

class OrderEntity
{
    public function __construct(private OrderModel $orderModel)
    {
        if($orderModel->id == 0) {
            throw new DomainException('주문을 찾을 수 없습니다.');
        }
    }

    public function model(): OrderModel
    {
        return $this->orderModel;
    }

    /**
     * 상품 주문 후 엔티티 반환
     */
    static public function orderProduct(int $userId, int $productId, int $quantity, int $orderAmount, int $amountToPay, int $usedPoints): static
    {
        $orderModel = OrderModel::create([
            'order_id' => 'O' . Helper::generateOrderNo(),
            'user_id' => $userId,
            'item_id' => $productId,
            'order_amount' => $orderAmount,
            'paied_amount' => $amountToPay,
            'used_points' => $usedPoints,
            'quantity' => $quantity,
            'status' => 'i',
        ]);

        return new static($orderModel);
    }

    /**
     * 주문 취소 가능여부
     */
    public function isCancelable(): bool
    {
        return in_array($this->orderModel->status, ['i', 'p'], true);
    }

    /**
     * 거래 확정 가능 (결제완료 → 거래완료)
     */
    public function isConfirmable(): bool
    {
        return $this->orderModel->status === 'p';
    }

    /**
     * 주문 취소
     */
    public function cancel(): void
    {
        $this->changeStatus('c');
    }

    /**
     * 주문 상태 변경
     */
    public function changeStatus(string $newStatus): void
    {
        $this->orderModel->status = $newStatus;
        $this->orderModel->save();
    }

    /**
     * 결제 확정
     */
    public function payConfirm(string $paiedAmount): void
    {
        $this->orderModel->paied_amount = (int) $paiedAmount;
        $this->orderModel->status = 'p';
        $this->orderModel->save();
    }

    /**
     * 거래 완료 확정
     */
    public function completePurchase(): void
    {
        $this->changeStatus('e');
    }
}