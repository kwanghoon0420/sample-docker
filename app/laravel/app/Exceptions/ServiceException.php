<?php
/**
 * 클라이언트로 오류 발생만 알리고 구체적인 오류 메시지는 로그에만 남김김
 */
namespace App\Exceptions;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Log;

class ServiceException extends HttpException
{
    public function __construct($message = '서비스 오류가 발생했습니다.', $code = 500)
    {
        parent::__construct($message, $code);
    }

    public function report()
    {
        Log::error($this->getMessage());
    }

}
