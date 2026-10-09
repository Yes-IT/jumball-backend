<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MatchScore extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'total_goal', 'total_goal_console', 'match_status', 'my_guesses', 'opponent_guessed', 'total_defence', 'cpu_captain_id'] ;
}
