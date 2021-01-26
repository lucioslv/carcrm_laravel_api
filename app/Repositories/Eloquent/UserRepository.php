<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\UserRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserRepository extends Repository implements UserRepositoryInterface
{
    // model property on class instances
    protected $model;

    // Constructor to bind model to repo
    public function __construct(User $user)
    {
        $this->model = $user;
    }

    // create a new record in the database
    public function create(array $data)
    {
        $user['name'] = $data['name'];
        $user['email'] = $data['email'];
        $user['password'] = Hash::make($data['password']);
        $user['next_expiration'] = Carbon::now()->addDays(7);
        $user['delete_account'] = Carbon::now()->addDays(15);

        return $this->model->create($user);
    }
}
