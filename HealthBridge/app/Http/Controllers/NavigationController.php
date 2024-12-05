<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function home()
    {
        return view('landing-page');
    }

    public function login()
    {
        return view('auth.login');
    }

    public function signup()
    {
        return view('auth.register');
    }
}
