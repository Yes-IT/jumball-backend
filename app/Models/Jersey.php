<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jersey extends Model
{
    use HasFactory;
    protected $fillable = ['jersey_image','country_id','jersey_color','year','type'];

    public function country(){
        return $this->belongsTo(Country::class);
    }
}
