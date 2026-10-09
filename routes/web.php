<?php

use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('home');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::post('admin/login', [AdminController::class, 'admin_login'])->name('admin.login');

Route::prefix('admin')->name('admin.')->middleware('admin')->group(function(){
    Route::get('logout', [AdminController::class, 'admin_logout'])->name('logout');
    Route::get('users', [AdminController::class, 'users'])->name('users');
    Route::get('players', [AdminController::class, 'players'])->name('players');
    //player edit
  Route::get('player/{id}', [AdminController::class, 'editPlayer']);
Route::post('player/update/{id}', [AdminController::class, 'updatePlayer']);
    //player edit
    Route::get('statistics', [AdminController::class, 'statistics'])->name('statistics');
    Route::get('statistics_export_csv', [AdminController::class, 'statistics_export_csv'])->name('statistics_export_csv');
    Route::get('term_and_condition', [AdminController::class, 'term_and_condition'])->name('term_and_condition');
    Route::get('privacy-policy', [AdminController::class, 'privacy_policy'])->name('privacy-policy');
    Route::post('term-and-condition-save', [AdminController::class, 'term_and_condition_save'])->name('term_and_condition_save');
    Route::post('privacy-policy-save', [AdminController::class, 'privacy_policy_save'])->name('privacy_policy_save');
    Route::get('edit_user_profile/{id}', [AdminController::class, 'edit_user_profile'])->name('edit_user_profile');
    Route::post('delete_user_profile', [AdminController::class, 'delete_user_profile'])->name('delete_user_profile');
    Route::get('user_export_csv', [AdminController::class, 'user_export_csv'])->name('user_export_csv');
    Route::get('player_export_csv', [AdminController::class, 'player_export_csv'])->name('player_export_csv');
    Route::post('delete_player', [AdminController::class, 'delete_player'])->name('delete_player');
    Route::post('user_status_change', [AdminController::class, 'user_status_change'])->name('user_status_change');
    Route::get('delete_all_player', [AdminController::class, 'delete_all_player'])->name('delete_all_player');
    Route::get('sample_player_csv', [AdminController::class, 'sample_player_csv'])->name('sample_player_csv');
    Route::post('import_player_csv', [AdminController::class, 'import_player_csv'])->name('import_player_csv');
    Route::post('edit_user_profile_update', [AdminController::class, 'edit_user_profile_update'])->name('edit_user_profile_update');

    Route::get('/jersey_management', [AdminController::class, 'jersey_management'])->name('jersey_Management');
    Route::post('/add_jersey', [AdminController::class, 'add_jersey'])->name('add_jersey');

    Route::get('/sticker_management', [AdminController::class, 'sticker_management'])->name('sticker_management');
    //sticker edit
    Route::get('/sticker/{id}', [AdminController::class, 'editSticker']);
Route::post('/sticker/update/{id}', [AdminController::class, 'updateSticker']);
    //change year
    Route::get('/get-players/{year}', [AdminController::class, 'getPlayers'])->name('get_players');
    //change year
    Route::get('/world_cup_management', [AdminController::class, 'world_cup_management'])->name('world_cup_management');
    //worldcup edit
    Route::get('/worldcup/{id}',[AdminController::class,'getWorldCup']);

       Route::post('/worldcup/update/{id}',[AdminController::class,'updateWorldCup']);
    //worldcup edit
    Route::post('/delete_jurcy', [AdminController::class, 'delete_jurcy'])->name('delete_jurcy');
    //worldcup add
    Route::post('/worldcup/store', [AdminController::class, 'storeWorldCup'])
    ->name('worldcup.store');

    Route::post('/add_sticker', [AdminController::class, 'add_sticker'])->name('add_sticker');
    Route::post('/delete_sticker', [AdminController::class, 'delete_sticker'])->name('delete_sticker');

    //download csv template
    Route::get('sample_player_csv', [AdminController::class, 'sample_player_csv2'])
    ->name('sample_player_csv');

    //edit and update jearcy
    Route::get('/edit_jersey/{id}', [AdminController::class,'edit_jersey']);
Route::post('/update_jersey', [AdminController::class,'update_jersey']);
});




Route::get('/clear', function () {
    \Artisan::call('config:cache');
    \Artisan::call('cache:clear');
    \Artisan::call('route:clear');
    \Artisan::call('view:clear');
    \Artisan::call('config:clear');

    echo 'Caching Clear Compleated';
});
