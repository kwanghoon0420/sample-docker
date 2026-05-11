<?php
namespace App\Domains\Queries;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\Order;

class OrderQuery extends Query
{
    protected array $searchableFields = ['user_email', 'order_id', 'product_name'];

    public function __construct()
    {
        parent::__construct(Order::query());
    }

    /**
     * 특정 사용자 기준으로 스코프 한정
     */
    public function forUser(int $userId): static
    {
        $this->baseQuery->where('user_id', $userId);
        return $this;
    }

    public function query(): QueryBuilder
    {
        return parent::query()->with(['product', 'user']);
    }

    /**
     * product_name 은 product 관계의 name 컬럼으로 검색
     */
    protected function applyFieldSearch(QueryBuilder $query, string $field, string $search): void
    {
        if ($field === 'product_name') {
            $like = '%' . $search . '%';
            $query->orWhereHas('product', function ($q) use ($like) {
                $q->where('name', 'like', $like);
            });
            return;
        }

        parent::applyFieldSearch($query, $field, $search);
    }
}
