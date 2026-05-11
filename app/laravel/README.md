# 포인트 기반 이커머스 샘플 (Laravel)

포트폴리오용으로 정리한 **상품 주문·포인트 결제·주문 상태(취소/확정)** 흐름을 포함한 Laravel 웹 애플리케이션입니다.

## 기술 스택

- **PHP** 8.2+
- **Laravel** 12
- **인증**: Laravel Breeze
- **관리 UI**: Filament 3 (`/admin` — 포인트/로그 등 리소스)
- **프론트**: Blade + Tailwind(DaisyUI 등 기존 구성 유지)

## 주요 기능

- **상품**: 목록·상세, 재고 기반 주문 가능 여부
- **주문**: 포인트 사용 주문, 상태(`결제대기` → `결제완료` → `거래완료` / 취소·환불)
- **마이페이지**: 주문 내역, 취소·확정(조건 충족 시)
- **관리자 영역 (`/admin2`)**: 주문 목록, 사용자와 동일한 **취소·확정** 유스케이스 호출 (접근은 관리자만)

## 아키텍처 / 디자인 패턴 요약

| 구분 | 역할 |
|------|------|
| **Application Service** | `App\Services\Order\OrderApplicationService` — 유스케이스(주문/취소/확정) 조율, 트랜잭션 경계 |
| **Command (입력 DTO)** | `App\Services\Order\Commands\*` — 유스케이스별 입력 묶음 (Place / Cancel / Confirm) |
| **Domain Entity** | `App\Domains\Entities\*` — 주문·상품·포인트 등 도메인 규칙과 상태 전이 |
| **Domain Service** | `App\Domains\Services\*` — 예: 포인트 정산 코어(`PointCore`) 등 하위 정산 로직 |
| **정책 클래스** | `App\Domains\Services\OrderActionPolicy` — 주문 취소/확정 시 “누가 할 수 있는지” (주문자 또는 관리자) |
| **HTTP 진입** | Controller는 검증·권한(일부 `abort_if`) 후 Application Service 호출 |
| **관리자 라우트 보호** | `admin2` 그룹에 `Admin2Middleware` — `OrderActionPolicy::isAdmin()` 통과 시만 접근 |

DDD 용어로 말하면, **애그리거트 단위 규칙은 Entity**, **여러 객체를 묶는 시나리오는 Application Service**, **한 엔티티에 넣기 애매한 정책/정산은 Domain Service** 쪽에 두는 방향으로 정리했습니다.

## 디렉터리 구조 (요약)

```
app/
├── Domains/
│   ├── Entities/          # 도메인 엔티티 (Order, Product, UserPoint 등)
│   ├── Services/          # 도메인 서비스 (PointCore, OrderActionPolicy 등)
│   └── Queries/           # 목록 조회용 쿼리 객체
├── Services/Order/        # 주문 애플리케이션 서비스 + Commands
├── Http/
│   ├── Controllers/       # 웹 진입점
│   └── Middleware/        # Admin2Middleware 등
└── Models/                # Eloquent 모델
```

## 로컬 실행 (참고)

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

DB·캐시 드라이버는 `.env`에 맞게 설정합니다. Docker를 쓰는 경우 프로젝트 루트의 `docker-compose` 등 기존 구성을 따르면 됩니다.

## 라이선스

MIT (Laravel 기본 스켈레톤과 동일)
