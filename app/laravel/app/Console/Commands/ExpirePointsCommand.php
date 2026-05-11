<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Dtos\Point as PointDto;
use App\Models\PointDetail;
use Carbon\Carbon;
use App\Domains\Entities\UserPointEntity;
use App\Models\User;

class ExpirePointsCommand extends Command
{
    protected $signature = 'points:expire';

    protected $description = '포인트 상세내역 만기 처리';

    public function handle(): int
    {
        $pointDetails = PointDetail::where('used_flag', 'n')
            ->where('remain_amount', '>', 0)
            ->where('expire_at', '<', Carbon::now())
            ->get();

        foreach($pointDetails as $pointDetail) {
            try {
                $userPointEntity = new UserPointEntity(User::find($pointDetail->user_id));
                $userPointEntity->expire($pointDetail->id);
            } catch(\Exception $e) {
                Log::error('포인트 상세내역 만기 처리 중 오류가 발생했습니다. : ' . $e->getMessage(), ['point_detail_id' => $pointDetail->id, 'user_id' => $pointDetail->user_id]);
                continue;
            }
        }

        return self::SUCCESS;
    }
}
