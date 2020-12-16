<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\producto;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use App\Models\categoria;
use App\Models\User;


class UserController extends Controller
{
    use ApiResponser;
    public function GetUserData($nickname){
        $user = User::where("nickname", $nickname)->first();
        if ($user) {
           return $this->success($user, "User Found", 200);
        }
    }

    public function GetAllUsers(){
        if (Auth::user()->user_type === 'admin') {
            $users= User::all();
            if ($users && count($users)>=1){
                return $this->success($users, "Users found", 200);
            }else{
                return $this->success(null,"no users registered");
            }
            
          } else {
            return $this->error('Unathorized User', 401);
          }
    }
}
