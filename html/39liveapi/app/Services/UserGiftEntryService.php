<?php

namespace App\Services;

use App\Models\UserGiftEntry;

class UserGiftEntryService
{
  protected $utilService;

  public function __construct(UtilService $utilService)
  {
    $this->utilService = $utilService;
  }

  public function basicFilterBuilder($user_id)
  {
    $gift_statuses = config('const')['GIFT_STATUSES'];
    $now = date('Y-m-d H:i:s');
    return UserGiftEntry::with('gift')
      ->where('user_id', $user_id) // 持ち主である
      ->where('gift_status', $gift_statuses['未使用']) // 未使用である
      ->where('expiration_datetime', '>', $now) // 有効期限前である
      ->orderBy('expiration_datetime', 'asc'); // リミットが近い順
  }

  public function basicFilterBuilder_giftId($user_id, $gift_id)
  {
    return $this->basicFilterBuilder($user_id)->where('gift_id', $gift_id);
  }

  /**
   * UserGiftEntryをユーザid、未使用、有効期限前の条件で、有効期限の早い順に返す
   */
  public function getUserGiftEntryListWithBasicFilter($user_id, $per_page)
  {
    $perPage = $this->utilService->getPerPageCommonWrap($per_page);
    return $this->basicFilterBuilder($user_id)->paginate($perPage);
  }

  /**
   * UserGiftEntryをユーザid、未使用、有効期限前の条件で、有効期限の早い順に返す
   */
  public function getUserGiftEntryListWithBasicFilter_giftId($user_id, $gift_id, $per_page)
  {
    $perPage = $this->utilService->getPerPageCommonWrap($per_page);
    return $this->basicFilterBuilder_giftId($user_id, $gift_id)->paginate($perPage);
  }
}
