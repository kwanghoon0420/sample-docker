<?php
namespace App\Domains\Queries;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use App\Models\User;

class UserQuery extends Query
{
    protected array $searchableFields = ['id', 'email', 'name']; // 검색 가능한 필드 설정, 자식 클래스에서 설정
    
    public function __construct()
    {
        parent::__construct(User::query());
    }

    public function query(): QueryBuilder
    {
        return parent::query()->with('point:user_id,remain_amount');
    }
}