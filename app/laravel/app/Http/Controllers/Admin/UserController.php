<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domains\Queries\UserQuery;
use App\Models\User;
use App\Exceptions\HttpException;
use App\Domains\Entities\UserPointEntity;
use Carbon\Carbon;

class UserController extends Controller
{
    public function list(Request $request, UserQuery $userQuery)
    {
        $users = $userQuery->setSearch($request->input('search'), $request->input('search_by'))->query()->paginate(10);
        
        return view('admin.user_list', ['users' => $users]);
    }

    public function earnPointPage(Request $request)
    {
        $user = User::find($request->input('user_id'));
        if (!$user) {
            throw new HttpException('사용자를 찾을 수 없습니다.');
        }

        return view('admin.point_earn', ['user' => $user]);
    }

    public function earnPoint(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|numeric|min:0',
            'amount' => 'required|numeric|min:0',
            'expire_days' => 'required|numeric|min:0',
        ]);
        $expireDate = Carbon::now()->addDays((int) $validatedData['expire_days']);

        $user = User::find($validatedData['user_id']);
        if (!$user) {
            throw new HttpException('사용자를 찾을 수 없습니다.');
        }

        $userPoint = new UserPointEntity($user);
        $adminId = $request->user()->id; // 관리자 ID
        $userPoint->setAdminId($adminId)->earn($validatedData['amount'], $expireDate);

        return redirect()->route('admin.user.list');
    }
}
