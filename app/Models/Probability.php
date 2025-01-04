<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Probability extends Model
{
    protected $table = 'probability';

    protected $fillable = [
        'player_move',
        'opponent_move',
        'result',
    ];
}
