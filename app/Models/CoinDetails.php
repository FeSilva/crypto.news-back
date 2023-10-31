<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoinDetails extends Model
{
    protected $table = "coin_details";
    use HasFactory;
    protected $fillable = [
      'currency',
      'details',
  ];
}
