<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class CoinWallet extends ModelBase
{
  protected $primaryKey = 'user_id';

  protected $fillable = [
    'user_id',
    'coin_amount',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
