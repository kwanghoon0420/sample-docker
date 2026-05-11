<?php
/* 회원별 적립금금 잔여금액 */

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
        Schema::create('points', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('user_id')->index();
            $table->decimal('remain_amount', 15, 2)->index(); // 잔여 적립금 금액
            $table->enum('status', ['a', 'i', 'l'])->index(); // a:정상, i:사용 중, l: 잠금
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('points');
    }
};
