<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Kreait\Firebase\Auth;

class FirebaseTestController extends Controller
{
  private $auth;

  public function __construct(Auth $auth)
  {
    $this->auth = $auth;
  }

  public function loginAnonymous()
  {
    $anonymous = $this->auth->signInAnonymously();
  }
}
