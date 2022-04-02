<?php

namespace App\Services\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthService
{

  const PAGINATION_LIMIT = 10;

  public function __construct(
    User $user
  ) {
    $this->user = $user;
  }
  public  function createOrUpdate($request, $user_id=null)
  {
    return $this->user->updateOrCreate([
      'id' => $user_id
    ], [
      'name' => $request->name,
      'email' => $request->email,
      'password' => Hash::make($request->password)
    ]);
  }

  public function login($request) {

    $user = $this->user::where('email', $request->email)->first();
    if ($user) {
      if (Hash::check($request->password, $user->password)) {
       return ['status'=>true,'data'=>$user];
      } else {
        return ['status' => false, 'message' => 'Password mismatch'];
        }
    } else {
      return ['status' => false, 'message' => 'Password mismatch'];
    }

  }
}
