<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\User;
use App\Models\WorldCup;
use App\Models\MatchScore;
use App\Models\WonWorldCup;
use App\Models\Dummy;
use Illuminate\Http\Request;
use App\Models\Jersey;
use App\Models\Sticker;
use DB;
use Illuminate\Support\Facades\Auth;

class PlayerController extends BaseController
{
    public function player_profile_data()
    {

        $auth_user =  Auth::user();

        $countries =  DB::table('countries')->get();
        $user = User::leftJoin('world_cups', 'world_cups.year', '=', 'users.world_cup')
            ->leftJoin('countries as co', 'users.country', '=', 'co.id')
            ->leftJoin('countries as ca', 'world_cups.country_id', '=', 'ca.id')
            ->select(
                'users.*',
                'world_cups.year as worldcup_year',
                'co.name as country_name',
                'co.country_worldcup_flag',
                'ca.short_name as country_short_name'
            )
            ->where('users.id', $auth_user->id)
            ->first();

        if (!empty($user)) {

            if (!empty($user->WorldCup)) {


                $user->WorldCup->name =  $user->WorldCup->Country->name;
                $user->WorldCup->country_worldcup_flag =  'upload/' . $user->WorldCup->Country->country_worldcup_flag;
            }
        }

        $skill_level = [
            ['name' => 'Amateur'],
            ['name' => 'Professional'],
            ['name' => 'International']

        ];
        $position = [
            ['name' => 'Goalkeeper'],
            ['name' => 'Defender'],
            ['name' => 'Midfielder'],
            ['name' => 'Striker']



        ];
        $style_play = [
            ['name' => 'Defensive'],
            ['name' => 'Balanced'],
            ['name' => 'Attacking']

        ];

        $query = DB::table('world_cups')
            ->leftJoin('countries', 'world_cups.country_id', '=', 'countries.id')
            ->whereIN('world_cups.year', [2022])
            ->select(
                'world_cups.year',
                DB::raw('GROUP_CONCAT(countries.short_name ORDER BY countries.short_name SEPARATOR " & ") as host_countries'),
                'world_cups.is_upcoming',
                DB::raw('if(world_cups.year=2022,1,0) as is_free'),
                DB::raw("IF(world_cups.year IN (2018, 2014), 1, 0) AS is_purchased"),
                DB::raw('if(world_cups.year=2014,1,0) as is_selected')
            );

        $world_cup = $query->groupBy('world_cups.year', 'world_cups.is_upcoming')
            ->orderBy('world_cups.year', 'desc')
            ->get();




        // foreach ($world_cup as  $world) {

        //     $world->name = $world->country->name ?? '';
        //     $world->country_worldcup_flag = 'upload/' . $world->country->country_worldcup_flag ?? '';
        // }
        // dd($user);

        if (empty($auth_user)) {
        }
        $userData = array();
        $userData['skill_level']        = $skill_level;
        $userData['countries']          = $countries;
        $userData['user']               = $user;
        $userData['position']           = $position;
        $userData['style_play']         = $style_play;
        $userData['world_cup']          = $world_cup;
        // $userData->user = 
        // dd($userData);

        return $this->sendResponse('User SignUp.', $userData,);
    }
    // public function SaveScore(Request $request)
    // {


    //     $auth_user =  Auth::user();
    //     $data = [
    //         'user_id' => $auth_user->id,
    //         'total_goal' => $request->total_goal,
    //         /** opposite team  */
    //         'total_defence'  => $request->total_defence,
    //         'cpu_captain_id'  => $request->cpu_captain_id,
    //         'total_goal_console' => $request->total_goal_console,
    //         'match_status' =>   $request->match_status,
    //         'my_guesses' => isset($request->my_guesses) ? $request->my_guesses : '',
    //         'opponent_guessed' => isset($request->opponent_guessed) ? $request->opponent_guessed : ''
    //     ];

