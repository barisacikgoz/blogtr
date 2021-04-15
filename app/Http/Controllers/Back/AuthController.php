<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(){
        return view('back.auth.login');
    }

    public function loginPost(Request $request){
        if (Auth::attempt(['email' => $request->post('email'), 'password' => $request->post('password')])){
            toastr()->success('Tekrardan Hoşgeldiniz '.Auth::user()->name);
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('admin.login')->withErrors('Email or password wrong!');
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('admin.login');
    }
}
