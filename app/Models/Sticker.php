<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sticker extends Model
{
    use HasFactory;
    // protected $fillable = ['image','country_id','jersey_color'];
    // public function country(){
    //     return $this->belongsTo(Country::class);
    // }

    protected $fillable = [
    'player_id',
    'year',
    'image',
    'status',
    'jersey_color'
];

public function player()
{
    return $this->belongsTo(Dummy::class, 'player_id');
}
}
