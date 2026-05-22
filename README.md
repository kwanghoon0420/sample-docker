# 포인트 기반 이커머스 샘플 (Laravel)

포트폴리오용으로 정리한 **상품 주문·포인트 결제·주문 상태(취소/확정)** 흐름을 포함한 Laravel 웹 애플리케이션입니다.
어플리케이션 주소 ./app/laravel

## 기술 스택

- **PHP** 8.2+
- **Laravel** 12
- **인증**: Laravel Breeze
- **관리 UI**: Filament 3 (`/admin` — 포인트 데이터 확인을 위해 설치)
- **프론트**: Blade + Tailwind(DaisyUI 등 기존 구성 유지)

## 주요 기능

- **상품**: 목록·상세, 재고 기반 주문 가능 여부
- **주문**: 포인트 사용 주문, 상태(`결제대기` → `결제완료` → `거래완료` / 취소·환불)
- **마이페이지**: 주문 내역, 취소·확정(조건 충족 시)
- **관리자 영역 (`/admin2`)**: 주문 목록, 사용자와 동일한 **취소·확정** 유스케이스 호출 (접근은 관리자만)
- 현재 관리자 아이디: admin@admin 비밀번호: adminadmin

## 아키텍처 / 디자인 패턴 요약

| 구분 | 역할 |
|------|------|
| **Application Service** | `App\Services\Order\OrderApplicationService` — 유스케이스(주문/취소/확정) 조율, 트랜잭션 경계 |
| **Command (입력 DTO)** | `App\Services\Order\Commands\*` - 요청 데이터 묶음 (Place / Cancel / Confirm) |
| **Domain Entity** | `App\Domains\Entities\*` — 주문·상품·포인트 등 도메인 엔티티, 생명주기가 있는 단일객체 |
| **Domain Service** | `App\Domains\Services\*` — 2개 이상의 도메인이 들어가는 규칙이 필요할 때 여기에 생성 예: 특정 유저가 특정 주문건에 대한 취소 권한이 있는지 |
| **Domain  Query** | App\Domains\Queries\*` 상품 조회, 주문 조회(검색) 등 쿼리로직들
| **HTTP 진입** | Controller는 Http 요청에 대한 처리, 필요한 서비스 호출, 적절한 response 를 반환하는 역할만 함.(아주 간단한 서비스 작업일 시 컨트롤러에서 직접 처리)
| **관리자 라우트 보호** | `admin2` 그룹에 `Admin2Middleware` — `OrderActionPolicy::isAdmin()` 통과 시만 접근 |

디자인 패턴에 대해 간단히 요약하자면
주문, 상품 등에 개별적인 기능(주문취소, 상품재고 감소 등)들은 각자 엔티티에서 담당하고 서로의 규칙에 대해 간섭하지 않습니다.
여러 엔티티가 필요한 작업은 Application Service 가 담당합니다.
컨트롤러나 Application Service 에서 if문구를 최대한 지양하고
필요한 판단로직이나 수행작업은 엔티티에게 위임합니다.
이렇게 분류한 이유는 도메인 별로 책임과 역할을 명확히 하고
컨트롤러나 Application Service 과 같은 루트 위치에서 봤을 때 이 요청은 어떤 작업을 하는지 한눈이 알아보기 쉽고
디테일한 작업을 알고싶을 때는 엔티티에 들어가서 확인할 수 있으며
특정된 규칙에 수정이 필요할 시 해당 엔티티만 수정하여 유지보수에 용이합니다.

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

### 스키마 요약
스키마 파일:
./app/laravel/database/migrations

| 테이블 | 설명 |
|--------|------|
| **users** | 사용자 테이블
| **products** | 상품 테이블
| **orders** | 상품 테이블
| **points** | 사용자별 포인트 잔여금 테이블
| **point_details** | 포인트 상세내역
| **point_changed_logs** | 포인트 변동로그
| **point_detail_changed_logs** | 포인트 상세내역 변동로그

이 포트폴리오는 포인트 시스템 위주로 설계하였습니다.
* users 테이블은 사용자별 잔여 포인트를 저장하고 사용자가 포인트 사용 시 "사용중" 적립금이 쌓이게 되며 확정/취소 처리할 시 해당 사용중 적립금은 사라집니다.
* 충전된 포인트는 point_details 에 insert 가 되며 row 별로 만료시일이 존재합니다.
* 사용자가 포인트 사용 시 해당 테이블 포인트를 생성된 시간 역순으로 뽑아서 사용합니다.
* point_changed_logs 사용자의 포인트가 변화가 있을 때마다 저장됩니다.
* point_detail_changed_logs 는 point_details 에 row 별로의 변동내역을 저장합니다.



