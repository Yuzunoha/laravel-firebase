<?php

namespace App\Services;

use App\Models\Live;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class LiveService
{
  protected $utilService;

  public function __construct(
    UtilService $utilService
  ) {
    $this->utilService = $utilService;
  }

  public function getPerPage($input)
  {
    $const = config('const');
    if ($input && ctype_digit($input)) {
      /* inputがあり、かつそれが十進数である */
      $per_page = intval($input);
      if ($const['PER_PAGE_MAX_LIVE'] < $per_page) {
        /* per_pageの上限を超えている */
        $per_page = $const['PER_PAGE_MAX_LIVE'];
      }
    } else {
      /* inputがない、またはあっても十進数でない */
      $per_page = $const['PER_PAGE_DEFAULT_LIVE'];;
    }
    return $per_page;
  }

  public function getLive($per_page, $keyword, $tid_csv, $id_csv, $now_on_air)
  {
    $builder = Live::with('user');

    $now = date('Y-m-d H:i:s');
    $str_finished_at = 'finished_at';
    if ('1' === $now_on_air) {
      /* 放送中のライブ限定 */
      $builder->where(function ($builder) use ($str_finished_at, $now) {
        $builder->whereNull($str_finished_at)->orWhere($str_finished_at, '>', $now);
      });
    } else if ('0' === $now_on_air) {
      /* 放送していないライブ限定 */
      $builder = $builder->where($str_finished_at, '<=', $now);
    } else {
      /* 限定無し */
    }

    if ($keyword) {
      /* キーワード検索 */
      $builder = $builder->where('name', 'LIKE', '%' . $keyword . '%');
    }

    if ($id_csv) {
      /* id検索 */
      $idArray = array_filter(explode(',', $id_csv), 'strlen');
      $builder = $builder->whereIn('id', $idArray);
    };

    if ($tid_csv) {
      /* タグ検索 */
      /*
      +----+----------+
      | id | name     |
      +----+----------+
      |  1 | 1,2,3    |
      |  3 | 1,23,3,4 |
      |  4 | 1,2,3,5  |
      |  5 | 1,2,6,3  |
      |  2 | 1,33     |
      +----+----------+

      # 先頭と末尾にカンマ(,)を付ければ、単純なlike検索で書ける。
      select * from names where concat(',', name, ',') like '%,3,%'
       */
      $tidArray = array_filter(explode(',', $tid_csv), 'strlen');
      $builder->where(function ($builder) use ($tidArray) {
        foreach ($tidArray as $tidElement) {
          $builder->orWhere(DB::raw('CONCAT(",", tid_csv, ",")'), 'LIKE', '%,' . $tidElement . ',%');
        }
      });
    }

    $per_page = $this->getPerPage($per_page);
    return $builder->orderBy('id', 'desc')->paginate($per_page);
  }

  public function getLiveById($id)
  {
    return Live::with('user')->find($id);
  }

  public function startLive($user_id, $name, $tid_csv)
  {
    if (!User::find($user_id)) {
      /* 存在しないuser_idだった */
      $this->utilService->throwHttpResponseException("user_id ${user_id} は存在しません。");
    }

    $tidArray = array_filter(explode(',', $tid_csv), 'strlen');
    $tidCsvValid = '';
    foreach ($tidArray as $tidElement) {
      $tidNum = intval($tidElement);
      if (!Tag::find($tidNum)) {
        /* 存在しないtidだった */
        $this->utilService->throwHttpResponseException("tid ${tidElement} は存在しません。");
      }
      $tidCsvValid .= $tidNum . ',';
    }

    $live = Live::create([
      'user_id' => $user_id,
      'name' => $name,
      'tid_csv' => $tidCsvValid ?: null,
    ]);
    return Live::with('user')->find($live->id);
  }
}
