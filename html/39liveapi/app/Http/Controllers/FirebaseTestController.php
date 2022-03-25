<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
// use Kreait\Firebase\Auth;
use Kreait\Firebase\Contract\Auth;

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
    print_r([$anonymous->idToken(), $anonymous->firebaseUserId()]);

    return view('welcome');
  }
}
