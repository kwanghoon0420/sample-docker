<?php
/**
 * 유용한 헬퍼 함수들
 */
namespace App\Support;

class Helper
{
    /**
     * 주문 번호 생성 함수
     * @return string
     */
    public static function generateOrderNo() {
        // YmdHis (14자리) + mt_rand (6자리)
        return date('YmdHis') . str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    }
}