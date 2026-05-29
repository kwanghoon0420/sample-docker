<?php
/**
 * 사용자 포인트 도메인 모델
 */
namespace App\Domains\Entities;

use App\Models\PointDetail;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Point as PointModel;
use App\Exceptions\DomainException;
use App\Support\Helper;
use App\Models\PointChangedLog;

class UserPointEntity
{
    use PointMethods;

    private PointModel $pointModel;

    private int $adminId;

    public function __construct(private User $user)
    {
        if($user->exists == false) {
            throw new DomainException('사용자를 찾을 수 없습니다.');
        }

        $this->pointModel = $this->initPointModel();

    }

    // 사용자 포인트 모델 초기화
    private function initPointModel(): PointModel
    {
        $piontModel = $this->user->point;
        if(!$piontModel) {
            $piontModel = new PointModel();
            $piontModel->user_id = $this->user->id;
            $piontModel->remain_amount = 0;
            $piontModel->status = 'a';
            $piontModel->save();
        }
        return $piontModel;
    }

    public function setAdminId(int $adminId): static
    {
        $this->adminId = $adminId;

        return $this;
    }

    public function getAdminId(): int
    {
        return $this->adminId ?? 0;
    }

    public function userModel(): User
    {
        return $this->user;
    }

    /**
     * 사용자 포인트 사용 가능성 확인
     */
    public function checkPointAvailable(): void
    {
        // 포인트 사용이 가능한 상태인지 (예: 휴면계정, 탈퇴계정 등은 포인트 사용 불가)
        // if ($this->user->status !== 'a') {
        //     throw new DomainException('포인트를 사용할 수 없는 사용자 상태입니다.');
        // }

        if(empty($this->pointModel) || $this->pointModel->remain_amount == 0) {
            throw new DomainException('포인트가 충전이 필요합니다.');
        }
        if($this->pointModel->status == 'l') {
            throw new DomainException('포인트가 잠금 처리되어 사용할 수 없습니다.');
        }
    }

    /**
     * 포인트 잔여금액 조회
     */
    public function getRemainPoints(): int
    {
        return (int) $this->pointModel->remain_amount ?? 0;
    }

    /**
     * 포인트 적립  
     */
    public function earn(string $amount, Carbon $expireDate, string $referenceId = ''): void
    {
        // $this->checkPointAvailable();

        if($referenceId == '') {
            $referenceId = 'E' . Helper::generateOrderNo();
        }
        // 유효날짜는 그 날 23:59:59 까지로 설정
        $expireDate = $expireDate->endOfDay();

        $this->add($this->userModel()->id, $amount, $referenceId, 'e', $expireDate, $this->getAdminId());

        $this->pointModel->refresh();
    }

    /**
     * 포인트 사용 (사용 중 처리)
     * @param int $amount
     * @param string $referenceId 포인트 사용의 근거가 되는 주문 번호 등 참조 ID
     * @return array
     */
    public function use(int $amount, string $referenceId): array
    {
        $this->checkPointAvailable();

        // 가능한 포인트 상세 내역을 조회한다. (유효기간이 남아있고, 사용되지 않은 내역, 일찍 충전된 순으로)
        $pointDetailForSub = PointDetail::where('user_id', $this->userModel()->id)
            ->where('used_flag', 'n')
            ->where('remain_amount', '>', 0)
            ->where('expire_at', '>', Carbon::now())
            ->orderBy('created_at', 'asc')
            ->get();

        $result = $this->subtract($this->userModel()->id, $amount, $referenceId, 'u', collect($pointDetailForSub), $this->getAdminId());

        // 포인트 차감 후 point 테이블에서 차감한 금액만큼 사용중으로 만든다.
        // 없으면 생성, 있으면 차감한 금액만큼 증가시킨다.
        $point = PointModel::query()
            ->where('user_id', '=', $this->userModel()->id)
            ->where('status', '=', 'i')
            ->lockForUpdate()
            ->first();
        if (!$point) {
            $point = new PointModel();
            $point->user_id = $this->userModel()->id;
            $point->remain_amount = $amount;
            $point->status = 'i';
            $point->save();
        } else {
            $point->remain_amount = bcadd((string) $point->remain_amount, $amount);
            $point->save();
        }

        $this->pointModel->refresh();

        return $result;
    }

