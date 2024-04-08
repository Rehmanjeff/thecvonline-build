<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Mail\UserForgotPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Repositories\UserRepositoryInterface;

class AuthController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;    
    }
    
    public function login(Request $request)
    {
        try {
            $data = $this->userRepository->loginValidation($request->all())->validate();     
    
            $user = $this->userRepository->query()->where('email', $request->email)->get()->first();
            throw_if(!$user, 'Invalid email or password');  

            if($this->userRepository->loginAttempt($data)){
                $user = currentUser();
                $token =  $this->userRepository->generateToken($user)->plainTextToken;
                return response()->success('Login successful', compact('token'));
            } else {
                abort(404, 'Invalid email or password');
            }
        } catch (\Exception  $e) {
            return $this->handleException($e);
        }        
    }

    public function forgotPassword(Request $request)
    {
        try {
            $user = $this->userRepository->query()->where('email', $request->email)->get()->first();
    
            throw_if(!$user, 'account not found');

            $user->verification_token = Str::random(64);
            $user->save();
            Mail::to($user->email)->send(new UserForgotPassword($user));
            
            return response()->success('Password reset link sent to your email', compact('user'));
            
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function resetPassword(Request $request)
    {
        try {
            $this->userRepository->resetPasswordValidation($request->all())->validate();

            $user = $this->userRepository->query()->where('email', $request->email)->get()->first();
            
            throw_if(!$user || $user->verification_token != $request->token, 'account not found');

            $user->password = Hash::make($request->get('password'));
            $user->save();
            
            return response()->success('Password updated successfully');

        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            $request->user()->currentAccessToken()->delete();
    
            return response()->success('User Logout successfully');
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }
}
