<?php

namespace App\Http\Controllers\API;


use App\Http\Controllers\API\BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use DB;
use Illuminate\Http\Request;

class CountryController extends BaseController
{
    public function worldCups($search = null)
    {
        $query = DB::table('world_cups')
            ->leftJoin('countries', 'world_cups.country_id', '=', 'countries.id')
            ->select(
                'world_cups.year',
                DB::raw('GROUP_CONCAT(countries.short_name ORDER BY countries.short_name SEPARATOR " & ") as host_countries'),
                'world_cups.is_upcoming',
                DB::raw('if(world_cups.year=2022,1,0) as is_free'),
                DB::raw("IF(world_cups.year IN (2018, 2014), 1, 0) AS is_purchased"),
                DB::raw('if(world_cups.year=2022,1,0) as is_selected'),
                DB::raw("CONCAT('https://jumball.tgastaging.com/', world_cups.image) AS image")
            );
        if ($search) {
            $query->where('world_cups.year', 'like', '%' . $search . '%')
                ->orWhere('countries.short_name', 'like', '%' . $search . '%');
        }
        $worldCups = $query->groupBy('world_cups.year', 'world_cups.is_upcoming', 'world_cups.image')
            ->orderBy('world_cups.year', 'desc')
            ->get();
        return $this->sendResponse('World Cups', $worldCups);
    }
    public function scoreBoard(Request $request)
    {
        $user = auth()->user();
        if ($request->match_no == 0) {
            DB::table('score_board')->where('user_id', $user->id)->where('year', $user->world_cup)->delete();
            $players = DB::table('DummyTable as d1')->join('stickers', 'd1.id', '=', 'stickers.player_id')->select('d1.*', DB::raw("CONCAT('https://jumball.tgastaging.com/', stickers.image) as image"))
                //         ->whereRaw('d1.id = (
                // SELECT d2.id
                // FROM DummyTable d2
                // WHERE d2.team = d1.team
                // ORDER BY RAND()
                // LIMIT 1
                // )')
                ->inRandomOrder()
                ->limit(15)
                ->get();
            // dd($players);
            // Generate random positions 1-16
            $positions = range(1, 16);
            shuffle($positions);

            $index = 0;

            foreach ($players as $player) {
                DB::table('score_board')->insert([
                    'user_id' => $user->id,
                    'player_id' => $player->id,
                    'match_Played' => 0,
                    'win' => 0,
                    'draw' => 0,
                    'lost' => 0,
                    'for_goal' => 0,
                    'against_goal' => 0,
                    'gaol_difference' => 0,
                    'points' => 0,
                    'year' => $user->world_cup,
                    'is_player' => 0,
                    'position' => $positions[$index++],
                ]);
            }

            DB::table('score_board')->insert([
                'user_id' => $user->id,
                'player_id' => null,
                'match_Played' => 0,
                'win' => 0,
                'draw' => 0,
                'lost' => 0,
                'for_goal' => 0,
                'against_goal' => 0,
                'gaol_difference' => 0,
                'points' => 0,
                'year' => $user->world_cup,
                'is_player' => 1,
                'position' => $positions[$index], // Last remaining unique position
            ]);
        } else {
        }
        $scoreBoard = DB::table('score_board')
            ->where('score_board.user_id', $user->id)
            ->where('score_board.year', $user->world_cup)
            ->leftJoin('DummyTable', 'score_board.player_id', '=', 'DummyTable.id')
            ->leftJoin('users', 'score_board.user_id', '=', 'users.id')
            ->leftJoin('stickers', 'stickers.player_id', 'DummyTable.id')
            ->select(
                'score_board.*',
                DB::raw("
                        COALESCE(
                            DummyTable.Name,
                            IF(
                                LOCATE(' ', users.name) > 0,
                                SUBSTRING(users.name, LOCATE(' ', users.name) + 1),
                                users.name
                            )
                        ) as player_name
                    "),
                DB::raw("CONCAT('https://jumball.tgastaging.com/', stickers.image) as image"),
                'DummyTable.position as position_opponent'
            )
            ->orderBy('score_board.position', 'asc')
            ->get();

        return $this->sendResponse('scoreBoard', ["data" => $scoreBoard]);
    }
    public function getSticker(Request $request)
    {
        $id = $request->id;

        $sticker = DB::table('stickers')
            ->select(DB::raw("CONCAT('https://jumball.tgastaging.com/', image) as sticker"))
            ->where('player_id', $id)
            ->first();
        return $this->sendResponse('Sticker', $sticker);
    }

    public function playerList(Request $request)
    {
        // $year = Auth::check()
        //     ? User::find(Auth::id())->world_cup
        //     : 2022;

        $positions = ['DF', 'MF', 'FW', 'GK'];

        $substitutePlayers = collect();

        foreach ($positions as $position) {

            $players = DB::table('DummyTable')
                ->where('DummyTable.position', $position)
                // ->where('DummyTable.Year', $year)
                ->leftJoin('jerseys', function ($join) {

                    $join->on('jerseys.country_id', '=', 'DummyTable.country_id')
                        ->where(function ($q) {

                            // Normal Jersey
                            $q->where(function ($q) {
                                $q->where('DummyTable.position', 'GK')
                                    ->where('DummyTable.Special', 'no')
                                    ->where('jerseys.type', 1);
                            })

                                ->orWhere(function ($q) {
                                    $q->where('DummyTable.position', '!=', 'GK')
                                        ->where('DummyTable.Special', 'no')
                                        ->where('jerseys.type', 0);
                                })

                                // Gold
                                ->orWhere(function ($q) {
                                    $q->where('DummyTable.position', 'GK')
                                        ->where('DummyTable.Special', 'gold')
                                        ->where('jerseys.type', 5);
                                })

                                ->orWhere(function ($q) {
                                    $q->where('DummyTable.position', '!=', 'GK')
                                        ->where('DummyTable.Special', 'gold')
                                        ->where('jerseys.type', 1);
                                })

                                // Purple
                                ->orWhere(function ($q) {
                                    $q->where('DummyTable.position', 'GK')
                                        ->where('DummyTable.Special', 'purple')
                                        ->where('jerseys.type', 6);
                                })

                                ->orWhere(function ($q) {
                                    $q->where('DummyTable.position', '!=', 'GK')
                                        ->where('DummyTable.Special', 'purple')
                                        ->where('jerseys.type', 3);
                                })

                                // Red
                                ->orWhere(function ($q) {
                                    $q->where('DummyTable.position', 'GK')
                                        ->where('DummyTable.Special', 'red')
                                        ->where('jerseys.type', 7);
                                })

                                ->orWhere(function ($q) {
                                    $q->where('DummyTable.position', '!=', 'GK')
                                        ->where('DummyTable.Special', 'red')
                                        ->where('jerseys.type', 4);
                                });
                        });
                })
                ->select(
                    'DummyTable.*',
                    'jerseys.jersey_image',
                    'jerseys.jersey_color'
                )
                ->inRandomOrder()
                ->get();

            $substitutePlayers = $substitutePlayers->merge($players);
        }

        $substitutePlayers = $substitutePlayers->filter(function ($player) {
            return !empty($player->jersey_image);
        })->values()->map(function ($player, $index) {

            $player->localId = $index + 1;
            $player->use = "false";
            $player->answer = "false";
            $player->lastSelect = "0";
            $player->image = "https://jumball.tgastaging.com/" . $player->jersey_image;

            return $player;
        });

        return $this->sendResponse('successful', [
            'SubstitutePlayer' => $substitutePlayers
        ]);
    }
    public function saveScoreBoard(Request $request)
    {
        $scoreBoardData = $request->input('scoreBoard');
        try {
            DB::beginTransaction();
            foreach ($scoreBoardData as $data) {
                DB::table('score_board')
                    ->where('user_id', $data['user_id'])
                    ->where('player_id', $data['player_id'])
                    ->where('year', $data['year'])
                    ->update([
                        'match_Played' => $data['match_Played'],
                        'win' => $data['win'],
                        'draw' => $data['draw'],
                        'lost' => $data['lost'],
                        'for_goal' => $data['for_goal'],
                        'against_goal' => $data['against_goal'],
                        'gaol_difference' => $data['gaol_difference'],
                        'points' => $data['points'],
                    ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendResponse('Error updating scoreboard: ' . $e->getMessage());
        }
        return $this->sendResponse('Scoreboard updated successfully.');
    }
    public function selectWorldCup(Request $request)
    {
        $user = auth()->user();
        $worldCupYear = $request->input('world_cup_year');

        // Check if the selected world cup year exists
        $worldCupExists = DB::table('world_cups')->where('year', $worldCupYear)->exists();

        if (!$worldCupExists) {
            return $this->sendError('Selected World Cup year does not exist.');
        }

        // Update the user's selected world cup year
        $user->world_cup = $worldCupYear;
        $user->save();

        return $this->sendResponse('World Cup year updated successfully.', ['selected_world_cup' => $worldCupYear]);
    }
    public function saveScore(Request $request)
    {
        $user = auth()->user();

        $opponent_id   = $request->oponent_id;
        $match_no      = $request->match_no;
        $goal          = $request->goal ?? 0;
        $goal_console  = $request->goal_console ?? 0;
        $win           = $request->win ?? 0;
        $loss          = $request->loss ?? 0;

        // Check if score record exists
        $scoreExists = DB::table('match_scores')
            ->where('user_id', $user->id)
            ->where('year', $user->world_cup)
            ->exists();

        // Save won sticker only if user won and doesn't already have it
        if ($win > 0) {
            $stickerExists = DB::table('won_stiker_by_user')
                ->where('user_id', $user->id)
                ->where('sticker_id', $opponent_id)
                ->where('year', $user->world_cup)
                ->exists();

            if (!$stickerExists) {
                DB::table('won_stiker_by_user')->insert([
                    'user_id'    => $user->id,
                    'sticker_id' => $opponent_id,
                    'match_no'   => $match_no,
                    'year'       => $user->world_cup,
                    'created_at' => now()
                ]);
            }
        }

        if ($scoreExists) {
            $match = 0;
            if ($match_no == 6 &&  $win > 0) {
                $match = 1;
            }
            DB::table('match_scores')
                ->where('user_id', $user->id)
                ->where('year', $user->world_cup)
                ->update([
                    'total_goal'          => DB::raw("total_goal + {$goal}"),
                    'matches'             => DB::raw("matches + 1"),
                    'world_cups'          => DB::raw("world_cups + {$match}"),
                    'total_goal_console'  => DB::raw("total_goal_console + {$goal_console}"),
                    'match_win'           => DB::raw("match_win + {$win}"),
                    'match_loss'          => DB::raw("match_loss + {$loss}"),
                    'updated_at'          => now(),
                ]);
        } else {
            DB::table('match_scores')->insert([
                'user_id'             => $user->id,
                'matches'             => 1,
                'year'                => $user->world_cup,
                'total_goal'          => $goal,
                'total_goal_console'  => $goal_console,
                'world_cups'          => 0,
                'match_win'           => $win,
                'match_loss'          => $loss,
                'created_at'          => now(),
                'updated_at'          => now(),
            ]);
        }

        return $this->sendResponse('Score updated successfully.');
    }
    public function staticsScore()
    {
        $user = auth()->user();
        $score = DB::table('match_scores')
            ->where('user_id', $user->id)
            ->select(
                'id',
                'user_id',
                'year',
                'matches',
                'world_cups',
                'total_goal',
                'total_goal_console',
                'match_win',
                'match_loss',
                DB::raw("
            CASE
                WHEN (match_win + match_loss) = 0 THEN 0
                WHEN MOD(ROUND((match_win / (match_win + match_loss)) * 100, 2), 1) = 0
                    THEN CAST(ROUND((match_win / (match_win + match_loss)) * 100, 0) AS UNSIGNED)
                ELSE ROUND((match_win / (match_win + match_loss)) * 100, 2)
            END AS winning_percentage
        ")
            )
            ->first();

        return $this->sendResponse('Score fetched successfully.', $score);
    }
    public function friendlyRandomPlayer()
    {
        $player = DB::table('DummyTable as d1')
            ->join('stickers', 'd1.id', '=', 'stickers.player_id')
            ->select(
                'd1.*',
                DB::raw("CONCAT('https://jumball.tgastaging.com/', stickers.image) as image"),
                'd1.position as position_opponent'
            )
            ->inRandomOrder()
            ->first();
        return $this->sendResponse('Score fetched successfully.', $player);
    }
}
