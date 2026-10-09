<?php

use App\Http\Controllers\API\PlayerController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\CountryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;



Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('sign_up_otp', [UserAuthController::class, 'sign_up']);
Route::post('/register', [UserAuthController::class, 'register']);
Route::post('/login', [UserAuthController::class, 'login']);
Route::post('/forget_password', [UserAuthController::class, 'forget_password']);
Route::post('/forget_reset_password', [UserAuthController::class, 'forget_reset_password']);


Route::post('/social_login', [UserAuthController::class, 'social_login']);
Route::get('/login_check', [UserAuthController::class, 'login_check']);




Route::middleware('auth:api', 'check.suspended')->group(function () {
    Route::get('/test', [UserAuthController::class, 'test']);

    Route::get('/getScoreBord', [UserAuthController::class, 'getScoreBord']);

    Route::get('/player_profile_data', [PlayerController::class, 'player_profile_data']);

    Route::post('/profile-update', [UserAuthController::class, 'profile_update']);

    Route::get('/setting', [UserAuthController::class, 'setting']);
    Route::post('/music_status_change', [UserAuthController::class, 'music_status_change']);
    Route::post('/sound_effect_status_change', [UserAuthController::class, 'sound_effect_status_change']);
    Route::post('/profile_delete', [UserAuthController::class, 'profile_delete']);
    Route::post('/user_logout', [ApiController::class, 'user_logout']);
    Route::get('/getallplayers', [ApiController::class, 'getallplayers']);
    Route::get('group_captain', [UserAuthController::class, 'group_captain']);
    Route::get('guess_player_list', [UserAuthController::class, 'guess_player_list']);

    Route::get('getPlayerList', [UserAuthController::class, 'getPlayerList']);
    Route::post('SaveScore', [PlayerController::class, 'SaveScore']);
    Route::post('WonWorldCup', [PlayerController::class, 'WonWorldCup']);


    Route::get('GetScroreBoard', [PlayerController::class, 'GetScroreBoard']);

    Route::get('guess_player_list_test', [UserAuthController::class, 'guess_player_list_test']);
    Route::get('guess_player_list_data', [UserAuthController::class, 'guess_player_list_data']);


    Route::get('getSticker', [PlayerController::class, 'getSticker']);
    Route::get('getUserSticker', [PlayerController::class, 'getUserSticker']);
    Route::get('get_randmally_sticker', [PlayerController::class, 'get_randmally_sticker']);

    Route::get('winrandomMatched', [PlayerController::class, 'winrandomMatched']);
});

Route::get('/termandcondition', [ApiController::class, 'termandcondition']);
Route::get('/privacypolicy', [ApiController::class, 'privacypolicy']);
// New API Routes 2026
Route::middleware('auth:api', 'check.suspended')->group(function () {
    Route::get('world-cups/{search?}', [CountryController::class, 'worldCups']);
    Route::post('select-world-cup', [CountryController::class, 'selectWorldCup']);
    Route::post('score-board', [CountryController::class, 'scoreBoard']);
    Route::post('save-score-board', [CountryController::class, 'saveScoreBoard']);
    Route::post('save-score', [CountryController::class, 'saveScore']);
    Route::get('statics-score', [CountryController::class, 'staticsScore']);
});
Route::post('get_sticker', [CountryController::class, 'getSticker']);
Route::post('player_list', [CountryController::class, 'playerList']);
Route::get('friendly_random_player', [CountryController::class, 'friendlyRandomPlayer']);
Route::post('friendly_player_list', [CountryController::class, 'playerList']);
// New API Routes 2026