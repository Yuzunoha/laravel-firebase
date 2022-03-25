<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Live extends ModelBase
{
  protected $fillable = [
    'user_id',
    'name',
    'tid_csv',
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
