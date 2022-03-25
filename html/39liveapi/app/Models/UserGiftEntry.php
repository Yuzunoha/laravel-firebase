<?php

namespace App\Models;

class UserGiftEntry extends ModelBase
{
  protected $fillable = [
    'user_id',
    'gift_id',
    'gift_status',
    'expiration_datetime', // '9999-12-31 23:59:59' 
    'to_user_id',
    'point_amount_base',
    'point_amount_calculated',
  ];

  public function gift()
  {
    return $this->hasOne(Gift::class, 'id', 'gift_id');
  }
}
