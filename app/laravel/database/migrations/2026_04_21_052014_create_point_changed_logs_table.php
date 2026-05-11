<?php
/**
 * 포인트 변동 내역
 * 포인트 충전, 적립, 사용, 환불, 복원 등의 내역을 저장
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
        Schema::create('point_changed_logs', function (Blueprint $table) {
            $table->id();
            $table->string('reference_id', 25)->index(); // 참조 id (주문, 충전, 적립 등 관련 id )
            $table->unsignedInteger('user_id')->index();
            $table->decimal('before_amount', 15, 2); // 변동 전 금액
            $table->decimal('changed_amount', 15, 2); // 변동금액
            $table->decimal('after_amount', 15, 2); // 변동 후 금액
            $table->enum('type', ['c', 'e', 'u', 'r', 'x'])->index(); // c:충전, e:적립, u:사용, r:환불, x:만기
            $table->string('admin_id')->nullable(); // 처리한 관리자 id (사용자 일 시 널값)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_changed_logs');
    }
};
