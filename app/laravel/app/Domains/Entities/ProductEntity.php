<?php
/**
 * 상품 애그리거트 도메인 모델
 */
namespace App\Domains\Entities;

use App\Models\Product as ProductModel;
use App\Exceptions\DomainException;

class ProductEntity
{
    public function __construct(private ProductModel $productModel)
    {
        if($productModel->exists == false) {
            throw new DomainException('상품을 찾을 수 없습니다.');
        }
    }

    /**
     * 상품 주문 가능성 확인
     * @return void
     */
    public function checkAvailableForOrder(int $quantity): void
    {
        if($this->productModel->status !== 'a') {
            throw new DomainException('상품을 주문할 수 없습니다.');
        }

        if ($this->productModel->stock < $quantity) {
            throw new DomainException('상품 재고가 부족합니다.');
        }
    }

    /**
     * 상품 초기화
     * @param int $productId
     * @return static
     */
    static public function init(int $productId): static
    {
        $productModel = ProductModel::find($productId);
        if (!$productModel) {
            throw new DomainException('상품을 찾을 수 없습니다.');
        }
        return new static($productModel);
    }

    public function model(): ProductModel
    {
        return $this->productModel;
    }

    /**
     * 재고 감소 - 상품 재고 감소 정확성 보장
     * @param int $quantity
     * @return void
     */
    public function decreaseStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new DomainException('수량이 올바르지 않습니다.');
        }
        $affected = ProductModel::query()
            ->whereKey($this->productModel->getKey())
            ->where('status', '=', 'a')
            ->where('stock', '>=', $quantity)
            ->decrement('stock', $quantity);
        if ($affected === 0) {
            throw new DomainException('상품 재고가 부족합니다.');
        }

        $this->productModel->refresh();
    }

    /**
     * 재고 복구 (주문 취소 등)
     */
    public function increaseStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new DomainException('수량이 올바르지 않습니다.');
        }
        ProductModel::query()
            ->whereKey($this->productModel->getKey())
            ->increment('stock', $quantity);

        $this->productModel->refresh();
    }
}