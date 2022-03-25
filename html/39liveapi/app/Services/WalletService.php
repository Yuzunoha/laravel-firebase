<?php

namespace App\Services;

use App\Models\CoinWallet;
use App\Models\PointToYenRequest;
use App\Models\PointWallet;
use App\Models\User;

class WalletService
{
  protected $utilService;
  protected $userGiftEntryService;

  public function __construct(
    UtilService $utilService,
    UserGiftEntryService $userGiftEntryService
  ) {
    $this->utilService = $utilService;
    $this->userGiftEntryService = $userGiftEntryService;
  }

  public function getCoinWalletByUserId($user_id)
  {
    $m = CoinWallet::where('user_id', $user_id)->first();
    if ($m) {
      return $m;
    }
    $this->utilService->throwHttpResponseException("user_id ${user_id} は存在しません。");
  }

  public function getPointWalletByUserId($user_id)
  {
    $m = PointWallet::where('user_id', $user_id)->first();
    if ($m) {
      return $m;
    }
    $this->utilService->throwHttpResponseException("user_id ${user_id} は存在しません。");
  }

  public function getUserGiftEntriesByUserId($user_id, $per_page)
  {
    // 存在しないuser_idなら空のリストを返す
    return $this->userGiftEntryService->getUserGiftEntryListWithBasicFilter($user_id, $per_page);
  }

  public function getUserGiftEntriesByUserIdAndGiftId($user_id, $gift_id, $per_page)
  {
    // 存在しなければ空のリストを返す
    return $this->userGiftEntryService->getUserGiftEntryListWithBasicFilter_giftId($user_id, $gift_id, $per_page);
  }

  public function presentOneGift($gift_id, $from_uid, $to_uid)
  {
    $gift_id = intval($gift_id);
    $from_uid = intval($from_uid);
    $to_uid = intval($to_uid);
    /* ユーザチェック */
    if ($from_uid === $to_uid) {
      $this->utilService->throwHttpResponseException("自分にプレゼントは出来ません。");
    }
    if (!User::find($from_uid)) {
      $this->utilService->throwHttpResponseException("user_id ${from_uid} は存在しません。");
    }
    if (!User::find($to_uid)) {
      $this->utilService->throwHttpResponseException("user_id ${to_uid} は存在しません。");
    }

    /* fromがgiftを所持しているかチェック */
    $userGiftEntryList = $this->userGiftEntryService->basicFilterBuilder_giftId($from_uid, $gift_id)->get();
    if (0 === count($userGiftEntryList)) {
      $this->utilService->throwHttpResponseException("gift_id ${gift_id} を保有しておりません。");
    }

    /* プレゼントするギフトのエントリ */
    $userGiftEntry = $userGiftEntryList[0];

    /* プレゼントされるユーザのポイントウォレット */
    $toUserPointWallet = PointWallet::where('user_id', $to_uid)->first();

    /* toのポイントを加算する */
    $point_amount_before = intval($toUserPointWallet['point_amount']); // プレゼントされるユーザの現在のポイント残高
    $gift_point = intval($userGiftEntry['gift']['point_amount']); // プレゼントするギフトのポイント
    $point_amount_after = $point_amount_before + $gift_point;
    $toUserPointWallet->update(['point_amount' => $point_amount_after]);

    /* fromのgiftを消費する(プレゼント履歴を記録する) */
    $gift_statuses = config('const')['GIFT_STATUSES'];
    $userGiftEntry->update([
      'gift_status' => $gift_statuses['プレゼント済'],
      'to_user_id' => $to_uid,
      'point_amount_base' => $gift_point,
      'point_amount_calculated' => $gift_point, // 倍率が掛かる予定である
    ]);
    return $userGiftEntry;
  }

  /**
   * intval('a')   // 0
   * intval('')    // 0
   * intval(null)  // 0
   * intval(123)   // 123
   * intval("123") // 123
   */
  public function createPointToYenRequest($uid, $requestPointAmount)
  {
    $minRequestPointAmount = 1000; // pointはこの量以上である必要がある
    $requestPointAmount = intval($requestPointAmount);
    if ($requestPointAmount < $minRequestPointAmount) {
      // 要求量が閾値を下回った
      $this->utilService->throwHttpResponseException(
        "${minRequestPointAmount} ポイント以上をリクエストしてください。今リクエストされたポイント: ${requestPointAmount}"
      );
    }

    // ポイントウォレットを取得する
    $pointWallet = PointWallet::find($uid);

    // 今リクエストされたポイントと、既にリクエスト中のポイントを合計する
    $newPointAmountBeingRequested = intval($pointWallet->point_amount_being_requested) + $requestPointAmount;

    // 合計後のポイント換金リクエスト量が、ポイント残高を上回ってしまうか判定する
    if (intval($pointWallet->point_amount) < $newPointAmountBeingRequested) {
      /* 残高が足りなかった */
      $this->utilService->throwHttpResponseException(
        "ポイント残高が不足しています(既にリクエスト中のポイントも考慮されます)。今リクエストされたポイント: ${requestPointAmount}"
      );
    }

    // 既にリクエスト中のポイントを更新する
    $pointWallet->point_amount_being_requested = $newPointAmountBeingRequested;
    $pointWallet->save();

    /* 換金リクエストを新規作成する(この関数内で残高は更新しない) */
    $pointToYenStatuses = config('const')['POINT_TO_YEN_STATUSES'];
    return PointToYenRequest::create([
      'user_id' => $uid,
      'point_amount' => $requestPointAmount,
      'yen_amount' => $requestPointAmount,
      'point_to_yen_status' => $pointToYenStatuses['リクエスト中'],
    ]);
  }
}
