<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlayerStats extends Model
{
    //
    protected $fillable = [
        'player_id',
        'total_rounds_played',
        'total_wins',
        'total_ties',
    ];
}