    //     $add = MatchScore::create($data);
    //     $result = $request->match_status;
    //     if ($result == 1) {
    //         $cpu_captain_id = $request->cpu_captain_id;
    //         $data = DB::table('won_stiker_by_user')
    //             ->where('won_stiker_by_user.user_id', $cpu_captain_id)
    //             ->inRandomOrder()
    //             ->limit(1)
    //             ->get();
    //         $auth_user =  Auth::user();
    //         if (count($data) > 0) {

    //             DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->sticker_id, 'user_id' => $auth_user->id]);
    //         } else {
    //             $data =  Sticker::with(['country'])->inRandomOrder()->limit(1)->get();
    //             DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->id, 'user_id' => $request->cpu_captain_id]);
    //         }
    //     } else {
    //         $auth_user =  Auth::user();
    //         $data = DB::table('won_stiker_by_user')
    //             ->where('won_stiker_by_user.user_id', $auth_user->id)
    //             ->leftJoin('stickers', 'won_stiker_by_user.sticker_id', '=', 'stickers.id')
    //             ->leftJoin('countries', 'stickers.country_id', '=', 'countries.id') // Join with countries table
    //             ->select('won_stiker_by_user.*', 'stickers.*', 'countries.name as country_name') // Adjust fields as needed
    //             ->inRandomOrder()
    //             ->limit(1)
    //             ->get();

