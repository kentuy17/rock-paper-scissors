<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GameMatch extends Model
{
    //
    protected $fillable = [
        'status',
        'current_round',
    ];
}
