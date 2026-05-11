<?php
namespace App\Domains\Queries;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Product as ProductModel;

class ProductQuery
{
    public function __construct(
        private ProductModel $productModel
    ) {}

    protected function listQuery(array $filters): QueryBuilder
    {
        // 상품 목록 조회 로직 구현
        $listQuery = $this->productModel->query();

        if(!empty($filters['search'])) {
            $listQuery->where('id', 'like', '%' . $filters['search'] . '%')
                ->orWhere('name', 'like', '%'. $filters['search'] . '%')
                ->orWhere('description', 'like', '%'. $filters['search'] . '%');
        }

        if(!empty($filters['status'])) {
            $listQuery->whereIn('status', $filters['status']);
        }

        return $listQuery->orderBy('created_at', 'desc');
    }

    public function listQueryForAdmin(array $filters): QueryBuilder
    {
        // 관리자용 상품 목록 조회 로직 구현 (필터링, 정렬 등)
        $filters['status'] = ['d', 'a', 's', 'e']; // 임시, 판매중, 품절, 판매종료 상품 모두 조회
        return $this->listQuery($filters);
    }

    public function listQueryForUser(array $filters): QueryBuilder
    {
        // 관리자용 상품 목록 조회 로직 구현 (필터링, 정렬 등)
        $filters['status'] = ['a', 's']; // 판매중, 품절 상품만 조회
        return $this->listQuery($filters);
    }
}