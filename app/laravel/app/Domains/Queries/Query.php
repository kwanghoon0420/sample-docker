<?php
namespace App\Domains\Queries;

use Illuminate\Database\Eloquent\Builder as QueryBuilder;

class Query
{
    protected array $searchableFields = []; // 검색 가능한 필드 설정, 자식 클래스에서 설정

    private string $search = '';

    private string $searchBy = '';

    public function __construct(protected QueryBuilder $baseQuery) {}


    public function setSearch(?string $search = null, ?string $searchBy = null): static
    {
        $this->search = $search ?? '';
        $this->searchBy = $searchBy ?? '';

        return $this;
    }

    public function getSearchableFields(): array
    {
        return $this->searchableFields;
    }

    protected function query(): QueryBuilder
    {
        $query = $this->baseQuery;

        if (!empty($this->search)) {
            $search = $this->search;

            // 특정 필드 검색
            if (in_array($this->searchBy, $this->searchableFields)) {
                $query->where(function ($q) use ($search) {
                    $this->applyFieldSearch($q, $this->searchBy, $search);
                });
            }
            // 전체 검색 (모든 검색 가능 필드를 OR로 묶음)
            else {
                $query->where(function ($q) use ($search) {
                    foreach ($this->searchableFields as $field) {
                        $this->applyFieldSearch($q, $field, $search);
                    }
                });
            }
        }

        return $query->orderBy('created_at', 'desc');
    }

    /**
     * 필드별 LIKE 검색 조건 적용. 관계 컬럼 등 특수 처리가 필요한 경우 자식 클래스에서 오버라이드한다.
     */
    protected function applyFieldSearch(QueryBuilder $query, string $field, string $search): void
    {
        $like = '%' . $search . '%';

        if ($field === 'user_email') {
            $query->orWhereHas('user', function ($q) use ($like) {
                $q->where('email', 'like', $like);
            });
            return;
        }

        $query->orWhere($field, 'like', $like);
    }
}
