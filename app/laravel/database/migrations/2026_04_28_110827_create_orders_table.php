<?php
/**
 * 주문 테이블 마이그레이션 파일
 * - 주문 번호, 사용자 ID, 주문 상태 등을 저장하는 테이블을 생성한다.
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
        Schema::create('orders', function (Blueprint $table) {
            // 주문번호, 사용자 ID, 주문상품, 주문금액, 결제금액, 사용 포인트, 결제ID, 주문상태
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('user_id')->constrained();
            $table->integer('item_id');
            $table->integer('quantity');
            $table->decimal('order_amount', 15, 2);
            $table->decimal('paied_amount', 15, 2);
            $table->string('payment_id')->nullable();
            $table->integer('used_points');
            $table->integer('used_points_changed_log_id')->nullable();
            $table->enum('status', ['i', 'p', 'e', 'c', 'r'])->index(); //주문 상태: i:결제대기, p:결제완료, e:거래완료, c:주문취소, r:환불완료
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
