<?php

namespace App\Models;

class PointToYenRequest extends ModelBase
{
  protected $fillable = [
    'user_id',
    'point_amount',
    'yen_amount',
    'point_to_yen_status',
  ];
}
