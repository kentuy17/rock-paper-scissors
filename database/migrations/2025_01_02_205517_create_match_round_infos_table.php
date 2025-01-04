<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('match_round_info', function (Blueprint $table) {
            $table->id();
            $table->integer('match_id');
            $table->integer('player_id');
            $table->integer('round_no');
            $table->string('player_move');
            $table->string('opponent_move');
            $table->string('result');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_round_infos');
    }
};
