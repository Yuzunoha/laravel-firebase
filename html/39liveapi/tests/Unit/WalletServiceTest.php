<?php

namespace Tests\Unit;

use App\Models\CoinWallet;
use App\Models\Gift;
use App\Models\PointWallet;
use App\Models\User;
use App\Models\UserGiftEntry;
use App\Services\UserGiftEntryService;
use App\Services\UserService;
use App\Services\UtilService;
use App\Services\WalletService;
use Database\Seeders\GiftSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\HttpResponseException;
use Tests\TestCase;

class WalletServiceTest extends TestCase
{
  use RefreshDatabase;

  protected $walletService;
  protected $userGiftEntryService;
  protected $userService;
  protected $const;

  public function setUp(): void
  {
    parent::setUp();

    $utilService = new UtilService;
    $this->userGiftEntryService = new UserGiftEntryService($utilService);
    $this->walletService = new WalletService($utilService, $this->userGiftEntryService);
    $this->userService = new UserService($utilService);

    $this->const = config('const');
  }

  public function test_getCoinWalletByUserId_正常系()
  {
    $user_id = 123;
    $coin_amount = 1234;
    CoinWallet::create([
      'user_id' => $user_id,
      'coin_amount' => $coin_amount,
    ]);
    $actual = $this->walletService->getCoinWalletByUserId($user_id)->toArray();
    $now = date('Y-m-d H:i:s');
    $e['user_id'] = $user_id;
    $e['coin_amount'] = $coin_amount;
    $e['created_at'] = $now;
    $e['updated_at'] = $now;
    $this->assertEquals($e, $actual);
  }

