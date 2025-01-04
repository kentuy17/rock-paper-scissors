<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProbabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('probability')->delete();

        \DB::table('probability')->insert(array(
            0 =>
            array(
                'id' => 1,
                'player_move' => 'ROC',
                'opponent_move' => 'ROC',
                'result' => 'DRAW',
            ),
            1 =>
            array(
                'id' => 2,
                'player_move' => 'ROC',
                'opponent_move' => 'PAP',
                'result' => 'LOSE',
            ),
            2 =>
            array(
                'id' => 3,
                'player_move' => 'ROC',
                'opponent_move' => 'SCI',
                'result' => 'WIN',
            ),
            3 =>
            array(
                'id' => 4,
                'player_move' => 'PAP',
                'opponent_move' => 'ROC',
                'result' => 'WIN',
            ),
            4 =>
            array(
                'id' => 5,
                'player_move' => 'PAP',
                'opponent_move' => 'PAP',
                'result' => 'DRAW',
            ),
            5 =>
            array(
                'id' => 6,
                'player_move' => 'PAP',
                'opponent_move' => 'SCI',
                'result' => 'LOSE',
            ),
            6 =>
            array(
                'id' => 7,
                'player_move' => 'SCI',
                'opponent_move' => 'ROC',
                'result' => 'LOSE',
            ),
            7 =>
            array(
                'id' => 8,
                'player_move' => 'SCI',
                'opponent_move' => 'PAP',
                'result' => 'WIN',
            ),
            8 =>
            array(
                'id' => 9,
                'player_move' => 'SCI',
                'opponent_move' => 'SCI',
                'result' => 'DRAW',
            ),
        ));
    }
}
