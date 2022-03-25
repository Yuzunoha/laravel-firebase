<?php

namespace App\Models;

class PointWallet extends ModelBase
{
  protected $primaryKey = 'user_id';

  protected $fillable = [
    'user_id',
    'point_amount',
    'point_amount_being_requested',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
