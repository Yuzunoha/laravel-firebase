<?php

namespace App\Services;

use App\Models\CoinWallet;
use App\Models\PointWallet;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
  protected $utilService;

  public function __construct(
    UtilService $utilService
  ) {
    $this->utilService = $utilService;
  }

  public function register(
    $nickname,
    $name,
    $email,
    $password
  ) {
    if (User::where('name', $name)->count()) {
      /* 重複した */
      $this->utilService->throwHttpResponseException("name ${name} は既に登録されています。");
    }
    if (User::where('email', $email)->count()) {
      /* 重複した */
      $this->utilService->throwHttpResponseException("email ${email} は既に登録されています。");
    }
    /* 作成して返却する */
    $const = config('const');
    $user = User::create([
      'nickname' => $nickname,
      'name'     => $name,
      'email'    => $email,
      'password' => Hash::make($password),
      'user_type'   => $const['USER_TYPES']['一般'],
      'user_status' => $const['USER_STATUSES']['未認証'],
      'auth_status' => $const['AUTH_STATUSES']['アクティブ'],
    ]);
    $user_id = $user->id;
    CoinWallet::create([
      'user_id' => $user_id,
      'coin_amount' => 0,
    ]);
    PointWallet::create([
      'user_id' => $user_id,
      'point_amount' => 0,
      'point_amount_being_requested' => 0,
    ]);
    return $this->getUserProfile($user_id);
  }

  public function login(
    $email,
    $password
  ) {
    $fnThrow = fn () => $this->utilService->throwHttpResponseException('emailとpasswordの組み合わせが不正です。');
    $user = User::where('email', $email)->first();

    if (!$user) {
      /* emailが存在しなかった */
      $fnThrow();
    }
    if (!Hash::check($password, $user->password)) {
      /* emailとpasswordが一致しなかった */
      $fnThrow();
    }

    /* 1ユーザにつき有効なトークンは1つだけにする */
    $user->tokens()->delete();

    /* トークンを発行する */
    $token = $user->createToken('token-name');

    /* トークンを返却する */
    return [
      'token' => $token->plainTextToken,
    ];
  }

  public function logout($loginUser)
  {
    /* 有効なトークンを全て削除する */
    $loginUser->tokens()->delete();
    return [
      'message' => 'ログアウトしました。既存のトークンは失効しました。',
    ];
  }

  public function getUserProfile(
    $userId
  ) {
    return User::find($userId);
  }

  public function postIdentificationPaper($userId, $file)
  {
    $ext = $file->getClientOriginalExtension(); // jpg, pdf, ...
    $fileName = $userId . '_' . date('Ymd_His') . '.' . $ext; // 4_20211116_011951.jpg
    $file->storeAs('identificationPapers', $fileName);
    return ['message' => 'アップロード成功'];
  }
}
