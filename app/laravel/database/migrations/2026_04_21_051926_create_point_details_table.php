<?php
/**
 * 적립금 상세내역 (충전, 적립 등)
 * 적립금 사용 시 여기서  내역별로 차감
 * 적립금 환불 및 복원 시에 원 위치로 복귀
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
        Schema::create('point_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->decimal('origin_amount', 15, 2); // 원 금액
            $table->decimal('used_amount', 15, 2); // 사용 금액
            $table->decimal('remain_amount', 15, 2)->index(); // 잔여 금액
            $table->enum('used_flag', ['y', 'n'])->index(); // 사용 완료 여부 (y: 사용 완료, n: 사용 미완료)
            $table->dateTime('expire_at')->index(); // 만기일
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_details');
    }
};
