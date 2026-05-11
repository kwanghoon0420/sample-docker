<?php

namespace App\Exceptions;

use Exception;

class HttpException extends Exception
{
    public function render()
    {
        // 웹일 때와 ajax 요청일 때 처리 방식이 다름
        if (request()->ajax()) {
            return response()->json([
                'status' => 'error',
                'msg' => $this->getMessage(),
            ], $this->getCode());
        }
        
        // 웹일 때는 alert 창 출력 후 이전 페이지로 이동
        return redirect()->back()->with('alert', $this->getMessage());
    }
}
