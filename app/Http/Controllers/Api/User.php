<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUser;
use Illuminate\Http\Request;

class User extends Controller
{

    public function createUser(CreateUser $request){
        return $request->json();
    }
    //
}
