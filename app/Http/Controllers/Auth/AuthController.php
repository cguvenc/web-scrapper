<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\auth\LoginRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
     public function index()
     {
        return view('auth.login');
     }
     public function login(LoginRequest $request)
     {
         if(Auth::attempt(['email'=> $request->email,'password'=>$request->password]))
         {
             return redirect()->route('dashboard');
         }
         return redirect()->back();
     }
     public function logout()
     {
         Auth::logout();
         return redirect('/');
     }

}
