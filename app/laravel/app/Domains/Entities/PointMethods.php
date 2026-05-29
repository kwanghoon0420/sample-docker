<?php
/**
 * 포인트 정산 최하층 로직 코어 모델
 */
namespace App\Domains\Entities;

use App\Dtos\Point as PointDto;
use App\Models\Point as PointModel;
use App\Models\PointChangedLog;
use App\Models\PointDetail;
use App\Models\PointDetailChangedLog;
use Illuminate\Support\Collection;
use Carbon\Carbon;

trait PointMethods
{
    /**
     * 포인트를 적립하고 사용자 총 포인트와 상세 내역을 함께 갱신한다.
     */
    private function add(string $userId, string $amount, string $referenceId, string $type, Carbon $expireAt, int $adminId = null): void
    {
        
        $point = PointModel::query()
            ->where('user_id', '=', $userId)
            ->where('status', '=', 'a') // 정상인 포인트
            ->orWhere('status', '=', 'l') // 잠금 상태의 포인트도 포함
            ->lockForUpdate()
            ->first();
        if (!$point) {
            $beforeAmount = '0.00';
            $afterAmount = $amount;
            $point = new PointModel();
            $point->user_id = $userId;
            $point->remain_amount = $afterAmount;
            $point->save();
        } else {
            // 포인트 변동 로그 저장
            $beforeAmount = $point->remain_amount;
            $afterAmount = bcadd((string) $point->remain_amount, $amount);
            $point->remain_amount = $afterAmount;
            $point->save();
        }

        // 포인트 변동 로그 저장
        $this->changedLog($userId, $beforeAmount, $amount, $afterAmount, $referenceId, $type, $adminId);

        $pointDetail = new PointDetail();
        $pointDetail->user_id = $userId;
        $pointDetail->origin_amount = $amount;
        $pointDetail->used_amount = '0.00';
        $pointDetail->remain_amount = $amount;
        $pointDetail->used_flag = 'n';
        $pointDetail->expire_at = $expireAt;
        $pointDetail->save();
    }

    /**
     * 포인트를 차감하고 상세 차감 로그를 남긴다.
     * @param string $userId 사용자 id
     * @param string $amount 차감할 포인트 금액
     * @param string $referenceId 차감 참조 id
     * @param string $type 차감 유형
     * @param Collection $pointDetailForSub 차감할 포인트 상세 내역을 전달받는다.(포인트를 차감하는 여러 상황에 쓰이기 위함.ex: 포인트 사용, 포인트 만료 등)
     * @return array
     */
    private function subtract(string $userId, string $amount, string $referenceId, string $type, Collection $pointDetailForSub, int $adminId = null): array
    {
        $point = PointModel::query()
            ->where('user_id', '=', $userId)
            ->where('status', '=', 'a')
            ->lockForUpdate()
            ->first();
        if (!$point) {
            return ['status'=> false, 'message'=> 'Point not found', 'changed_log_id' => null];
        }
        
        // 차감 후 잔여 포인트 금액 계산
        $remainAmount = bcsub((string) $point->remain_amount, $amount);
        if (bccomp($remainAmount, '0.00', 2) < 0) {
            return ['status'=> false, 'message'=> 'Point balance is not enough', 'changed_log_id' => null];
        }
        
        // 포인트 변동 로그 저장
        $changedLogId = $this->changedLog($userId, $point->remain_amount, $amount, $remainAmount, $referenceId, $type, $adminId);

        // 포인트 잔여 금액 업데이트
        $point->remain_amount = $remainAmount;
        $point->save();

        // 차감할 포인트 금액 초기화
        $amountToSub = $amount;
        
        // 차감할 포인트 상세 내역을 순회하면서 포인트를 차감한다.
        foreach ($pointDetailForSub as $row) {
            $currentSubAmount = '0.00'; // 현재 row 에서 차감된 포인트 금액 (detail log 에 기록하기 위함)
            $pointDetail = PointDetail::whereKey($row->id)->lockForUpdate()->first(); // 엘로퀀트 모델 반환

            // 잔여 포인트가 차감할 포인트보다 많거나 같은 경우
            if (bccomp((string) $row->remain_amount, $amountToSub, 2) >= 0) {
                $pointDetail->used_amount = bcadd((string) $row->used_amount, $amountToSub);
                $pointDetail->remain_amount = bcsub((string) $row->remain_amount, $amountToSub);
                // 차감 결과로 잔액이 0이 되면 사용 완료 처리
                if (bccomp((string) $pointDetail->remain_amount, '0.00', 2) === 0) {
                    $pointDetail->used_flag = 'y';
                }
                $currentSubAmount = $amountToSub;
                $amountToSub = '0.00';
            }
            // 잔여 포인트가 차감할 포인트보다 작은 경우 
            else {
                $currentSubAmount = (string) $row->remain_amount;
                $pointDetail->used_amount = bcadd((string) $row->used_amount, $currentSubAmount);
                $pointDetail->remain_amount = '0.00';
                $pointDetail->used_flag = 'y';
                $amountToSub = bcsub($amountToSub, $currentSubAmount);
            }

            $pointDetail->save();

            $this->detailChangedLog($userId, $changedLogId, $row->id, $currentSubAmount, $type);

            // 차감할 금액이 0이 된 경우 루프를 종료한다.
            if (bccomp($amountToSub, '0.00', 2) === 0) {
                break;
            }
        }

        // 차감 후에도 차감할 금액이 남아있는 경우는 잔여 포인트가 부족한 경우이므로 예외를 던진다.
        if (bccomp($amountToSub, '0.00', 2) > 0) {
            return ['status'=> false, 'message'=> 'Point balance is not enough', 'changed_log_id' => null];
        }

        return ['status' => true, 'changed_log_id' => $changedLogId];
    }

    /**
     * 포인트 변동 로그를 저장한다.
     */
    public function changedLog(string $userId, string $beforeAmount, string $changedAmount, string $afterAmount, string $referenceId, string $type, int $adminId): int
    {
        $pointChangedLog = new PointChangedLog();
        $pointChangedLog->user_id = $userId;
        $pointChangedLog->reference_id = $referenceId;
        $pointChangedLog->before_amount = $beforeAmount;
        $pointChangedLog->changed_amount = $changedAmount;
        $pointChangedLog->after_amount = $afterAmount;
        $pointChangedLog->type = $type;
        $pointChangedLog->admin_id = $adminId;
        $pointChangedLog->save();

        return $pointChangedLog->id;
    }

    /**
     * 포인트 상세 변동 로그를 저장한다.
     */
    public function detailChangedLog(string $userId, int $changedLogId, int $detailId, string $amount, string $type): int
    {
        $pointDetailChangedLog = new PointDetailChangedLog();
        $pointDetailChangedLog->user_id = $userId;
        $pointDetailChangedLog->point_changed_log_id = $changedLogId;
        $pointDetailChangedLog->point_detail_id = $detailId;
        $pointDetailChangedLog->type = $type;
        $pointDetailChangedLog->changed_amount = $amount;
        $pointDetailChangedLog->save();
        
        return $pointDetailChangedLog->id;
    }

}
