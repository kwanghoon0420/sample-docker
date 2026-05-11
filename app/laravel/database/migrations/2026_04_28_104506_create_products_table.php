<?php
/**
 * 상품 테이블 마이그레이션 파일
 * - 상품명, 설명, 가격, 재고 수량, 상태 등을 저장하는 테이블을 생성한다.
 * - 상품 상태는 d:임시, a:판매중, s:품절, e:판매종료, x:삭제로 구분한다.
 */
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->longText('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->integer('stock'); // 재고 수량
            $table->enum('status', ['d', 'a', 's', 'e', 'x'])->index(); // 상품 상태 d:임시, a:판매중, s:품절, e:판매종료, x:삭제
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