    /**
     * 사용 확정 (사용 완료 처리)
     * @return void
     */
    public function confirm(string $referenceId)
    {
        $pointChangedLog = PointChangedLog::where('reference_id', '=', $referenceId)
            ->where('user_id', '=', $this->userModel()->id)
            ->where('type', '=', 'u')
            ->first();
        if(!$pointChangedLog) {
            throw new DomainException('포인트 변동 내역을 찾을 수 없습니다.', ['reference_id' => $referenceId, 'user_id' => $this->userModel()->id]);
        }

        // 사용 중 포인트내역에서 사용한 만큼 차감처리
        $affectedRows = PointModel::query()
            ->where('user_id', $this->userModel()->id)
            ->where('status', '=', 'i')
            ->decrement('remain_amount', $pointChangedLog->changed_amount);
        if($affectedRows === 0) {
            throw new DomainException('사용 중인 포인트가 없습니다.', ['reference_id' => $referenceId, 'user_id' => $this->userModel()->id]);
        }

        $this->pointModel->refresh();
    }

    /**
     * 포인트 환불 (사용 완료된 포인트를 환불 처리)
     * @param int $changedLogId 포인트 환불의 근거가 되는 포인트 변동내역 ID
     * @return void
     */
    public function refund(int $changedLogId): void
    {
        $pointChangedLog = PointChangedLog::where('id', $changedLogId)->first();
        if(!$pointChangedLog) {
            throw new DomainException('포인트 변동 내역을 찾을 수 없습니다.');
        }

        $newChangedLogId = $this->changedLog(
            $this->userModel()->id, 
            $pointChangedLog->after_amount, 
            $pointChangedLog->changed_amount, 
            $pointChangedLog->before_amount, 
            $pointChangedLog->reference_id, 
            'r',
            $this->getAdminId()
        );

        // 사용 중인 포인트를 차감하고
        $affectedRows = PointModel::query()
            ->where('user_id', $this->userModel()->id)
            ->where('status', '=', 'i')
            ->decrement('remain_amount', $pointChangedLog->changed_amount);
        if($affectedRows === 0) {
            throw new DomainException('포인트 환불 처리 중 오류가 발생했습니다.', ['changed_log_id' => $changedLogId, 'user_id' => $this->userModel()->id]);
        }
        // 액티브 포인트를 그만큼 증가
        $affectedRows = PointModel::query()
            ->where('user_id', $this->userModel()->id)
            ->where('status', '=', 'a')
            ->increment('remain_amount', $pointChangedLog->changed_amount);
        if($affectedRows === 0) {
            throw new DomainException('포인트 환불 처리 중 오류가 발생했습니다.', ['changed_log_id' => $changedLogId, 'user_id' => $this->userModel()->id]);
        }

        // 사용했던 포인트 상세내역에 그대로 원복 처리
        foreach($pointChangedLog->pointDetailChangedLogs as $pointDetailChangedLog) {
            $pointDetail = PointDetail::where('id', $pointDetailChangedLog->point_detail_id)->first();
            if(!$pointDetail) {
                throw new DomainException('포인트 상세 내역을 찾을 수 없습니다.');
            }
            $pointDetail->used_amount = bcsub((string) $pointDetail->used_amount, (string) $pointDetailChangedLog->changed_amount);
            $pointDetail->remain_amount = bcadd((string) $pointDetail->remain_amount, (string) $pointDetailChangedLog->changed_amount);
            $pointDetail->used_flag = 'n';
            $pointDetail->save();

            // row 별로 포인트 상세내역 변동 로그 저장
            $this->detailChangedLog($this->userModel()->id, $newChangedLogId, $pointDetailChangedLog->point_detail_id, $pointDetailChangedLog->changed_amount, 'r');

            // 포인트 원복 후 해당 내역이 만기면 만기처리함
            $this->expire($pointDetailChangedLog->point_detail_id);
        }

        $this->pointModel->refresh();
    }

    /**
     * 포인트 상세 내역 만기된 경우 처리, 포인트 금액 모두 차감처리
     */
    public function expire(int $pointDetailId): void
    {
        $pointDetail = PointDetail::where('id', $pointDetailId)->first();
        if(!$pointDetail) {
            throw new DomainException('포인트 상세 내역을 찾을 수 없습니다.');
        }

        if($pointDetail->expire_at < Carbon::now()) {
            $this->subtract($this->userModel()->id, $pointDetail->remain_amount, 'expire', 'x', collect([$pointDetail]), $this->getAdminId());
        }

        $this->pointModel->refresh();
    }
}