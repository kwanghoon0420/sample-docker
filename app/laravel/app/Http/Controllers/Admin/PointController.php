<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Queries\PointChangedLogQuery;

class PointController extends Controller
{
    public function logs(Request $request, PointChangedLogQuery $pointChangedLogQuery)
    {
        $pointChangedLogs = $pointChangedLogQuery
            ->setSearch($request->input('search'), $request->input('search_by'))
            ->query()
            ->paginate(10);

        return view('admin.point_logs', ['pointChangedLogs' => $pointChangedLogs]);
    }

}
