<?php

namespace App\Http\Controllers;

use App\Http\Requests\RequestLogin;
use App\Http\Requests\RequestPostIdentificationPaper;
use App\Http\Requests\RequestRegister;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    protected $userService;

    public function __construct(
        UserService $userService
    ) {
        $this->userService = $userService;
    }

    public function register(RequestRegister $request)
    {
        return $this->userService->register(
            $request->nickname,
            $request->name,
            $request->email,
            $request->password
        );
    }

    public function login(RequestLogin $request)
    {
        return $this->userService->login(
            $request->email,
            $request->password
        );
    }

    public function logout()
    {
        return $this->userService->logout(
            Auth::user()
        );
    }

    public function getUserProfile()
    {
        return $this->userService->getUserProfile(
            Auth::id()
        );
    }

    public function postIdentificationPaper(RequestPostIdentificationPaper $request)
    {
        return $this->userService->postIdentificationPaper(
            Auth::id(),
            $request->file('identificationPaper')
        );
    }
}
