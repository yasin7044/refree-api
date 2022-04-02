<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\CreateUser;
use App\Http\Requests\User\LoginRequest;
use App\Services\Auth\AuthService;
use Illuminate\Http\Response;

class AuthController extends BaseController
{
    public function __construct(
        AuthService $authService
    ) {
        $this->authService = $authService;
    }
    public function createNewUser(CreateUser $request)
    {
        $user = $this->authService->createOrUpdate($request);
        
        $accessToken = $user->createToken('auth-token')->accessToken;
        $data = [
            'user'=>$user,
            'access_token'=> $accessToken
        ];
        return $this->respond(trans('auth.registration_successful'), Response::HTTP_CREATED, $data);
    }
    public function login(LoginRequest $request){

        $data = $this->authService->login($request);
        if($data['status']) {
            $token = $data['data']->createToken('Laravel Password Grant Client')->accessToken;
            $data = [
                'user' => $data['data'],
                'access_token' => $token
            ];
            return $this->respond(trans('auth.login_successful'), Response::HTTP_OK, $data);
        }
        return  $this->respond(trans($data['message']), Response::HTTP_OK,$data);
    }
    //
}