    //         if (count($data) > 0) {
    //             DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->sticker_id, 'user_id' => $request->cpu_captain_id]);
    //         } else {
    //             $data =  Sticker::with(['country'])->inRandomOrder()->limit(1)->get();
    //             DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->id, 'user_id' => $request->cpu_captain_id]);
    //         }
    //     }
    //     if ($add) {
    //         return $this->sendResponse('data save successfully.', []);
    //     } else {
    //         return $this->sendResponse('Something is wrong.', []);
    //     }
    // }
    public function SaveScore(Request $request)
    {


        $auth_user =  Auth::user();
        $data = [
            'user_id' => $auth_user->id,
            'total_goal' => $request->total_goal,
            /** opposite team  */
            'total_defence'  => $request->total_defence,
            'cpu_captain_id'  => $request->cpu_captain_id,
            'total_goal_console' => $request->total_goal_console,
            'match_status' =>   $request->match_status,
            'my_guesses' => isset($request->my_guesses) ? $request->my_guesses : '',
            'opponent_guessed' => isset($request->opponent_guessed) ? $request->opponent_guessed : ''
        ];

        $add = MatchScore::create($data);
        $scores = MatchScore::where('total_goal', 0)->get();

        foreach ($scores as $score) {
            $rand = rand(0, 2);
            MatchScore::where('id', $score->id)->update(['total_goal' => $rand]);
        }

        $result = $request->match_status;
        if ($result == 1) {
            $cpu_captain_id = $request->cpu_captain_id;
            $data = DB::table('won_stiker_by_user')
                ->where('won_stiker_by_user.user_id', $cpu_captain_id)
                ->inRandomOrder()
                ->limit(1)
                ->get();
            $auth_user =  Auth::user();
            if (count($data) > 0) {

                DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->sticker_id, 'user_id' => $auth_user->id, 'match_no' => $request->match_no]);
            } else {
                $data =  Sticker::with(['country'])->inRandomOrder()->limit(1)->get();
                DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->id, 'user_id' => $request->cpu_captain_id, 'match_no' => $request->match_no]);
            }
        } else {
            $auth_user =  Auth::user();
            $data = DB::table('won_stiker_by_user')
                ->where('won_stiker_by_user.user_id', $auth_user->id)
                ->leftJoin('stickers', 'won_stiker_by_user.sticker_id', '=', 'stickers.id')
                ->leftJoin('countries', 'stickers.country_id', '=', 'countries.id') // Join with countries table
                ->select('won_stiker_by_user.*', 'stickers.*', 'countries.name as country_name') // Adjust fields as needed
                ->inRandomOrder()
                ->limit(1)
                ->get();

            if (count($data) > 0) {
                DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->sticker_id, 'user_id' => $request->cpu_captain_id, 'match_no' => $request->match_no]);
            } else {
                $data =  Sticker::with(['country'])->inRandomOrder()->limit(1)->get();
                DB::table('won_stiker_by_user')->insert(['sticker_id' => $data[0]->id, 'user_id' => $request->cpu_captain_id, 'match_no' => $request->match_no]);
            }
        }
        if ($add) {
            return $this->sendResponse('data save successfully.', []);
        } else {
            return $this->sendResponse('Something is wrong.', []);
        }
    }
    public function WonWorldCup(Request $request)
    {
        $auth_user =  Auth::user();
        $getUserWonWorldCup = WonWorldCup::where('user_id', $auth_user->id)->first();
        if (!empty($getUserWonWorldCup)) {
            $total_won = $getUserWonWorldCup->total_won;
        } else {
            $total_won = 0;
        }
        if ($auth_user->id) {
            $update =  WonWorldCup::updateOrCreate(
                ['user_id' => $auth_user->id],
                ['total_won' => $total_won + (int)$request->total_won]
            );
            if ($update) {
                $this->getSticker();
                return $this->sendResponse('data save successfully.', []);
            } else {
                return $this->sendResponse('Something is wrong.', []);
            }
        }
    }
    public function  GetScroreBoard()
    {
        $auth_user =  Auth::user();
        // $userData['getUserWonWorldCup'] = WonWorldCup::where('user_id', $auth_user->id)->first();
        $userData['getUserWonWorldCup'] = DB::table('won_stiker_by_user')
            ->where('user_id', $auth_user->id)
            ->where('match_no', 5)
            ->count();
        $userData['getTotalMatch'] = MatchScore::where('user_id', $auth_user->id)->count();
        //$getTotalMatch = MatchScore::where('user_id', $auth_user->id)->count();

        //$userData['totalMatchScore'] = MatchScore::where('user_id', $auth_user->id)->get();
        $userData['totalGoal'] = MatchScore::selectRaw('SUM(total_goal) as totalGoalSum')
            ->where('user_id', $auth_user->id)
            ->first();

        $userData['total_goal_console'] = MatchScore::selectRaw('SUM(total_goal_console) as total_goal_console')
            ->where('user_id', $auth_user->id)
            ->first();


        //$win = MatchScore::where('match_status', 1)->get();
        $win = DB::table('match_scores')->where('user_id', $auth_user->id)->where('match_status', 1)->count();

        $totalGame = DB::table('match_scores')->where('user_id', $auth_user->id)->count();
        // $loss = MatchScore::where('user_id', $auth_user->id)->where('match_status', 0)->count();
        if ($win > 0 || $totalGame > 0) {
            $userData['winPercent'] = round(($win / $totalGame) * 100);
        } else {
            $userData['winPercent'] = 0;
        }
        $userData['totalPoint'] = $win * 3;

        // $userData['wingame'] = MatchScore::selectRaw('SUM(total_goal_console) as total_goal_console')
        // ->where('user_id', $auth_user->id)
        // ->first();




        //dd($totalMatchScore);

        return $this->sendResponse('User data.', $userData,);
    }

    public function getSticker()
    {

        $auth_user =  Auth::user();
        $wonSticker = DB::table('won_stiker_by_user')->where('user_id', $auth_user->id)->get();
        if (count($wonSticker) != 0) {
            foreach ($wonSticker as $row) {
                $stickerId[] = $row->id;
            }
        }
        if (!empty($stickerId)) {
            $getAllSticker =  Sticker::with(['country'])->whereNotIn('id', $stickerId)->inRandomOrder()->limit(1)->get();
        } else {
            $getAllSticker =  Sticker::with(['country'])->inRandomOrder()->limit(1)->get();
        }
        if (count($getAllSticker) != 0) {

            $data = [
                'user_id' =>  $auth_user->id,
                'sticker_id' => $getAllSticker[0]->id,
            ];
            DB::table('won_stiker_by_user')->insert($data);

            return $this->sendResponse('User data.', $getAllSticker,);
        } else {
            return $this->sendError('no data found', []);
        }
    }


    public function getUserSticker(Request $request)
    {
        $auth_user =  Auth::user();
        $usersSticker = DB::table('won_stiker_by_user')
            ->join('stickers', 'won_stiker_by_user.sticker_id', '=', 'stickers.player_id')
            ->where('won_stiker_by_user.user_id',  $auth_user->id)
            ->select('won_stiker_by_user.*', 'stickers.image')
            ->get();
        $world_cup = DB::table('won_stiker_by_user')
            ->where('user_id', $auth_user->id)
            ->where('match_no', 8)
            ->exists();
        $manager = DB::table('won_stiker_by_user')
            ->where('user_id', $auth_user->id)
            ->where('match_no', 7)
            ->exists();
        if (count($usersSticker) != 0) {
            if ($manager) {
                $usersSticker[] = [
                    'id' => -2,
                    'user_id' => $auth_user->id,
                    'sticker_id' => -2,
                    'image' => '/assets/images/download1.png',
                    'created_at' => null,
                    'updated_at' => null,
                    'match_no' => 5
                ];
            }
        }
        if (count($usersSticker) != 0) {
            if ($world_cup) {
                $usersSticker[] = [
                    'id' => 0,
                    'user_id' => $auth_user->id,
                    'sticker_id' => 0,
                    'image' => '/assets/images/undefined.png',
                    'created_at' => null,
                    'updated_at' => null,
                    'match_no' => 5
                ];
                $usersSticker[] = [
                    'id' => -1,
                    'user_id' => $auth_user->id,
                    'sticker_id' => -1,
                    'image' => '/assets/images/undefined2.png',
                    'created_at' => null,
                    'updated_at' => null,
                    'match_no' => 5
                ];
                $usersSticker[] = [
                    'id' => -2,
                    'user_id' => $auth_user->id,
                    'sticker_id' => -2,
                    'image' => '/assets/images/download1.png',
                    'created_at' => null,
                    'updated_at' => null,
                    'match_no' => 5
                ];
            }
            return $this->sendResponse('User data.', $usersSticker,);
        } else {
            return $this->sendError('no data found', []);
        }
    }

    /** Get randmal sticker */

    public function get_randmally_sticker(Request $request)
    {

        $cap = json_decode(DB::table('pre_player_lists')->where('user_id', Auth::id())->latest()->first()->data);
        $match_no = (int)$request->match_no;
        $CPUCaption = Dummy::where('IsCaptain', 'TRUE')
            ->whereRaw('LOWER(FullName) LIKE ?', ['%' . strtolower($cap[$match_no]->name) . '%'])
            ->inRandomOrder()
            ->limit(1)
            ->get();
        // $getAllSticker =  Sticker::with(['country'])->inRandomOrder()->limit(1)->get();
        $getAllSticker = Sticker::with(['country'])
            ->where('country_id', $CPUCaption[0]->country_id)
            ->get()
            ->random(1);
        return $this->sendResponse('User data.', $getAllSticker,);
    }


    /** get User Score */
    public function getUserScore(Request $request)
    {
        $auth_user =  Auth::user()->id;
        $getUserGame = DB::table('match_scores')
            ->select(
                DB::raw('SUM(total_goal) as sum_total_goal'),
                DB::raw('SUM(match_status) as match_status'),
                DB::raw('SUM(opponent_guessed) as sum_opponent_guessed')
            )
            ->where('user_id', $auth_user)
            ->first();
    }

    public function winrandomMatched(Request $request) {}
}
