<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlayerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \DB::table('players')->delete();

        \DB::table('players')->insert(array(
            0 =>
            array(
                'id' => 1,
                'name' => 'Player 1',
                'status' => 'Offline',
            ),
            1 =>
            array(
                'id' => 2,
                'name' => 'Player 2',
                'status' => 'Offline',
            ),
        ));
    }
}
