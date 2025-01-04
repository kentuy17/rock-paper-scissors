<?php

namespace App\Http\Controllers;

use App\Models\GameMatch;
use App\Models\MatchRoundInfo;
use App\Models\Player;
use App\Models\PlayerStats;
use App\Models\Probability;
use Illuminate\Http\Request;

class GameController extends Controller
{

    public function index()
    {
        if (!session('match_id')) {
            return redirect('/new-match');
        }

        return redirect('/match/' . session('match_id'));
    }

    public function match($id)
    {

        if (!session(''))
            $actions = collect([
                (object)[
                    'icon' => '👊🏻',
                    'name' => 'Rock',
                    'code' => 'ROC'
                ],
                (object)[
                    'icon' => '🖐🏽',
                    'name' => 'Paper',
                    'code' => 'PAP'
                ],
                (object)[
                    'icon' => '✌🏼',
                    'name' => 'Scissor',
                    'code' => 'SCI'
                ],

            ]);

        $match = GameMatch::find($id);
        $round = $match->current_round;

        return view('game', compact('actions', 'round'));
    }

    public function selectUser()
    {
        $players = Player::get();
        return view('player-select', compact('players'));
    }

    public function setPlayer(Request $request)
    {
        $player = Player::find($request->player);
        session([
            'player_id' => $player->id,
            'player_name' => $player->name
        ]);

        return redirect('/new-match');
    }

    public function newMatch()
    {
        try {
            if (!session('player_id')) {
                return redirect('/player-select');
            }

            if (session('player_id') == 1) {
                session(['game_mode' => 'random']);
            }

            if (session('player_id') == 2) {
                session(['game_mode' => 'manual']);
            }

            $match = GameMatch::create([
                'status' => 'Playing',
                'current_round' => 1
            ]);

            session([
                'match_id' => $match->id
            ]);
        } catch (\Exception $e) {
            return dd($e);
        }

        return redirect('/match/' . $match->id);
    }

    public function findMatch()
    {
        if (!session('player_id')) {
            return redirect('/player-select');
        }
    }

    public function generateRandomMove(Request $request)
    {
        $match = GameMatch::where('status', 'Playing')
            ->where('id', $request->match_id)
            ->first();

        MatchRoundInfo::create([
            'match_id' => $match->id,
            'player_id' => $request->player_id,
            'round_no' => $match->current_round,
            'opponent_move' => null,
            'result' => null
        ]);
    }

    private function gameLogic($player, $opponent)
    {
        $prob = Probability::where('player_move', $player)
            ->where('opponent_move', $opponent)
            ->first();

        return $prob->result;
    }

    public function playerManualMove(Request $request)
    {
        try {
            //code...
            $match_id = session('match_id');
            $match = GameMatch::findOrFail($match_id);
            $moves = ["ROC", "PAP", "SCI"];
            $opponent_move = array_rand($moves);
            $result = $this->gameLogic($request->playerMove, $moves[$opponent_move]);

            $match_info = MatchRoundInfo::create([
                'match_id' => $match->id,
                'player_id' => session('player_id'),
                'round_no' => $match->current_round,
                'player_move' => $request->playerMove,
                'opponent_move' => $moves[$opponent_move],
                'result' => $result,
            ]);

            $tally = MatchRoundInfo::where('player_id', session('player_id'))
                ->where('match_id', $match_id)
                ->get();

            if ($match->current_round < 10) {
                $match->current_round += 1;
                $match->save();
            }

            if ($match->current_round == 10) {
                $match->status = 'Completed';
                $match->save();
            }

            // temp assume opponent to be either Player 1 | 2 (no time)
            $player_stats = PlayerStats::firstOrNew(['player_id' => session('player_id')]);
            $player_stats->total_rounds_played += 1;
            $player_stats->total_wins += $result == "WIN" ? 1 : 0;
            $player_stats->total_ties += $result == "DRAW" ? 1 : 0;
            $player_stats->save();

            $opponent_id = session('player_id') == 1 ? 2 : 1;
            $opponent_stats = PlayerStats::firstOrNew(['player_id' => $opponent_id]);
            $opponent_stats->total_rounds_played += 1;
            $opponent_stats->total_wins += $result == "LOSE" ? 1 : 0;
            $opponent_stats->total_ties += $result == "DRAW" ? 1 : 0;
            $opponent_stats->save();
        } catch (\Exception $e) {
            return response([
                'error' => $e->getMessage(),
            ], 500);
        }

        return response([
            'match_info' => $match_info,
            'result' => $result,
            'tally' => [
                'win' => $tally->where('result', 'WIN')->count(),
                'lose' => $tally->where('result', 'LOSE')->count(),
                'draw' => $tally->where('result', 'DRAW')->count()
            ],
            'stats' => [
                'player' => $player_stats ?? null,
                'opponent' => $opponent_stats ?? null
            ]
        ], 200);
    }
}
