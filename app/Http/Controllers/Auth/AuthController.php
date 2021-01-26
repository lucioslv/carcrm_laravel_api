<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Repositories\Eloquent\UserRepository;

class AuthController extends Controller
{
    protected $repo;

    public function __construct(UserRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\AuthRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AuthRequest $request)
    {
        $user = $this->repo->create($request->all());

        if($user->id){
            return response()->json([
                'access_token' => $user->createToken('auth-api')->accessToken
            ], 200);
        }

        return response()->json(['error' => 'Erro ao cadastrar usuário.'], 400);
    }
}
