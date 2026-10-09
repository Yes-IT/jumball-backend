<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Controllers\API\BaseController;
use App\Models\Player;
use Illuminate\Http\Request;
use App\Models\WebsidePage;

class ApiController extends BaseController
{
    public function termandcondition()

    {

       $termandcondition= WebsidePage::find(1);
       // dd('bajaakhcs');
       $data = array();
       $data['termandcondition'] = $termandcondition;
    return $this->sendResponse('termandcondition',$data,);
    }

    public function privacypolicy()

    {

       $privacypolicy= WebsidePage::find(2);
       // dd('bajaakhcs');
       $data = array();
       $data['privacypolicy'] = $privacypolicy;
    return $this->sendResponse('privacypolicy',$data,);
    }

    public function user_logout(Request $request)
    {
       //dd(Auth::id());
   
      $request->user()->token()->revoke();
       return $this->sendResponse('user_logout','',);
    }

    public function getallplayers()
    {

     
         $player = Player::with('Country')->select('id','name','type','designation','country_id')->get();
         $type= array();
         $type = $player->pluck('type')->unique();
         $designation= array();
         $designation = $player->pluck('designation')->unique();
         
         $userData = array();
         $userData['type']                 = $type;
         $userData['designation']          = $designation;
         $userData['player']               = $player;
  

 
         return $this->sendResponse('get all players.',$userData,);
    }

}
