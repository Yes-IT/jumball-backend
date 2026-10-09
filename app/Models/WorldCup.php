<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorldCup extends Model
{
    use HasFactory;

     protected $fillable = [
        'country_id',
       
        'year',
        'is_upcoming',
        'image'
    ];

    function Country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
