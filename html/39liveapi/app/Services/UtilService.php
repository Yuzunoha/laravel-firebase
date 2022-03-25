<?php

namespace App\Services;

use Illuminate\Http\Exceptions\HttpResponseException;

class UtilService
{
  public function getIp(): string
  {
    $keys = [
      'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
      'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR'
    ];
    foreach ($keys as $key) {
      if (array_key_exists($key, $_SERVER)) {
        foreach (explode(',', $_SERVER[$key]) as $ip) {
          $ip = trim($ip);
          if (filter_var(
            $ip,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
          )) {
            return $ip;
          }
        }
      }
    }
    return 'ip不明';
  }

  public function throwHttpResponseException($message, int $status = 400): void
  {
    $res = response()->json([
      'status' => $status,
      'message' => $message,
    ], $status);

    throw new HttpResponseException($res);
  }

  /**
   * per_pageを返却する
   * 
   * var_dump(intval(123)); // int(123)
   * var_dump(intval("123")); // int(123)
   * var_dump(intval("123abc")); // int(123)
   * var_dump(intval("abc123")); // int(0)
   * var_dump(intval(null)); // int(0)
   */
  public function getPerPageCommon($per_page, $max_value, $default_value)
  {
    $per_page = intval($per_page);
    $max_value = intval($max_value);
    $default_value = intval($default_value);

    if ($max_value < $per_page) {
      /* 最大値を超えていた */
      return $max_value;
    }

    if ($per_page <= 0) {
      /* 不正な値でint(0)と判定された */
      return $default_value;
    }

    /* 指定された値を返す */
    return $per_page;
  }

  public function getPerPageCommonWrap($per_page)
  {
    $const = config('const');
    return $this->getPerPageCommon($per_page, $const['PER_PAGE_MAX_COMMON'], $const['PER_PAGE_DEFAULT_COMMON']);
  }

  /**
   * 連想配列のキーのみの配列を取得する。除外指定もできる
   */
  public function extractKeyArray(array $targetArray, array $excludeKeyArray = [])
  {
    $ret = [];
    foreach ($targetArray as $key => $value) {
      if (in_array($key, $excludeKeyArray)) {
        /* 除外する */
        continue;
      }
      $ret[] = $key;
    }
    return $ret;
  }
}