  public function test_getCoinWalletByUserId_異常系_ユーザが存在しない()
  {
    $user_id = 123;
    try {
      $this->walletService->getCoinWalletByUserId($user_id);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "user_id {$user_id} は存在しません。",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_getPointWalletByUserId_正常系()
  {
    $user_id = 234;
    $point_amount = 2345;
    $point_amount_being_requested = 4567;
    PointWallet::create([
      'user_id' => $user_id,
      'point_amount' => $point_amount,
      'point_amount_being_requested' => $point_amount_being_requested,
    ]);
    $actual = $this->walletService->getPointWalletByUserId($user_id)->toArray();
    $now = date('Y-m-d H:i:s');
    $e['user_id'] = $user_id;
    $e['point_amount'] = $point_amount;
    $e['point_amount_being_requested'] = $point_amount_being_requested;
    $e['created_at'] = $now;
    $e['updated_at'] = $now;
    $this->assertEquals($e, $actual);
  }

  public function test_getPointWalletByUserId_異常系_ユーザが存在しない()
  {
    $user_id = 123;
    try {
      $this->walletService->getPointWalletByUserId($user_id);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "user_id {$user_id} は存在しません。",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_getUserGiftEntriesByUserId_正常系()
  {
    $gift_statuses = config('const')['GIFT_STATUSES'];
    (new GiftSeeder())->run();
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 1,
      'gift_status' => $gift_statuses["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => 2,
      'gift_id' => 1,
      'gift_status' => $gift_statuses["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 2,
      'gift_status' => $gift_statuses["プレゼント済"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 2,
      'gift_status' => $gift_statuses["未使用"],
      'expiration_datetime' => '1990-12-31 23:59:59',
    ]);
    $now = date('Y-m-d H:i:s');
    $user_id = 1;
    $actual = $this->walletService->getUserGiftEntriesByUserId($user_id, 1)->toArray()['data'];
    $expected = UserGiftEntry::with(['gift'])
      ->where('expiration_datetime', '>', $now)
      ->where('gift_status', $gift_statuses['未使用'])
      ->where('user_id', $user_id)
      ->get()->toArray();
    $this->assertEquals($expected, $actual);
  }

  public function test_getUserGiftEntriesByUserIdAndGiftId_正常系()
  {
    $gift_statuses = config('const')['GIFT_STATUSES'];
    (new GiftSeeder())->run();
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 1,
      'gift_status' => $gift_statuses["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 1,
      'gift_status' => $gift_statuses["プレゼント済"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => 2,
      'gift_id' => 1,
      'gift_status' => $gift_statuses["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 2,
      'gift_status' => $gift_statuses["プレゼント済"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 2,
      'gift_status' => $gift_statuses["未使用"],
      'expiration_datetime' => '1990-12-31 23:59:59',
    ]);
    $now = date('Y-m-d H:i:s');
    $user_id = 1;
    $gift_id = 1;
    $actual = $this->walletService->getUserGiftEntriesByUserIdAndGiftId($user_id, $gift_id, 1)->toArray()['data'];
    $expected = UserGiftEntry::with(['gift'])
      ->where('expiration_datetime', '>', $now)
      ->where('gift_status', $gift_statuses['未使用'])
      ->where('user_id', $user_id)
      ->where('gift_id', $gift_id)
      ->get()->toArray();
    $this->assertEquals($expected, $actual);
  }

  public function test_getUserGiftEntriesByUserIdAndGiftId_異常系_存在しないアイテム()
  {
    $gift_statuses = config('const')['GIFT_STATUSES'];
    (new GiftSeeder())->run();
    UserGiftEntry::create([
      'user_id' => 1,
      'gift_id' => 1,
      'gift_status' => $gift_statuses["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    $user_id = 1;
    $gift_id = 123;
    $actual = $this->walletService->getUserGiftEntriesByUserIdAndGiftId($user_id, $gift_id, 1)->toArray()['data'];
    $expected = [];
    $this->assertEquals($expected, $actual);
  }

  public function test_getUserGiftEntriesByUserId_異常系_存在しないユーザ()
  {
    $actual = $this->walletService->getUserGiftEntriesByUserId(123, 1)->toArray()['data'];
    $expected = [];
    $this->assertEquals($expected, $actual);
  }

  public function test_presentOneGift_正常系()
  {
    // シーディング
    (new GiftSeeder())->run();

    // ユーザを作る
    $userA = $this->userService->register('nickname_a', 'name_a', 'email_a', 'pass_a');
    $userB = $this->userService->register('nickname_b', 'name_b', 'email_b', 'pass_b');

    // 変数
    $gift_id = 5;

    // ギフトを登録する
    UserGiftEntry::create([
      'user_id' => $userA->id,
      'gift_id' => $gift_id,
      'gift_status' => $this->const['GIFT_STATUSES']["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    UserGiftEntry::create([
      'user_id' => $userA->id,
      'gift_id' => $gift_id,
      'gift_status' => $this->const['GIFT_STATUSES']["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:57',
    ]);
    UserGiftEntry::create([
      'user_id' => $userA->id,
      'gift_id' => $gift_id,
      'gift_status' => $this->const['GIFT_STATUSES']["未使用"],
      'expiration_datetime' => '9999-12-31 23:59:58',
    ]);

    // プレゼントを実行する
    $this->walletService->presentOneGift($gift_id, $userA->id, $userB->id);

    // プレゼントしたギフトを取得する
    $gift = Gift::find($gift_id)->toArray();

    // プレゼント後のエントリを取得する
    $entry = UserGiftEntry::find(2)->toArray();

    // プレゼント後のエントリの検証
    $this->assertEquals('プレゼント済', $entry['gift_status']);
    $this->assertEquals($userB->id, $entry['to_user_id']);
    $this->assertEquals($gift['point_amount'], $entry['point_amount_base']);
    $this->assertEquals($gift['point_amount'], $entry['point_amount_calculated']);

    // プレゼント先のポイント量の増分が、プレゼントしたポイント量と等しい事
    $this->assertEquals($entry['point_amount_calculated'], PointWallet::find($userB->id)->point_amount);

    // 有効期限が最も近いギフトが消費されること
    $this->assertEquals('未使用', UserGiftEntry::find(1)->toArray()['gift_status']);
    $this->assertEquals('プレゼント済', UserGiftEntry::find(2)->toArray()['gift_status']);
    $this->assertEquals('未使用', UserGiftEntry::find(3)->toArray()['gift_status']);
  }

  public function test_presentOneGift_異常系_同一ユーザ()
  {
    try {
      $this->walletService->presentOneGift(1, 1, 1);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "自分にプレゼントは出来ません。",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_presentOneGift_異常系_送り元が存在しない()
  {
    $from_uid = 1;
    try {
      $this->walletService->presentOneGift(1, $from_uid, 2);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "user_id ${from_uid} は存在しません。",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_presentOneGift_異常系_送り先が存在しない()
  {
    $userA = $this->userService->register('nickname_a', 'name_a', 'email_a', 'pass_a');
    $to_uid = 2;
    try {
      $this->walletService->presentOneGift(1, $userA->id, $to_uid);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "user_id ${to_uid} は存在しません。",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_presentOneGift_異常系_fromがgiftを所持していない()
  {
    $userA = $this->userService->register('nickname_a', 'name_a', 'email_a', 'pass_a');
    $userB = $this->userService->register('nickname_b', 'name_b', 'email_b', 'pass_b');
    $gift_id = 1;
    // プレゼント済みのギフト
    UserGiftEntry::create([
      'user_id' => $userA->id,
      'gift_id' => $gift_id,
      'gift_status' => $this->const['GIFT_STATUSES']["プレゼント済"],
      'expiration_datetime' => '9999-12-31 23:59:59',
    ]);
    // 有効期限が切れたギフト
    UserGiftEntry::create([
      'user_id' => $userA->id,
      'gift_id' => $gift_id,
      'gift_status' => $this->const['GIFT_STATUSES']["未使用"],
      'expiration_datetime' => '1999-12-31 23:59:57',
    ]);
    try {
      $this->walletService->presentOneGift($gift_id, $userA->id, $userB->id);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "gift_id ${gift_id} を保有しておりません。",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_createPointToYenRequest_正常系()
  {
    $uid = 1;
    $requestPointAmount = 2000;
    $oldPointAmount = 5000;
    $oldPointAmountBeingRequested = 1500;
    PointWallet::create([
      'user_id' => $uid,
      'point_amount' => $oldPointAmount,
      'point_amount_being_requested' => $oldPointAmountBeingRequested,
    ]);
    $now = date('Y-m-d H:i:s');
    $e = [
      'id' => 1,
      'user_id' => $uid,
      'point_amount' => $requestPointAmount,
      'yen_amount' => $requestPointAmount,
      'point_to_yen_status' => 'リクエスト中',
      'created_at' => $now,
      'updated_at' => $now,
    ];
    $actual = $this->walletService->createPointToYenRequest($uid, $requestPointAmount)->toArray();
    $this->assertEquals($e, $actual);

    /* point_amount_being_requested が更新されること */
    $newPointAmountBeingRequested = $oldPointAmountBeingRequested + $requestPointAmount;
    $actualPointWallet = PointWallet::find($uid)->toArray();
    $expectedPointWallet = [
      'user_id' => $uid,
      'point_amount' => strval($oldPointAmount),
      'point_amount_being_requested' => strval($newPointAmountBeingRequested),
      'created_at' => $now,
      'updated_at' => $now,
    ];
    $this->assertEquals($expectedPointWallet, $actualPointWallet);
  }

  public function test_createPointToYenRequest_異常系_リクエストが閾値を下回った1()
  {
    try {
      $this->walletService->createPointToYenRequest(null, 'a');
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "1000 ポイント以上をリクエストしてください。今リクエストされたポイント: 0",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_createPointToYenRequest_異常系_リクエストが閾値を下回った2()
  {
    $requestPointAmount = 999;
    try {
      $this->walletService->createPointToYenRequest(null, $requestPointAmount);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "1000 ポイント以上をリクエストしてください。今リクエストされたポイント: ${requestPointAmount}",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }

  public function test_createPointToYenRequest_異常系_ポイント残高が不足していた()
  {
    $uid = 1;
    $requestPointAmount = 2000;
    PointWallet::create([
      'user_id' => $uid,
      'point_amount' => 5000,
      'point_amount_being_requested' => 1500,
    ]);
    $this->walletService->createPointToYenRequest($uid, $requestPointAmount);
    try {
      $this->walletService->createPointToYenRequest($uid, $requestPointAmount);
    } catch (HttpResponseException $e) {
      $expected = json_encode([
        'status' => 400,
        'message' => "ポイント残高が不足しています(既にリクエスト中のポイントも考慮されます)。今リクエストされたポイント: ${requestPointAmount}",
      ]);
      $actual = json_encode($e->getResponse()->original);
      $this->assertEquals($expected, $actual);
    }
  }
}
