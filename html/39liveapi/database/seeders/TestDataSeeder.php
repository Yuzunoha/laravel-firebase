<?php

namespace Database\Seeders;

use App\Models\CoinWallet;
use App\Models\PointWallet;
use App\Models\User;
use App\Models\UserGiftEntry;
use App\Services\UserService;
use App\Services\UtilService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestDataSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $const = config('const');

    /* 管理者ユーザ(id=1)を作成する */
    $userService = new UserService(new UtilService);
    $password = "e58fa5bf6d15@af";
    $userA = $userService->register('nickname_a', 'name_a', 'admin@email', $password);
    User::find($userA->id)->update([
      'user_type'   => $const['USER_TYPES']['管理者'],
      'user_status' => $const['USER_STATUSES']['管理者'],
      'auth_status' => $const['AUTH_STATUSES']['管理者'],
    ]);

    /* 管理者ユーザ(id=1)にギフトを持たせる */
    $gift_statuses = $const['GIFT_STATUSES'];
    for ($i = 1; $i <= 11; $i++) {
      $giftData = [
        'user_id' => $userA->id,
        'gift_id' => $i,
        'gift_status' => $gift_statuses["未使用"],
        'expiration_datetime' => '9999-12-31 23:59:59',
      ];
      UserGiftEntry::create($giftData);
      UserGiftEntry::create($giftData);
      UserGiftEntry::create($giftData);
    }

    /* 管理者ユーザ(id=1)にポイントを持たせる */
    PointWallet::find($userA->id)->update(['point_amount' => 10000]);

    /* 管理者ユーザ(id=1)にコインを持たせる */
    CoinWallet::find($userA->id)->update(['coin_amount' => 42500]);
  }
}
