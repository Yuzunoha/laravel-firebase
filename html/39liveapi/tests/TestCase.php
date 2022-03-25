<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
  use CreatesApplication;

  public function p($a = '')
  {
    print_r(PHP_EOL);
    print_r($a);
    print_r(PHP_EOL);
  }
}
