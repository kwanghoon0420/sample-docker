<?php
/**
 * 클라이언트로 오류 발생만 알리고 구체적인 오류 메시지는 로그에만 남김김
 */
namespace App\Exceptions;

use App\Exceptions\HttpException;
use Illuminate\Support\Facades\Log;
use Override;

class DomainException extends HttpException
{
    private array $data = [];

    public function __construct($message = '도메인 오류가 발생했습니다.', array $data = [], $code = 0)
    {
        parent::__construct($message, $code);
        $this->data = $data;
    }

    public function report()
    {
        Log::error($this->getMessage(), $this->data);
    }

    public function getData(): array
    {
        return $this->data;
    }

    #[Override]
    public function render()
    {
        return parent::render();
    }

}
