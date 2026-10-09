<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dummy extends Model
{
    protected $table = 'DummyTable';
    use HasFactory;

       public $timestamps = false;

     protected $fillable = [
        'Competition',
        'Year',
        'team',
        'number',
        'position',
        'FullName',
        'Club',
        'Country',
        'country_id',
        'DateOfBirth',
        'IsCaptain',
        'Name',
        'Position_id',
        'Special',
        'active',
    ];

    public function Country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }
}

