<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatchRoundInfo extends Model
{
    protected $table = 'match_round_info';

    protected $fillable = [
        'match_id',
        'player_id',
        'round_no',
        'player_move',
        'opponent_move',
        'result'
    ];
}
