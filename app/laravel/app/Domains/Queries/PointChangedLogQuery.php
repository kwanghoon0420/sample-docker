<?php
namespace App\Domains\Queries;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\PointChangedLog;

class PointChangedLogQuery extends Query
{
    protected array $searchableFields = ['user_email', 'type', 'reference_id'];

    public function __construct()
    {
        parent::__construct(PointChangedLog::query());
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
        return parent::query();
    }
}
