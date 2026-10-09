<?php

namespace App\Http\Controllers;

use App\Models\Country;

use App\Models\Player;
use App\Models\Dummy;
use App\Models\WebsidePage;
use App\Models\User;
use App\Models\WorldCup;
use App\Models\Jersey;
use App\Models\Sticker;
use Doctrine\Inflector\Rules\Word;
use GrahamCampbell\ResultType\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Ui\Presets\React;
use Lcobucci\JWT\Token\Plain;

use Symfony\Component\HttpKernel\HttpCache\ResponseCacheStrategy;

class AdminController extends Controller
{
    public function admin_login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            // 'c_password' => 'required|same:password',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'errors' => $validator->errors()]);
        }

        $credentials = $request->only('email', 'password');
        $AdminCheck = User::where('email', $request->email)->first();
        // dd($AdminCheck);
        if (!empty($AdminCheck) && !Hash::check($request->password, $AdminCheck->password)) {

            return response()->json(['status' => 202, 'errors' => ['You Have not Access']]);
        }

        if (!empty($AdminCheck) && $AdminCheck->role == 0) {
            return response()->json(['status' => 201, 'errors' => ['You Have not Access']]);
        }

        if (Auth::attempt($credentials)) {
            // Authentication passed...
            // return redirect()->intended('dashboard');
            return response()->json(['status' => 200, 'success' => 'success']);
        }

        return response()->json(['status' => 201, 'success' => 'success']);
    }

    public function admin_logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }
    public function users(Request $request)
    {

        // $user = User::with('Country', 'WorldCup')->get();
        // dd(  $user );


        $query =  User::query();

        if (!empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')->orwhere('email', 'like', '%' . $request->search . '%');
            // $query->orWhere('email','like', '%' . $request->search . '%');
        }

        if (!empty($request->skill_level)) {

            $query->whereIn('skill_level', $request->skill_level);
        }
        if (!empty($request->position)) {
            $query->whereIn('position', $request->position);
        }
        if (!empty($request->play_style)) {
            $query->whereIn('play_style', $request->play_style);
        }
        if (!empty($request->datefilters)) {
            $date_explode = explode('-', $request->datefilters);
            // $query->where('created_at', $request->position);

            $from =     date("Y-m-d", strtotime($date_explode[0]));
            $to   =     date("Y-m-d", strtotime($date_explode[1] . '+1 day'));
            $query->whereBetween('created_at', [$from, $to]);
            // $query->whereDate('created_at','<=', $from)
            // ->whereDate('created_at','>=',  $to);
        }


        $users = $query->with('Country', 'WorldCup')->where('role', 0)->paginate(10)->appends(request()->query());
        //$users = $query->with('Country', 'WorldCup')->where('role',0)->toSql();//->paginate(10)->appends(request()->query());
        //    dd($users);
        //changes
        if ($request->ajax()) {
            return view('admin.users', compact('users', 'request'))->render();
        }
        //changes

        return view('admin.users', compact('users', 'request'));
    }

    function user_export_csv(Request $request)
    {
        $query =  User::query();

        if (!empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%')->orwhere('email', 'like', '%' . $request->search . '%');
            // $query->orWhere('email','like', '%' . $request->search . '%');
        }

        if (!empty($request->skill_level)) {

            $query->whereIn('skill_level', $request->skill_level);
        }
        if (!empty($request->position)) {
            $query->whereIn('position', $request->position);
        }
        if (!empty($request->play_style)) {
            $query->whereIn('play_style', $request->play_style);
        }
        if (!empty($request->datefilters)) {
            $date_explode = explode('-', $request->datefilters);
            // $query->where('created_at', $request->position);

            $from =     date("Y-m-d", strtotime($date_explode[0]));
            $to =       date("Y-m-d", strtotime($date_explode[1] . '+1 day'));
            $query->whereBetween('created_at', [$from, $to]);
            // $query->whereDate('created_at','<=', $from)
            // ->whereDate('created_at','>=',  $to);
        }

        // $users = $query->with('WorldCup')->where('role',0)->get();//->paginate(10)
        $users = $query->with('Country', 'WorldCup')->where('role', 0)->get(); //->paginate(10)
        // dd($users);

        if (count($users) <= 0) {
            return redirect()->back()->withErrors('Data Not found..!');
        }



        $fileName = 'user' . date('Y-m-d') . '.csv';
        // $tasks = Task::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('SNo.', 'User Name', 'Email', 'Country', 'Skill Level', 'Position', 'Style of Play', 'World Cup', 'Signup Date');

        $callback = function () use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($users as $key => $user) {

                // fputcsv($file, array(++$key, $user->name, $user->email, $user->country_name, $user->skill_level,$user->position,$user->play_style,$user->WorldCup->Country->name.' '.$user->WorldCup->year, date('d/m/Y',strtotime($user->created_at ))));
                // fputcsv($file, array(++$key, $user->name, $user->email, $user->country_name, $user->skill_level, $user->position, $user->play_style, $user->WorldCup->Country->name . ' ' . $user->WorldCup->year, date('d/m/Y', strtotime($user->created_at))));
                fputcsv($file, [
                    $key + 1,
                    $user->name,
                    $user->email,
                    $user->country_name,
                    $user->skill_level,
                    $user->position,
                    $user->play_style,
                    ($user->WorldCup?->Country?->name ?? '') . ' ' . ($user->WorldCup?->year ?? ''),
                    $user->created_at?->format('d/m/Y')
                ]);

                // fputcsv($file, array(++$key, $user->name, $user->email, $user->Country->name, $user->skill_level,$user->position,$user->play_style,$user->WorldCup->Country->name.' '.$user->WorldCup->year, date('d/m/Y',strtotime($user->created_at ))));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    // public function players(Request $request)
    // {
    //     $query = Player::query();

    //     $country_ids = array();
    //     if (!empty($request->country)) {
    //         foreach ($request->country as $country) {
    //             $countrys = Country::where('name', $country)->first();
    //             array_push($country_ids, $countrys->id);
    //         }
    //     }
    //     if (!empty($request->search)) {
    //         $query->where('name', 'like', '%' . $request->search . '%');
    //         // $query->orWhere('email','like', '%' . $request->search . '%');
    //     }
    //     // dd($country_ids);
    //     if (!empty($request->types)) {   //yaha se krna hai ye kaam nhi kr raha ============
    //         $query->whereIn('type', $request->types);
    //     }

    //     if (count($country_ids) > 0) {
    //         $query->whereIn('country_id', $country_ids);
    //     }

    //     $players = $query->with('Country')->paginate(10)->appends(request()->query());

    //     $countries = Country::all();

    //     // dd($players);

    //     return view('admin.players', compact('players', 'countries', 'request'));
    // }

    //changes
    public function players(Request $request)
    {
        $query = Dummy::query();

        // Search by Name & FullName
        if (!empty($request->search)) {

            $query->where(function ($q) use ($request) {
                $q->where('Name', 'like', '%' . $request->search . '%')
                    ->orWhere('FullName', 'like', '%' . $request->search . '%');
            });
        }

        // Country Filter
        if (!empty($request->country)) {

            $countryCodes = Country::whereIn('name', $request->country)
                ->pluck('short_name')
                ->toArray();

            $query->whereIn('Country', $countryCodes);
        }

        // Type Filter (Gold / Red / Purple)
        if (!empty($request->types)) {

            $types = array_map('strtolower', $request->types);

            $query->whereIn('Special', $types);
        }

        $players = $query
            ->orderBy('id', 'desc')
            ->paginate(10)
            ->appends($request->query());

        $countries = Country::orderBy('name')->get();

        return view('admin.players', compact('players', 'countries', 'request'));
    }
    //changes

    public function player_export_csv(Request $request)
    {

        $query = Dummy::query();

        // $country_ids = array();
        // if (!empty($request->country)) {
        //     foreach ($request->country as $country) {
        //         $countrys = Country::where('name', $country)->first();
        //         array_push($country_ids, $countrys->id);
        //     }
        // }
        if (!empty($request->country)) {

            $countryCodes = Country::whereIn('name', $request->country)
                ->pluck('short_name')
                ->toArray();

            $query->whereIn('Country', $countryCodes);
        }
        if (!empty($request->search)) {
            // $query->where('name', 'like', '%' . $request->search . '%');
            $query->where(function ($q) use ($request) {
                $q->where('Name', 'like', '%' . $request->search . '%')
                    ->orWhere('FullName', 'like', '%' . $request->search . '%');
            });
        }
        // dd($country_ids);
        if (!empty($request->types)) {
            // $query->whereIn('type', $request->types);
            $types = array_map('strtolower', $request->types);

            $query->whereIn('Special', $types);
        }

        // if (count($country_ids) > 0) {
        //     $query->whereIn('country_id', $country_ids);
        // }

        // $players = $query->with('Country')->get();
        $players = $query->get();


        if (count($players) <= 0) {
            return redirect()->back()->withErrors('Data Not found..!');
        }

        $fileName = 'Player_' . date('Y-m-d') . '.csv';
        // $tasks = Task::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        // $columns = array('SNo.', 'Surname', 'Country', 'Type', 'Designation', 'Jersey Number');
        $columns = [
            'S.No',
            'Competition',
            'Year',
            'Team',
            'Number',
            'Position',
            'Full Name',
            'Country',
            'Captain',
            'Name',
            'Position ID',
            'Special'
        ];

        // $callback = function () use ($players, $columns) {
        //     $file = fopen('php://output', 'w');
        //     fputcsv($file, $columns);

        //     foreach ($players as $key => $user) {
        //         fputcsv($file, array(++$key, $user->name, $user->Country->name, $user->type, $user->designation));
        //     }

        //     fclose($file);
        // };


        $callback = function () use ($players, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            // foreach ($players as $key => $user) {
            //     fputcsv($file, [
            //         $key + 1,
            //         $user->name ?? '',
            //         optional($user->Country)->name ?? '',
            //         $user->type ?? '',
            //         $user->designation ?? '',
            //         $user->jersey_number ?? ''
            //     ]);
            // } 

            foreach ($players as $key => $user) {
                fputcsv($file, [
                    $key + 1,
                    $user->Competition,
                    $user->Year,
                    $user->team,
                    $user->number,
                    $user->position,
                    $user->FullName,
                    $user->Country,
                    $user->IsCaptain,
                    $user->Name,
                    $user->Position_id,
                    $user->Special,
                ]);
            }

            fclose($file);
        };


        return response()->stream($callback, 200, $headers);
    }

    function delete_all_player(Request $request)
    {
        if (Player::count() > 0) {

            $player = Player::truncate();
            return response()->json(['status' => 200, 'msg' => 'success']);
        } else {
            return response()->json(['status' => 201, 'errors' => ['data not found..!']]);
        }
    }

    function sample_player_csv()
    {
        $players = new Collection();
        $players->push(['name' => 'David json', 'Country_name' => 'Landon', 'type' => 'Red', 'designation' => 'wide attackers', 'jersey_number' => 10]);
        $players->push(['name' => 'Johon Smith', 'Country_name' => 'India', 'type' => 'Green', 'designation' => 'Goalkeeper', 'jersey_number' => 555]);
        $players->push(['name' => 'Donald Trump', 'Country_name' => 'NewYork', 'type' => 'Gold', 'designation' => 'Defensive Midfielder', 'jersey_number' => 666]);


        // dd($players);

        $fileName = 'Sample_Player_CSV.csv';
        // $tasks = Task::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );
        $columns = array('SNo.', 'Surname', 'Country', 'Type', 'Designation', 'Jersey Number');

        $callback = function () use ($players, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($players as $key => $user) {


                fputcsv($file, array(++$key, $user['name'], $user['Country_name'], $user['type'], $user['designation'], $user['jersey_number']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    function import_player_csvold(Request $request)
    {

        if (empty($request->file('csv_upload'))) {
            return response()->json(['status' => 201, 'errors' => ['csv file required']]);
        }
        $file = $request->file('csv_upload');
        $fileContents = file($file->getPathname());

        // dd($fileContents);

        foreach ($fileContents as $key => $line) {
            if ($key != 0) {

                $data = str_getcsv($line);
                // dd($data[1]);

                Player::create([
                    'name' => $data[1],
                    'country_id' => Country::where('name', $data[2])->first()->id,
                    'type' => $data[3],
                    'designation' => $data[4],
                    'jersey_number' => $data[5]
                    // Add more fields as needed
                ]);
            }
        }

        return response()->json(['status' => 200, 'msg' => 'success']);
    }


    public function import_player_csv(Request $request)
    {
        if (!$request->hasFile('csv_upload')) {
            return response()->json([
                'status' => 201,
                'errors' => ['CSV file is required']
            ]);
        }

        $file = $request->file('csv_upload');
        $fileContents = file($file->getPathname());

        foreach ($fileContents as $key => $line) {

            // Skip header row
            if ($key === 0) {
                continue;
            }

            $data = str_getcsv($line);

            if (count($data) < 6) {
                continue;
            }

            $country = Country::where('name', trim($data[2]))->first();

            // Skip if country not found
            if (!$country) {
                continue;
            }

            Player::create([
                'name'          => $data[1] ?? '',
                'country_id'    => $country->id,
                'type'          => $data[3] ?? '',
                'designation'   => $data[4] ?? '',
                'jersey_number' => $data[5] ?? '',
            ]);
        }

        return response()->json([
            'status' => 200,
            'msg' => 'CSV imported successfully'
        ]);
    }




    public function statistics(Request $request)
    {

        $query = Player::query();

        if (!empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
            // $query->orWhere('email','like', '%' . $request->search . '%');
        }
        $players = $query->with('Country')->paginate(10)->appends(request()->query());
        // dd($players);

        return view('admin.statistics', compact('players', 'request'));
    }

    public function statistics_export_csv(Request $request)
    {
        $query = Player::query();

        if (!empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
            // $query->orWhere('email','like', '%' . $request->search . '%');
        }
        $players = $query->with('Country')->get();

        $fileName = 'Statistics_' . date('Y-m-d') . '.csv';
        // $tasks = Task::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('SNo.', 'Playername', 'Total no. of games played', 'Goal scored', 'Goal concede', 'Win percentage', 'Total points', 'Worldcups won');

        $callback = function () use ($players, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($players as $key => $user) {
                fputcsv($file, array(++$key, $user->name, '04', '14', '09', '63%', '99', '04'));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
    public function term_and_condition()
    {
        $term_and_conditions = WebsidePage::find(1);
        return view('admin.term_and_condition', compact('term_and_conditions'));
    }

    public function term_and_condition_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'erorrs' => $validator->errors()]);
        }
        // dd($request->all());
        $term_and_conditions = WebsidePage::find(1);
        $term_and_conditions->title = 'Terms and Conditions';
        $term_and_conditions->description = $request->description;
        $term_and_conditions->save();

        return response()->json(['status' => 200, 'message' => 'success']);
    }


    public function privacy_policy()
    {
        $privacy_policies = WebsidePage::find(2);
        return view('admin.privacy_policy', compact('privacy_policies'));
    }

    public function privacy_policy_save(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'erorrs' => $validator->errors()]);
        }
        // dd($request->all());
        $term_and_conditions = WebsidePage::find(2);
        $term_and_conditions->title = 'Privacy-Policy';
        $term_and_conditions->description = $request->description;
        $term_and_conditions->save();

        return response()->json(['status' => 200, 'message' => 'success']);
    }


    function edit_user_profile($id)
    {

        $user = User::find($id);
        $countries = Country::get();
        $world_cups = WorldCup::get();
        $data = array();
        $data['user'] = $user;
        $data['countries'] = $countries;
        $data['world_cups'] = $world_cups;
        return response()->json(['status' => 200, 'data' => $data]);
    }
    function delete_user_profile(Request $request)
    {
        // dd($request);
        $user = User::find($request->user_id);
        if (!empty($user)) {

            $user->delete();
        }
        return response()->json(['status' => 200, 'message' => 'success']);
    }
    function delete_player(Request $request)
    {
        // dd($request);
        $player = Player::find($request->player_id);
        if (!empty($player)) {

            $player->delete();
        }
        return response()->json(['status' => 200, 'message' => 'success']);
    }


    function user_status_change(Request $request)
    {

        $user_id = $request->user_id;

        $user = User::find($user_id);
        $user->status = $user->status == 0 ? 1 : 0;
        // dd($user);
        $user->save();

        return response()->json(['status' => 200, 'message' => 'success']);
    }

    function edit_user_profile_update(Request $request)
    {
        // dd($request->all());

        $user = User::find($request->user_id);
        //   dd($user);
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
        $user->name          = $request->name;
        // $user->country       = $request->country;
        $user->country_name  = $request->country_name;
        $user->skill_level   = $request->skill_level;
        $user->position      = $request->position;
        $user->play_style    = $request->play_style;
        $user->save();

        return response()->json(['status' => 200, 'msg' => 'success']);
    }

    // public function jersey_management(Request $request)
    // {
    //     $allCountry = Country::all();
    //     $Jersey = Jersey::with(['country'])->paginate(10)->appends(request()->query());
    //     return view('admin.jersey-management', compact('allCountry', 'Jersey'));
    // }
    public function jersey_management(Request $request)
    {
        $allCountry = Country::all();

        $query = Jersey::with('country');

        if ($request->filled('search')) {

            $search = $request->search;

            $query->whereHas('country', function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%');
            });
        }

        $Jersey = $query->paginate(10)->appends($request->all());

        return view('admin.jersey-management', compact('allCountry', 'Jersey', 'request'));
    }
    public function add_jersey(Request $request)
    {   
        if (!empty($request->file('jersey_image'))) {
            $imageName = time() . '.' . $request->jersey_image->extension();
            $request->jersey_image->move(public_path('upload/jersey_image'), $imageName);
            $add = Jersey::updateOrCreate(
                ['id' => $request->id],
                [
                    'country_id' => $request->country_id,
                    'jersey_image' => 'upload/jersey_image/' . $imageName,
                    'jersey_color' => $request->jersey_color,
                    'year'=>$request->year,
                    'type'=>$request->type
                ]
            );
        } else {
            $add = Jersey::updateOrCreate(
                ['id' => $request->id],
                ['country_id' => $request->country_id, 'jersey_color' => $request->jersey_color,
                  'year'=>$request->year,
                  'type'=> $request->type
                
                ],
               
            );
        }
        // if ($add) {
             
        //     return redirect()->back()->with('success', 'Data added successfully');
        // } else {
        //     return redirect()->back()->with('error', 'Something went wrong');
        // }

        if ($add) {

    return response()->json([
        'status' => 200,
        'message' => 'Jersey added successfully.'
    ]);

} else {

    return response()->json([
        'status' => 400,
        'message' => 'Something went wrong.'
    ]);

}
    }

    function delete_jurcy(Request $request)
    {
        // dd($request);
        $Jersey = Jersey::find($request->jearcy_id);
        if (!empty($Jersey)) {

            $Jersey->delete();
        }
        return response()->json(['status' => 200, 'message' => 'success']);
    }
    //edit jearcy
    public function edit_jersey($id)
{
    $jersey = Jersey::find($id);

    return response()->json([
        'status' => 200,
        'data' => $jersey
    ]);
}

public function update_jersey(Request $request)
{
    $jersey = Jersey::find($request->jearcy_id);

    $jersey->country_id = $request->country_id;
    $jersey->jersey_color = $request->jersey_color;
    $jersey->year = $request->year;
    $jersey->type = $request->type;

    if($request->hasFile('jersey_image'))
    {
        $imageName = time().'.'.$request->jersey_image->extension();
        $request->jersey_image->move(public_path('upload/jersey_image'),$imageName);

        $jersey->jersey_image = 'upload/jersey_image/'.$imageName;
    }

    $jersey->save();

    return response()->json([
        'status'=>200,
        'message'=>'Updated Successfully'
    ]);
}

    public function sticker_managementold(Request $request)
    {
        $allCountry = Country::all();
        $sticker = Sticker::with(['country'])->paginate(20)->appends(request()->query());

        return view('admin.sticker-management', compact('allCountry', 'sticker'));
    }

    //changes
    // public function sticker_management(Request $request)
    // {
    //     $usedCountryIds = Sticker::pluck('country_id')->toArray();

    //     $allCountry = Country::whereNotIn('id', $usedCountryIds)->get();

    //     $query = Sticker::with('country');

    //     if ($request->filled('search')) {

    //         $search = $request->search;

    //         $query->whereHas('country', function ($q) use ($search) {
    //             $q->where('name', 'LIKE', '%' . $search . '%');
    //         });
    //     }

    //     $sticker = $query->paginate(20)->appends($request->all());

    //     return view('admin.sticker-management', compact('allCountry', 'sticker', 'request'));
    // }
    //changes

    public function sticker_management(Request $request)
    {
        // Session (Year) dropdown
        $years = Dummy::select('Year')
            ->distinct()
            ->orderBy('Year', 'DESC')
            ->pluck('Year');

        $query = Sticker::leftJoin('DummyTable', 'stickers.player_id', '=', 'DummyTable.id')
            ->select(
                'stickers.*',
                'DummyTable.FullName',
                'DummyTable.Year'
            );

        // Search by Player Name
        if ($request->filled('search')) {

            $query->where('DummyTable.FullName', 'LIKE', '%' . $request->search . '%');
        }

        $sticker = $query->paginate(25)->appends($request->all());

        $players = Dummy::orderBy('FullName')->get(['id', 'FullName']);

        return view('admin.sticker-management', compact(
            'years',
            'sticker',
            'players',
            'request'
        ));


        // return view('admin.sticker-management', compact('years', 'sticker', 'request'));
    }
    //new method
    public function getPlayers($year)
    {
        $players = Dummy::where('Year', $year)
            ->orderBy('FullName')
            ->select('id', 'FullName')
            ->get();

        return response()->json($players);
    }
    //new method




    public function add_sticker(Request $request)
    {
        
        if ($request->hasFile('sticker_image')) {

            $imageName = time() . '.' . $request->sticker_image->extension();

            $request->sticker_image->move(public_path('upload/sticker_image'), $imageName);

            $add = Sticker::updateOrCreate(
                ['id' => $request->id],
                [
                    'player_id' => $request->player_id,
                    'year'      => $request->year,
                    'image' => 'upload/sticker_image/' . $imageName,
                    'status'=>$request->status
                ]
            );
        } else {

            $add = Sticker::updateOrCreate(
                ['id' => $request->id],
                [
                    'player_id' => $request->player_id,
                    'year'      => $request->year,
                    'status'=>$request->status

                ]
            );
        }

        if ($add) {
            // return redirect()->back()->with('success', 'Data added successfully');
            return response()->json(['status' => 200, 'message' => 'Data save successfully']);
        }

        return redirect()->back()->with('error', 'Something went wrong');
    }

    function delete_sticker(Request $request)
    {
        // dd($request);
        $Sticker = Sticker::find($request->sticker_id);
        if (!empty($Sticker)) {

            $Sticker->delete();
        }
        return response()->json(['status' => 200, 'message' => 'deleted successfully']);
    }

    //edit
    public function editSticker($id)
    {
        $sticker = Sticker::find($id);

        if (!$sticker) {
            return response()->json([
                'success' => false,
                'message' => 'Sticker not found'
            ]);
        }

        return response()->json($sticker);
    }
    public function updateSticker(Request $request, $id)
    {  
        
       $request->validate([
    'player_id' => 'nullable',
    'year' => 'nullable',
    'sticker_image' => 'nullable|image|mimes:jpg,jpeg,png,webp',
    'edit_status' => 'nullable'
]);

        $sticker = Sticker::find($id);

        if (!$sticker) {
            return response()->json([
                'success' => false,
                'message' => 'Sticker not found'
            ]);
        }

        // Update Player
        $sticker->player_id = $request->player_id;

        $sticker->year = $request->year;
        $sticker->status=$request->edit_status;
        // Update Image
        if ($request->hasFile('sticker_image')) {

            // Purani image delete (optional)
            if ($sticker->image && file_exists(public_path($sticker->image))) {
                unlink(public_path($sticker->image));
            }

            $image = $request->file('sticker_image');

            $filename = time() . '.' . $image->getClientOriginalExtension();

            $image->move(public_path('uploads/stickers'), $filename);

            $sticker->image = 'uploads/stickers/' . $filename;
        }

        $sticker->save();
       

        return response()->json([
            'success' => true,
            'message' => 'Sticker updated successfully.'
        ]);
    }



    public function world_cup_management(Request $request)
    {
        $search = $request->search;

        $country = Country::orderBy('name')->get();

        $worldcup = WorldCup::with('Country')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->whereHas('Country', function ($countryQuery) use ($search) {
                        $countryQuery->where('name', 'LIKE', "%{$search}%");
                    })
                        ->orWhere('year', 'LIKE', "%{$search}%");
                });
            })
            ->paginate(10);

        // Preserve only search, not page
        if ($search) {
            $worldcup->appends([
                'search' => $search
            ]);
        } else {
            $worldcup->appends($request->query());
        }

        return view('admin.world_cup_management', compact('worldcup', 'country'));
    }
    public function getWorldCup($id)
    {
        return WorldCup::findOrFail($id);
    }
    public function updateWorldCup(Request $request, $id)
    {
        $request->validate([
            'country_id'   => 'required',
            'year'         => 'required|numeric',
            'is_upcoming'  => 'required|in:0,1',
            'edit_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6048',
        ]);

        try {
            $worldcup = WorldCup::findOrFail($id);



            $worldcup->update([
                'country_id'   => $request->country_id,
                'year'         => $request->year,

            ]);
            $worldcup = WorldCup::where('year', $request->year);
            $worldcup->update([
                'is_upcoming'  => $request->is_upcoming,

            ]);

            if ($request->hasFile('edit_image')) {

                $imageName = time() . '.' . $request->edit_image->extension();

                $request->edit_image->move(
                    public_path('upload/sticker_image'),
                    $imageName
                );

                $worldcup->update([
                    'image' => 'upload/sticker_image/' . $imageName,
                ]);
            }



            return response()->json([
                'success' => true,
                'message' => 'World Cup updated successfully.'
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    //add world cup
    public function storeWorldCup(Request $request)
    {

        $request->validate([
            'country_id'   => 'required',

            'year'         => 'required',
            'is_upcoming'  => 'required',
            //    'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:6048',
        ]);
        $imageName = null;

        if ($request->hasFile('image')) {

            $imageName = time() . '.' . $request->image->extension();

            // $request->image->move(
            //     public_path('upload/worldcup'),
            //     $imageName
            // );
            $request->image->move(public_path('upload/sticker_image'), $imageName);
        }

        WorldCup::create([

            'country_id'  => $request->country_id,

            'year'        => $request->year,
            'is_upcoming' => $request->is_upcoming,
            'image' => 'upload/sticker_image/' . $imageName,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'World Cup Added Successfully.'
        ]);
    }

    public function editPlayer($id)
    {
        return response()->json(Dummy::findOrFail($id));
    }



    public function updatePlayer(Request $request, $id)
    {
        $player = Dummy::findOrFail($id);

        $player->Competition = $request->Competition;
        $player->Year = $request->Year;
        $player->team = $request->team;
        $player->number = $request->number;
        $player->position = $request->position;
        $player->FullName = $request->FullName;
        $player->Country = $request->Country;
        $player->country_id = $request->country_id;
        $player->IsCaptain = $request->IsCaptain;
        $player->Name = $request->Name;
        $player->Position_id = $request->Position_id;
        $player->Special = $request->Special;

        // Sticker Upload
        if ($request->hasFile('sticker')) {

            $image = $request->file('sticker');

            $filename = time() . '_' . $image->getClientOriginalName();

            $image->move(public_path('upload/stickers'), $filename);

            $player->sticker = $filename;
        } else {

            $player->sticker = null;
        }

        $player->save();

        return response()->json([
            'success' => true,
            'message' => 'Player updated successfully.'
        ]);
    }
    public function sample_player_csv2()
    {
        $file = public_path('upload/SAMPLE.CSV.xlsx');




        if (!file_exists($file)) {
            abort(404, 'Sample file not found.');
        }

        return response()->download($file);
    }
}
