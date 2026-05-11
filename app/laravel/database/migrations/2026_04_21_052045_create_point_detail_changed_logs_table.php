<?php
/**
 * 적립금 상세내역 변동 내역
 * 적립금 상세내역 테이블에 row 별로 변동 내역을 저장
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
        Schema::create('point_detail_changed_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('point_changed_log_id')->constrained(); // 포인트 변동 내역 id
            $table->foreignId('point_detail_id')->constrained(); // 적립금 상세내역 id
            $table->unsignedInteger('user_id')->index();
            $table->decimal('changed_amount', 15, 2); // 변동금액
            $table->enum('type', ['u', 'r', 'x'])->index(); // u:사용, r:환불, x:만기
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_detail_changed_logs');
    }
};
