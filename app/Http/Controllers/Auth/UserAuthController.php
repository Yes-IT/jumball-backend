<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Models\Dummy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\API\BaseController;
use App\Mail\ForgetPasswordOtpMail;
use App\Mail\SignupMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class UserAuthController extends BaseController
{
    public function sign_up(Request $request)
    {

        $validator = Validator::make($request->all(), [
            // 'name' => 'required',
            'email' => 'required|email|unique:users,email',
            // 'password' => 'required',
            // 'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return $this->sendError(array_values($validator->errors()->toArray())[0][0], '');
            // return response()->json(['status'=>201, 'errors'=> 'email is invalid'],201);
        }

        // if(!preg_match('/[0-9]/', $request->password) || !preg_match('/[a-zA-Z]/', $request->password) || !preg_match('/[^a-zA-Z0-9]/', $request->password)|| strlen($request->password ) < 6)
        // {
        //     return $this->sendError('Validation Error.', ['error'=>'password should be minimum six characters long and should at least have one numeric, one alphabetic and one special character']);
        // }




        $user = User::where('email', $request->email)->first();
        if (!empty($user)) {

            return $this->sendError('user already exist', '');
        }

        $data = array();
        $data['request'] = $request->all();
        $data['otp'] = rand(1000, 9999);

        Mail::to($request->email)->send(new SignupMail($data));


        return $this->sendResponse('User SignUp.', $data,);
    }
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            // 'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            // return $this->sendError('Validation Error.', $validator->errors());
            return $this->sendError(array_values($validator->errors()->toArray())[0][0], '');
        }

        if (!preg_match('/[0-9]/', $request->password) || !preg_match('/[a-zA-Z]/', $request->password) || !preg_match('/[^a-zA-Z0-9]/', $request->password) || strlen($request->password) < 6) {
            // return $this->sendError('Validation Error.', ['error'=>'password should be minimum six characters long and should at least have one numeric, one alphabetic and one special character']);
            return $this->sendError('password should be minimum six characters long and should at least have one numeric, one alphabetic and one special character', '');
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('MyApp')->accessToken;
        $success['name'] =  $user->name;

        $player = new Player;
        $player->user_id = $user->id;
        $player->name = $user->name;
        // $player->card_image = $player->card_image;
        $player->is_captain = 1;
        // $player->country_id = $user->country;
        $player->type = 'Gold';
        $player->designation = 'MidFielder';
        $player->save();


        $setting = new Setting;
        $setting->user_id = $user->id;
        $setting->save();

        // return $this->sendResponse($success, 'User register successfully.');
        return $this->sendResponse('User register successfully.', $success,);
    }


    public function login(Request $request)
    {
        if (!preg_match('/[0-9]/', $request->password) || !preg_match('/[a-zA-Z]/', $request->password) || !preg_match('/[^a-zA-Z0-9]/', $request->password) || strlen($request->password) < 6) {
            return $this->sendError('password should be minimum six characters long and should at least have one numeric, one alphabetic and one special character', '');
        }



        $success = array();
        $User = User::where('email', $request->email)->first();
        if (empty($User)) {
            return $this->sendError('Please enter registered email.', '');
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();
            $success['token'] =  $user->createToken('MyApp')->accessToken;
            $success['name'] =  $user->name;
            $success['is_profile_update'] =  $user->is_profile_update;

            $countries =  DB::table('countries')->get();

            $success['name'] =  $user->name;
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
            $setting = Setting::where('user_id', $user->id)->first();
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
                ->where('users.id', $user->id)
                ->first();
            if (empty($auth_user)) {
            }
            $userData = array();
            $userData['skill_level']        = $skill_level;
            $userData['countries']          = $countries;
            $userData['user']               = $user;
            $userData['position']           = $position;
            $userData['style_play']         = $style_play;
            $userData['world_cup']          = $world_cup;
            $userData['token'] =  $user->createToken('MyApp')->accessToken;
            $userData['setting'] = $setting;
            // return $this->sendResponse($success, 'User login successfully.');
            return $this->sendResponse('User login successfully.', $userData,);
        } else {
            return $this->sendError('Incorrect Password, Please try again.', '');
        }
    }

    public function test()
    {
        dd('trtesr');
    }

    public function login_check(Request $request)
    {
        $Auth = Auth::user();
        // dd('bajaakhcs');
        if ($data = array());
        $data['is_login'] = $Auth == null ? 0 : 1;
        return $this->sendResponse('auth', $data,);
    }

    public function profile_update(Request $request)
    {
        // $user = Auth::user();
        // dd($request->all());
        if (empty(Auth::id())) {
            return $this->sendError('Unauthorised.', '');
        }
        $user = User::find(Auth::id());
        if ($request->hasFile('image_profile')) {
            // if ($global_template->logo && file_exists('images/logos/'.$global_template->logo)){
            //     unlink('images/logos/'.$global_template->logo);
            // }

            $image = $request->image_profile;
            // $exc=$image->extension();
            $imageName                      = rand() . time() . '.' . $image->extension();
            $image->move(public_path('assets/images'), $imageName);
            $user->profile_images         = $imageName;  //image save kara hai yaha

        }
        // print_r($user->profile_images); die;


        $user->name             = $request->name;
        $user->country          = $request->country_id;
        $user->skill_level      = $request->skill_level;
        $user->position         = $request->position;
        $user->play_style       = $request->play_style;
        $user->world_cup        = $request->world_cup_id ?? '2022';
        $user->is_profile_update   = 1;
        $user->save();

        $player = Player::where('user_id', $user->id)->first();
        if (!empty($player)) {
            $player->user_id = $user->id;
            $player->name = $user->name;
            // $player->card_image = $player->card_image;
            $player->is_captain = 1;
            $player->country_id = $user->country;
            $player->type = 'Gold';
            $player->designation = 'MidFielder';
            $player->save();
        }

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
        return $this->sendResponse('User updated successfully.', $userData,);
    }

    public function forget_password(Request $request)
    {
        // dd($request->all());
        $user = User::where('email', $request->email)->first();
        if (empty($user)) {
            return $this->sendError('Email not registered', '');
        }

        $otp = rand(1000, 9999);
        $user->otp =  $otp;
        $user->otp_time =   date('Y-m-d H:i:s');
        $user->save();


        $data = array();
        $data['email'] = $user->email;
        $data['otp'] = $otp;

        Mail::to($user->email)->send(new ForgetPasswordOtpMail($data));
        return $this->sendResponse('Forget password otp.', $data,);
    }

    public function forget_reset_password(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'conform_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            // return $this->sendError('Validation Error.', $validator->errors());
            return $this->sendError(array_values($validator->errors()->toArray())[0][0], '');
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $data = array();
        $data['user'] = $user;
        return $this->sendResponse('Your password has been changed successfuly! .', $data,);
    }

    public function social_login(Request $request)
    {

        // dd($request->all());
        //$user_trash = User::onlyTrashed()->where('email',$request->email)->first();
        // dd( $user_trash );
        // if($user_trash !=null )
        // {
        //     return $this->sendError('not allowed','');
        // }

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            // return $this->sendError('Validation Error.', $validator->errors());
            return $this->sendError(array_values($validator->errors()->toArray())[0][0], '');
        }

        if (!empty($request->email)) {
            $user =  User::where('email', $request->email)->first();
            // dd( $user );
            if (!empty($user)) {
                // dd(Auth::loginUsingId($user->id));
                // if(Auth::attempt(['id' => $request->id])){
                if (Auth::loginUsingId($user->id)) {
                    $user = Auth::user();
                    $success['token'] =  $user->createToken('MyApp')->accessToken;
                    $success['name'] =  $user->name;
                    $success['is_profile_update'] =  $user->is_profile_update;


                    $user =  User::find($user->id);
                    $user->name = $request->name;
                    // $user->email = $request->email; 
                    $user->fcm_token = $request->fcm_tocken;
                    $user->save();
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
                        ->where('users.id', $user->id)
                        ->first();
                    $countries =  DB::table('countries')->get();

                    $success['name'] =  $user->name;
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
                    $setting = Setting::where('user_id', $user->id)->first();
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
                    $userData['token'] =  $user->createToken('MyApp')->accessToken;
                    $userData['setting'] = $setting;
                    // return $this->sendResponse($success, 'User login successfully.');
                    return $this->sendResponse('User login successfully.', $userData,);
                }
            } else {
                $user = new User();
                $user->name = $request->name;
                $user->email = $request->email;
                $user->fcm_token = $request->fcm_tocken;
                $user->save();
                // dd(Auth::loginUsingId($user->id));
                if (Auth::loginUsingId($user->id)) {
                    // if(Auth::attempt(['id=>$user->id])){
                    $user = Auth::user();
                    $success['token'] =  $user->createToken('MyApp')->accessToken;
                    $success['name'] =  $user->name;
                    $success['is_profile_update'] =  $user->is_profile_update;

                    $player = new Player;
                    $player->user_id = $user->id;
                    $player->name = $user->name;
                    // $player->card_image = $player->card_image;
                    $player->is_captain = 1;
                    $player->country_id = $user->country;
                    $player->type = 'Gold';
                    $player->designation = 'MidFielder';
                    $player->save();
                    $countries =  DB::table('countries')->get();

                    $success['name'] =  $user->name;
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
                    $setting = Setting::where('user_id', $user->id)->first();
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
                    $userData['token'] =  $user->createToken('MyApp')->accessToken;
                    $userData['setting'] = $setting;
                    // return $this->sendResponse($success, 'User login successfully.');
                    return $this->sendResponse('User login successfully.', $userData,);
                }
            }
        } else {
            return $this->sendError('Unauthorised Email Not Found.', '');
        }
    }

    public function setting(Request $request)
    {

        $setting = Setting::where('user_id', Auth::id())->first();;
        // dd('bajaakhcs');
        $data = array();
        $data['setting'] = $setting;

        return $this->sendResponse('setting', $data,);
    }

    public function music_status_change(Request $request)
    {
        //dd(Auth::id());
        $model = Setting::updateOrCreate(
            ['user_id' => Auth::id()], // Column and value to check for existence
            ['music' => $request->music, 'user_id' => Auth::id()] // Values to update or create
        );
        $music_status_change = Setting::where('user_id', Auth::id())->first();
        // $music_status_change->music = $request->music;
        //$music_status_change->save();

        $data = array();
        $data['music_status_change'] = $music_status_change;
        return $this->sendResponse('music_status_change', $data,);
    }

    public function sound_effect_status_change(Request $request)
    {
        //dd(Auth::id());

        $model = Setting::updateOrCreate(
            ['user_id' => Auth::id()],
            ['sound_effect' => $request->sound_effect, 'user_id' => Auth::id()]
        );

        $sound_effect_status_change = Setting::where('user_id', Auth::id())->first();
        // $sound_effect_status_change->sound_effect = $request->sound_effect;
        // $sound_effect_status_change->save();

        $data = array();
        $data['sound_effect_status_change'] = $sound_effect_status_change;
        return $this->sendResponse('sound_effect_status_change', $data,);
    }

    public function profile_delete(Request $request)
    {
        $profile_delete = User::where('id', Auth::id())->first();
        $profile_delete->email = rand() . '_delete_' . $profile_delete->email;
        $profile_delete->save();

        $request->user()->token()->revoke();
        return $this->sendResponse('profile_delete', '',);
    }


    public function group_captain(Request $request)
    {
        $user = User::find(Auth::id());

        $players = Player::where('country_id', '!=', $user->country)->where('is_captain', 1)->limit(15)->get();

        // $c =  collect($players);
        // //$c->push($player);
        // foreach($c as $key => $player  )
        // {
        //     //dd($player);
        //     if($key == 0)
        //     {
        //         $userplayer = Player::where('user_id', $user->id)->first();
        //        // $c =  collect($player);
        //         $c->push($userplayer);
        //     }
        // }
        // $userplayer = Player::where('user_id', $user->id)->first();
        // $c =  collect($userplayer);
        // $c->push($players);
        //dd($c->reverse());

        //    if(count($players) > 0)
        //    {
        //      foreach($players as $player)
        //      {

        //      }
        //    }
        $plll = Player::where('user_id', $user->id)->first();
        $players->push($plll);




        $data = array();
        // $data['self_player'] = Player::where('user_id', $user->id)->first();
        $data['user_id'] = Auth::id();
        $data['other_players'] = $players;

        // dd($players);
        return $this->sendResponse('group captain.', $data,);

        //dd($c);

    }

    public function guess_player_list(Request $request)
    {
        $defender  = $request->defender;
        $midfilder         = $request->midfilder;
        $attacker       = $request->attacker;
        $user = User::find(Auth::id());
        $defensivePlayers = [];
        $midfielders = [];
        $attackers = [];
        $goalkeepers = [];
        // $defensivePlayers = Player::where('country_id', $user->country)->where('designation','Defensive Midfielder')->inRandomOrder()->limit($defender)->get();
        /** 'Defender','MidFielder','Striker','Goalkeeper'  */
        $defensivePlayers = Player::where('designation', 'Defender')->inRandomOrder()->limit($defender)->get();
        $midfielders = Player::where('designation', 'MidFielder')->inRandomOrder()->limit($midfilder)->get();
        $attackers = Player::where('designation', 'Striker')->inRandomOrder()->limit($attacker)->get();
        $goalkeepers = Player::where('designation', 'Goalkeeper')->inRandomOrder()->limit(1)->get();

        $allPlayers = $defensivePlayers->merge($midfielders)->merge($attackers)->merge($goalkeepers);


        $pIds = [];
        // You can convert the merged collection to an array if needed
        $allPlayersArray = $allPlayers->toArray();
        foreach ($allPlayersArray as $row) {
            $pIds[] = $row['id'];
        }
        // dd($allPlayersArray['id']);
        $cpucountryids = Player::where('country_id', '!=', $user->country)->get()->unique('country_id');
        //$user = Player::inRandomOrder()->limit(11)->get();
        $cpucountryid = $cpucountryids->random();

        //dd($cpucountryid);
        // $players = Player::where('country_id','=',$user->country)->inRandomOrder()->limit(11)->get(); //production me run krna h ye line
        //  $players = Player::where('country_id','=',3)->whereNotIn('id', $pIds)->inRandomOrder()->limit(11)->get();
        /** Cpu player data */


        $CPUdefensivePlayers = Player::where('designation', 'Defender')->whereNotIn('id', $pIds)->inRandomOrder()->limit($defender)->get();
        $CPUmidfielders = Player::where('designation', 'MidFielder')->whereNotIn('id', $pIds)->inRandomOrder()->limit($midfilder)->get();
        $CPUattackers = Player::where('designation', 'Striker')->whereNotIn('id', $pIds)->inRandomOrder()->limit($attacker)->get();
        $CPUplayers = Player::where('designation', 'Goalkeeper')->whereNotIn('id', $pIds)->inRandomOrder()->limit(1)->get();

        $allCPUPlayers = $CPUdefensivePlayers->merge($CPUmidfielders)->merge($CPUattackers)->merge($CPUplayers);
        $allCPUPlayersData = $allCPUPlayers->toArray();
        $data = array();
        $data['myplayer'] = $allPlayers;
        $data['cpuplayer'] = $allCPUPlayersData;
        return $this->sendResponse('successful', $data,);
    }


    public function getPlayerList()
    {
        $getDummy = $users = DB::table('DummyTable')->get();
        // dd($getDummy); 
        foreach ($getDummy as $row) {
            if ($row->IsCaptain == FALSE) {
                $caption = 0;
            } else {
                $caption = 1;
            }
            $country = '';
            if ($row->team == 'Qatar') {
                $country = 1;
            } else if ($row->team == 'Ecuador') {
                $country = 2;
            } else if ($row->team == 'Senegal') {
                $country = 3;
            } else if ($row->team == 'Netherlands') {
                $country = 4;
            } else if ($row->team == 'England') {
                $country = 5;
            } else if ($row->team == 'Iran') {
                $country = 6;
            } else if ($row->team == 'USA') {
                $country = 7;
            } else if ($row->team == 'Wales') {
                $country = 8;
            } else if ($row->team == 'Argentina') {
                $country = 9;
            } else if ($row->team == 'Saudi Arabia') {
                $country = 10;
            } else if ($row->team == 'Mexico') {
                $country = 11;
            } else if ($row->team == 'Poland') {
                $country = 12;
            } else if ($row->team == 'France') {
                $country = 13;
            } else if ($row->team == 'Australia') {
                $country = 14;
            } else if ($row->team == 'Denmark') {
                $country = 15;
            } else if ($row->team == 'Tunisia') {
                $country = 16;
            } else if ($row->team == 'Spain') {
                $country = 17;
            } else if ($row->team == 'Costa Rica') {
                $country = 18;
            } else if ($row->team == 'Germany') {
                $country = 19;
            } else if ($row->team == 'Japan') {
                $country = 20;
            } else if ($row->team == 'Belgium') {
                $country = 21;
            } else if ($row->team == 'Canada') {
                $country = 22;
            } else if ($row->team == 'Morocco') {
                $country = 23;
            } else if ($row->team == 'Croatia') {
                $country = 24;
            } else if ($row->team == 'Brazil') {
                $country = 25;
            }
            //  print_r($country);




            $array[] = array(
                //'Competition' => $row->Competition,
                // 'Year'        => $row->Year,
                //'team'        => $row->team,
                'jersey_number'      => $row->number,
                'designation'    => $row->position,
                'name'           => $row->FullName,
                'country_id'     => (int)$country,
                // 'DateOfBirth' => $row->DateOfBirth,
                'is_captain'   => 0, //$caption ,
                // 'Position_id' => $row->Position_id,
                //'Special'     => $row->Special,
                //'active'      => $row->active,
                'created_at' => date('Y-m-d, h:i:s'),
                'updated_at' => date('Y-m-d, h:i:s')
            );
        }
        //  dd($array);

        DB::table('players')->insert($array);
        die;
    }




    /** data */
    public function guess_player_list_test(Request $request)
    {
        //dd('zxzx');
        $defender  = $request->defender;
        $midfilder         = $request->midfilder;
        $attacker       = $request->attacker;
        $user = User::find(Auth::id());
        $defensivePlayers = [];
        $midfielders = [];
        $attackers = [];
        $goalkeepers = [];
        // $defensivePlayers = Player::where('country_id', $user->country)->where('designation','Defensive Midfielder')->inRandomOrder()->limit($defender)->get();
        /** 'Defender','MidFielder','Striker','Goalkeeper'  */
        $defensivePlayers = Dummy::where('position', 'DF')->inRandomOrder()->limit($defender + 2)->get();
        $midfielders = Dummy::where('position', 'FW')->inRandomOrder()->limit($attacker + 2)->get();
        $attackers = Dummy::where('position', 'MF')->inRandomOrder()->limit($midfilder + 2)->get();
        $goalkeepers = Dummy::where('position', 'GK')->inRandomOrder()->limit(1)->get();

        $allPlayers = $defensivePlayers->merge($midfielders)->merge($attackers)->merge($goalkeepers);

        foreach ($allPlayers as $row) {
            if ($row->IsCaptain == 'FALSE') {
                $IsCaptain = 0;
            } else {
                $IsCaptain = 1;
            }

            $PlayeList[] = array(
                'id'  => $row->id,
                'user_id' => null,
                'name'  => $row->Name,
                'card_image' => null,
                'is_captain'  => $IsCaptain,
                'country_id' => $row->Country,
                'type'    =>    $row->Special,
                'designation' => $row->position,
                'jersey_number' => $row->number,
            );
        }


        $pIds = [];
        // You can convert the merged collection to an array if needed
        $allPlayersArray = $allPlayers->toArray();
        foreach ($allPlayersArray as $row) {
            $pIds[] = $row['id'];
        }
        // dd($allPlayersArray['id']);
        // $cpucountryids = Dummy::where('country_id','!=',$user->country)->get()->unique('country_id');
        //$user = Player::inRandomOrder()->limit(11)->get();
        // $cpucountryid = $cpucountryids->random();

        //dd($cpucountryid);
        // $players = Player::where('country_id','=',$user->country)->inRandomOrder()->limit(11)->get(); //production me run krna h ye line
        //  $players = Player::where('country_id','=',3)->whereNotIn('id', $pIds)->inRandomOrder()->limit(11)->get();
        /** Cpu player data */


        $CPUdefensivePlayers = Dummy::where('position', 'DF')->whereNotIn('id', $pIds)->inRandomOrder()->limit($defender + 2)->get();
        $CPUmidfielders = Dummy::where('position', 'FW')->whereNotIn('id', $pIds)->inRandomOrder()->limit($attacker + 2)->get();
        $CPUattackers = Dummy::where('position', 'MF')->whereNotIn('id', $pIds)->inRandomOrder()->limit($midfilder + 2)->get();
        $CPUplayers = Dummy::where('position', 'GK')->whereNotIn('id', $pIds)->inRandomOrder()->limit(1)->get();

        $allCPUPlayers = $CPUdefensivePlayers->merge($CPUmidfielders)->merge($CPUattackers)->merge($CPUplayers);
        $allCPUPlayersData = $allCPUPlayers->toArray();
        // dd($allCPUPlayersData);
        foreach ($allCPUPlayersData as $row1) {
            if ($row['IsCaptain'] == 'FALSE') {
                $IsCaptain = 0;
            } else {
                $IsCaptain = 1;
            }

            $allCPUPlayersDatas[] = array(
                'id'  => $row1['id'],
                'user_id' => null,
                'name'  => $row1['Name'],
                'card_image' => null,
                'is_captain'  => $IsCaptain,
                'country_id' => $row1['Country'],
                'type'    =>    $row1['Special'],
                'designation' => $row1['position'],
                'jersey_number' => $row1['number'],
            );
        }


        $data = array();
        $data['myplayer'] = $PlayeList;
        $data['cpuplayer'] = $allCPUPlayersDatas;
        return $this->sendResponse('successful', $data,);
    }



    /** IOS */
    public function guess_player_list_data(Request $request)
    {

        $defender  = $request->defender;
        $midfilder         = $request->midfilder;
        $attacker       = $request->attacker;

        $userCatianId = $request->userCaptian_id;
        $cap = json_decode(DB::table('pre_player_lists')->where('user_id', Auth::id())->latest()->first()->data);
        // dd($cap);

        $cpuCatianId = $request->cpuCaptian_id;

        $user = User::find(Auth::id());
        $defensivePlayers = [];
        $midfielders = [];
        $attackers = [];
        $goalkeepers = [];

        /** guess player list */
        $player_type = $request->player_type;
        $guess_player_list_id  = $request->guess_player_list_id;
        if ($player_type == '' && $guess_player_list_id == '') {
            DB::table('guess_player_list')->where('user_id', Auth::id())->delete();
        }
        if ($player_type != '' && !empty($guess_player_list_id)) {
            foreach ($guess_player_list_id as $guess_player_list_ids) {
                DB::table('guess_player_list')->insert([
                    'user_id'  => Auth::id(),
                    'type'   => $request->player_type,
                    'guess_player_list_id' => $guess_player_list_ids
                    // Add more data as needed
                ]);
            }
        }
        $user_guessid = [];
        $cup_guessid = [];
        $guess_player_list_data = DB::table('guess_player_list')->get();
        if (count($guess_player_list_data) != 0) {
            foreach ($guess_player_list_data as $guess_player_list_datas) {
                if ($guess_player_list_datas->type == 'USER') {
                    $user_guessid[] = $guess_player_list_datas->guess_player_list_id;
                } else {
                    $cup_guessid[] = $guess_player_list_datas->guess_player_list_id;
                }
            }
        }

        // $defensivePlayers = Player::where('country_id', $user->country)->where('designation','Defensive Midfielder')->inRandomOrder()->limit($defender)->get();
        /** 'Defender','MidFielder','Striker','Goalkeeper'  */
        $def = Dummy::where('id', $user_guessid)->where('position', 'DF')->first();
        if ($def) {
            $defensivePlayers = Dummy::where('position', 'DF')->where('IsCaptain', 'FALSE')->inRandomOrder()->limit($defender)->get();
        } else {
            $defensivePlayers = Dummy::where('position', 'DF')->where('IsCaptain', 'FALSE')->whereNotIn('id', $user_guessid)->inRandomOrder()->limit($defender)->get();
        }
        $mid = Dummy::where('id', $user_guessid)->where('position', 'FW')->first();
        if ($mid) {
            $midfielders = Dummy::where('position', 'FW')->where('IsCaptain', 'FALSE')->inRandomOrder()->limit($attacker)->get();
        } else {
            $midfielders = Dummy::where('position', 'FW')->where('IsCaptain', 'FALSE')->whereNotIn('id', $user_guessid)->inRandomOrder()->limit($attacker)->get();
        }
        $attack = Dummy::where('id', $user_guessid)->where('position', 'MF')->first();
        if ($attack) {
            $attackers = Dummy::where('position', 'MF')->where('IsCaptain', 'FALSE')->whereNotIn('id', $user_guessid)->inRandomOrder()->limit($midfilder)->get();
        } else {
            $attackers = Dummy::where('position', 'MF')->where('IsCaptain', 'FALSE')->whereNotIn('id', $user_guessid)->inRandomOrder()->limit($midfilder)->get();
        }
        $goalkeepers = Dummy::where('position', 'GK')->where('IsCaptain', 'FALSE')->whereNotIn('id', $user_guessid)->inRandomOrder()->limit(1)->get();

        if (isset($userCatianId)) {
            $playerCaption = Dummy::where('id', $userCatianId)->where('IsCaptain', 'TRUE')->inRandomOrder()->limit(1)->get();
        } else {

            $playerCaption = Dummy::where('IsCaptain', 'TRUE')->inRandomOrder()->limit(1)->get();
        }

        foreach ($playerCaption as $row2) {
            // dd($user);
            $mname = $user->name;
            // $mname = $this->extractMiddleAndLastNames($row2->FullName);
            // dd($user);
            $PlayeListCap[] = array(
                'id' => $row2->id,
                'user_id' => null,
                'name' => $mname,
                'card_image' => null,
                'is_captain' => 1,
                'country_id' => $user->Country->country_worldcup_flag,
                'type' => $user->Special,
                'designation' => $user->position,
                'jersey_number' => $row2->number,
            );
        }

        $matchedDesignation = $PlayeListCap[0]['designation'];

        $allPlayers = $defensivePlayers->merge($midfielders)->merge($attackers)->merge($goalkeepers);

        $allPlayers1 = [];

        foreach ($allPlayers as $row) {
            $IsCaptain = ($row->IsCaptain == 'FALSE') ? 0 : 1;
            // $mname = $this->extractName($row->Name);
            $mname = $row->Name;

            $allPlayers1[] = array(
                'id' => $row->id,
                'user_id' => null,
                'name' => $mname,
                'card_image' => null,
                'is_captain' => $IsCaptain,
                'country_id' => $row->Country,
                'type' => $row->Special,
                'designation' => $row->position,
                'jersey_number' => $row->number,
            );
        }
        $fwPlayers = [];
        $nonFwPlayers = [];
        foreach ($allPlayers1 as $player) {
            if ($player['designation'] === $matchedDesignation) {
                $fwPlayers[] = $player; // Add player to FW players array
            } else {
                $nonFwPlayers[] = $player; // Add player to non-FW players array
            }
        }

        // Now $fwPlayers contains all players with 'designation' === 'FW'
        // and $nonFwPlayers contains all other players
        $captianmachedDeginationPleyer = array_merge($PlayeListCap, $fwPlayers);

        unset($captianmachedDeginationPleyer[1]);
        $allPlayersWithCaptian = array_values($captianmachedDeginationPleyer);


        $allMyPleyer = array_merge($nonFwPlayers, $allPlayersWithCaptian);
        // Output or further processing
        $pIds = [];
        // You can convert the merged collection to an array if needed
        //$allPlayersArray = $allPlayers1->toArray();
        foreach ($allMyPleyer as $row) {
            $pIds[] = $row['id'];
        }
        $CPUdefensivePlayers = Dummy::where('position', 'DF')->where('IsCaptain', 'FALSE')->whereNotIn('id', $pIds)->whereNotIn('id', $cup_guessid)->inRandomOrder()->limit($defender)->get();
        $CPUmidfielders = Dummy::where('position', 'FW')->where('IsCaptain', 'FALSE')->whereNotIn('id', $pIds)->whereNotIn('id', $cup_guessid)->inRandomOrder()->limit($attacker)->get();
        $CPUattackers = Dummy::where('position', 'MF')->where('IsCaptain', 'FALSE')->whereNotIn('id', $pIds)->whereNotIn('id', $cup_guessid)->inRandomOrder()->limit($midfilder)->get();
        $CPUplayersGK = Dummy::where('position', 'GK')->where('IsCaptain', 'FALSE')->whereNotIn('id', $pIds)->whereNotIn('id', $cup_guessid)->inRandomOrder()->limit(1)->get();
        //  $allCPUPlayers = $CPUdefensivePlayers->merge($CPUmidfielders)->merge($CPUattackers);
        // $ids = $playerCaption[0]->id;
        if (isset($cpuCatianId)) {
            $CPUCaption = Dummy::where('id', $cpuCatianId)->where('IsCaptain', 'TRUE')->limit(1)->get();
        } else {
            $CPUCaption = Dummy::where('IsCaptain', 'TRUE')
                ->whereRaw('LOWER(FullName) LIKE ?', ['%' . strtolower($cap[$request->match_no]->name) . '%'])
                ->inRandomOrder()
                ->limit(1)
                ->get();
        }

        foreach ($CPUCaption as $row45) {

            $mname = $this->extractName($row45->Name);
            // $CPUCaption = Dummy::where('id', $cpuCatianId)->where('IsCaptain', 'TRUE')->get();
            // dd($CPUCaption, $cap);
            // $mname = $this->extractName($cap[$request->match_no]->name);
            // $mname = $this->extractMiddleAndLastNames($row45->Name);
            $PlayeCPUListCap[] = array(
                'id' => $row45->id,
                'user_id' => null,
                'name' =>  $mname,
                'card_image' => null,
                'is_captain' => 1,
                'country_id' => $row45->Country,
                'type' => $row45->Special,
                'designation' => $row45->position,
                'jersey_number' => $row45->number,
            );
        }

        $matchedCPUDesignation = $PlayeCPUListCap[0]['designation'];
        $allCPUPlayers = $CPUdefensivePlayers->merge($CPUmidfielders)->merge($CPUattackers)->merge($CPUplayersGK);
        foreach ($allCPUPlayers as $rowData1) {
            $IsCaptain = ($rowData1->IsCaptain == 'FALSE') ? 0 : 1;
            $cpuPlyerId[] = $rowData1->id;
            // $mname = $this->extractName($rowData1->Name);
            $mname = $rowData1->Name;
            $CPUallPlayers1[] = array(
                'id' => $rowData1->id,
                'user_id' => null,
                'name' => $mname,
                'card_image' => null,
                'is_captain' => $IsCaptain,
                'country_id' => $rowData1->Country,
                'type' => $rowData1->Special,
                'designation' => $rowData1->position,
                'jersey_number' => $rowData1->number,
            );
        }

        $fwPlayers1 = [];
        $nonFwPlayers = [];

        foreach ($CPUallPlayers1 as $player1) {
            if ($player1['designation'] === $matchedCPUDesignation) {
                $fwPlayers1[] = $player1; // Add player to FW players array
            } else {
                $nonFwPlayers1[] = $player1; // Add player to non-FW players array
            }
        }

        $captianmachedDeginationCPUPleyer = array_merge($PlayeCPUListCap, $fwPlayers1);
        // print_r($captianmachedDeginationCPUPleyer);

        unset($captianmachedDeginationCPUPleyer[1]);
        $allPlayersWithCaptianCPU = array_values($captianmachedDeginationCPUPleyer);
        //print_r($allPlayersWithCaptianCPU); die;
        $allMyPleyerCPU = array_merge($nonFwPlayers1, $allPlayersWithCaptianCPU);
        /** Get isSubtitute  pleayer */
        $defensivePlayersSubtitute = Dummy::where('position', 'DF')->where('IsCaptain', 'FALSE')->whereNotIn('id', $pIds)->whereNotIn('id', $cpuPlyerId)->inRandomOrder()->limit(1)->get();
        $midfieldersSubtitute = Dummy::where('position', 'FW')->where('IsCaptain', 'FALSE')->whereNotIn('id', $pIds)->whereNotIn('id', $cpuPlyerId)->inRandomOrder()->limit(1)->get();
        //dd($midfieldersSubtitute);
        $attackersSubtitute = Dummy::where('position', 'MF')->where('IsCaptain', 'FALSE')->whereNotIn('id', $pIds)->whereNotIn('id', $cpuPlyerId)->inRandomOrder()->limit(1)->get();

        $isSubtitute = $defensivePlayersSubtitute->merge($midfieldersSubtitute)->merge($attackersSubtitute);
        $isSubtitutePlyer = $isSubtitute->toArray();
        //print_r($isSubtitutePlyer); die;
        foreach ($isSubtitutePlyer as $row2) {
            if ($row2['IsCaptain'] == 'FALSE') {
                $IsCaptain = 0;
            } else {
                $IsCaptain = 1;
            }
            // $mname = $this->extractName($row2['Name']);
            $mname = $row2['Name'];
            $isSubtitutePlyerData[] = array(
                'id'  => $row2['id'],
                'user_id' => null,
                'name'  => $mname,
                'card_image' => null,
                'is_captain'  => $IsCaptain,
                'country_id' => $row2['Country'],
                'type'    =>    $row2['Special'],
                'designation' => $row2['position'],
                'jersey_number' => $row2['number'],
            );
        }


        $data = array();
        $data['myplayer'] = $allMyPleyer;
        $data['cpuplayer'] = $allMyPleyerCPU;
        $data['SubtitutePlyer'] = $isSubtitutePlyerData;
        //     DB::table('pre_player_lists')->where('user_id', Auth::id())->delete();
        //     DB::table('pre_player_lists')->insert(['user_id' => Auth::id(), 'data' => json_encode($data)]);
        // } else {
        //     $data = json_decode(DB::table('pre_player_lists')->where('user_id', Auth::id())->first()->data);
        // }
        return $this->sendResponse('successful', $data,);
        //   } catch (\Exception $e) {
        //       return $this->sendError($e->getMessage());
        //   }
    }
    function extractMiddleAndLastNames($fullName)
    {
        // Trim any leading or trailing whitespace
        $trimmedName = trim($fullName);

        // Split the name into an array of words
        $nameParts = preg_split('/\s+/', $trimmedName);

        // Initialize variables for middle and last names
        $middleName = '';
        $lastName = '';

        // Assign middle and last names if available.
        // If the name has more than two parts, assume second is middle and third is last.
        if (count($nameParts) > 2) {
            $middleName = $nameParts[1];
            $lastName = $nameParts[2];
        } elseif (count($nameParts) === 2) {
            // If there are exactly two parts, treat the second as a middle name.
            // (No explicit last name will be assigned)
            $middleName = $nameParts[1];
        }

        // Concatenate middle and last names
        $result = trim($middleName  . $lastName);

        // If middle name and last name are empty, fallback to returning the first name
        if (empty($result) && !empty($nameParts[0])) {
            $result = $nameParts[0];
        }

        return $result;
    }

    /** Get Score board */
    public function getScoreBord(Request $request)
    {
        $user_id =  Auth::id();
        $totalgame = DB::table('match_scores')->where('user_id', $user_id)->count();
        $totalLss = DB::table('match_scores')->where('user_id', $user_id)->where('match_status', 0)->count();
        $totalWin = DB::table('match_scores')->where('user_id', $user_id)->where('match_status', 1)->count();

        $total_defence = DB::table('match_scores')->where('user_id', $user_id)
            ->sum('total_defence');

        $getData = DB::table('match_scores')
            ->where('user_id', $user_id)
            ->join('users', 'match_scores.user_id', '=', 'users.id')
            ->select('match_scores.*', 'users.name')
            ->first();

        $mergedData = [];
        if (!empty($getData) == 0) {

            $userdata = User::where('id', '=', $user_id)->get();
            if (!$request->is_first) {
                $users = DB::table('players_list')->inRandomOrder()->get();
                DB::table('pre_player_lists')->where('user_id', Auth::id())->delete();
                DB::table('pre_player_lists')->insert(['user_id' => Auth::id(), 'data' => json_encode($users)]);
            } else {
                $users = json_decode(DB::table('pre_player_lists')->where('user_id', Auth::id())->first()->data);
            }
            $mergedUsers = $userdata->concat($users);
            foreach ($mergedUsers as $item) {
                $arrayData[] = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'user_id' => $item->id,
                    'total_goal' => $totalgame == 0 ? 0 : rand(0, 2),
                    'total_goal_console' => $totalgame == 0 ? 0 : rand(0, 2),
                    'match_status' => $totalgame == 0 ? 0 : rand(0, 2),
                    'my_guesses' => $totalgame == 0 ? 0 : rand(0, 2),
                    'opponent_guessed' => $totalgame == 0 ? 0 : rand(0, 2),
                    'cpu_captain_id' => $totalgame == 0 ? 0 : rand(0, 2),
                    'totalgame' => $totalgame == 0 ? 0 : rand(0, 2),
                    'totalLoss' => $totalgame == 0 ? 0 : rand(0, 2),
                    'total_defence' => $totalgame == 0 ? 0 : rand(0, 2),
                    'totalWin' => $totalgame == 0 ? 0 : rand(0, 2),
                ];
                return $this->sendResponse('successful', $mergedUsers);
            }
        }
        //   dd($getData);
        // Iterate over $getData to populate the first array
        $name = $getData->name; // Example without space
        // $name = "Rahul kumar"; // Example with space

        $spacePosition = strpos($name, ' ');

        if ($spacePosition !== false) {
            // If there's a space, get the part after it
            $result = substr($name, $spacePosition + 1);
        } else {
            // If there's no space, get the entire string
            $result = $name;
        }

        $array = [
            'id' => $getData->id,
            'user_id' => $getData->user_id,
            'total_goal' => $getData->total_goal,
            'total_goal_console' => $getData->total_goal_console,
            'match_status' => $getData->match_status,
            'my_guesses' => $getData->my_guesses,
            'opponent_guessed' => $getData->opponent_guessed,
            'cpu_captain_id' => $getData->cpu_captain_id,
            'name' =>  $result,
            'totalgame' => $totalgame,
            'totalLoss' => $totalLss,
            'total_defence' => $total_defence,
            'totalWin' => $totalWin,
        ];

        // Add the array to the merged data
        $mergedData[] = $array;


        // Fetch all users
        //$users = User::where('id', '!=', 1)->inRandomOrder()->limit(15)->get();
        if (!$request->is_first) {
            $users = DB::table('players_list')->inRandomOrder()->get();

            DB::table('pre_player_lists')->insert(['user_id' => Auth::id(), 'data' => json_encode($users)]);
        } else {
            $users = collect(json_decode(DB::table('pre_player_lists')->where('user_id', Auth::id())->latest()->first()->data));
        }
        // Check if there are any users and iterate over them
        if ($users->isNotEmpty()) {
            foreach ($users as $item) {
                $arrayData = [
                    'id' => $item->id,
                    'name' => $item->name,
                    'user_id' => $item->id,
                    'total_goal' => $totalgame == 0 ? 0 : rand(0, 2),
                    'total_goal_console' => $totalgame == 0 ? 0 : rand(0, 2),
                    'match_status' => $totalgame == 0 ? 0 : rand(0, 2),
                    'my_guesses' => $totalgame == 0 ? 0 : rand(0, 2),
                    'opponent_guessed' => $totalgame == 0 ? 0 : rand(0, 2),
                    'cpu_captain_id' => $totalgame == 0 ? 0 : rand(0, 2),
                    'totalgame' => $totalgame == 0 ? 0 : rand(0, 2),
                    'totalLoss' => $totalgame == 0 ? 0 : rand(0, 2),
                    'total_defence' => $totalgame == 0 ? 0 : rand(0, 2),
                    'totalWin' => $totalgame == 0 ? 0 : rand(0, 2),
                ];

                // Add the arrayData to the merged data
                $mergedData[] = $arrayData;
            }
        }



        return $this->sendResponse('successful', $mergedData,);
    }

    // public function extractName($name)
    // {



    //     $nameParts = explode(' ', $name);
    //     // Check the number of parts and extract accordingly
    //     if (count($nameParts) == 3) {
    //         // If name has three parts (e.g., "Rahul Kumar Saw"), get "Kumar Saw"
    //         return  $nameParts[1] . ' ' . $nameParts[2];
    //     } elseif (count($nameParts) == 2) {
    //         return $nameParts[1];
    //     } else {
    //         return $name;
    //     }
    // }
    public function extractName($name)
    {
        $nameParts = explode(' ', $name, 2);
        return $nameParts[1] ?? $nameParts[0];
    }
}
