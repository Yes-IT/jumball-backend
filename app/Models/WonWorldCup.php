<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WonWorldCup extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_won']; 
}
