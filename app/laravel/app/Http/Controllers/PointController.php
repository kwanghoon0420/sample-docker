<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domains\Queries\PointChangedLogQuery;

class PointController extends Controller
{
    /**
     * 포인트 내역 (현재 로그인 사용자 기준)
     */
    public function pointList(Request $request, PointChangedLogQuery $pointChangedLogQuery)
    {
        $logs = $pointChangedLogQuery
            ->forUser($request->user()->id)
            ->setSearch($request->input('search'), $request->input('search_by'))
            ->query()
            ->paginate(12)
            ->withQueryString();

        $remainPoints = $request->user()->userPointEntity()->getRemainPoints();

        return view('mypage.point.list', [
            'logs' => $logs,
            'remainPoints' => $remainPoints,
        ]);
    }
}
