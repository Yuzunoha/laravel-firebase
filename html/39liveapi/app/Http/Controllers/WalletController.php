<?php

namespace App\Http\Controllers;

use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
  protected $walletService;

  public function __construct(WalletService $walletService)
  {
    $this->walletService = $walletService;
  }

  public function getCoinWalletByUserId()
  {
    return $this->walletService->getCoinWalletByUserId(Auth::id());
  }

  public function getPointWalletByUserId()
  {
    return $this->walletService->getPointWalletByUserId(Auth::id());
  }

  public function getUserGiftEntriesByUserId(Request $request)
  {
    return $this->walletService->getUserGiftEntriesByUserId(Auth::id(), $request->per_page);
  }

  public function getUserGiftEntriesByUserIdAndGiftId(Request $request, $gift_id)
  {
    return $this->walletService->getUserGiftEntriesByUserIdAndGiftId(Auth::id(), $gift_id, $request->per_page);
  }

  public function presentOneGift($gift_id, $to_uid)
  {
    return $this->walletService->presentOneGift($gift_id, Auth::id(), $to_uid);
  }

  public function createPointToYenRequest(Request $request)
  {
    return $this->walletService->createPointToYenRequest(Auth::id(), $request->point_amount);
  }
}
