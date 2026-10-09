<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Player extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'country_id',
        'type',
        'designation',
        'jersey_number'
    ];

    function Country(){
        return $this->belongsTo(Country::class,'country_id');
    }
}
