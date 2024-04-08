<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Repositories\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function loginValidation(array $data = [])
    {
        return Validator::make($data, [
            'email' => 'required|email',
            'password' => 'required',
        ]);
    }

    public function resetPasswordValidation(array $data = [])
    {
        return Validator::make($data, [
            'email' => 'required',
            'token' => 'required',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function loginAttempt(array $data = [])
    {
        return Auth::attempt($data);
    }

    public function generateToken($user, $device_name = "Default")
    {
        return $user->createToken($device_name);
    }

    public function query()
    {
        return $this->model->query();
    }
}