<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestLiveStart;
use App\Services\LiveService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveController extends Controller
{
    protected $liveService;

    public function __construct(
        LiveService $liveService
    ) {
        $this->liveService = $liveService;
    }

    public function getLive(Request $request)
    {
        return $this->liveService->getLive(
            $request->per_page,
            $request->keyword,
            $request->tid_csv,
            $request->id_csv,
            $request->now_on_air
        );
    }

    public function getLiveById(Request $request)
    {
        return $this->liveService->getLiveById(
            $request->id
        );
    }

    public function startLive(RequestLiveStart $request)
    {
        return $this->liveService->startLive(
            Auth::id(),
            $request->name,
            $request->tid_csv
        );
    }
}
