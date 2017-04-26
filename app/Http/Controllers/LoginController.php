<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login()
    {
    	return view('public.login');
    }

    public function loginAction()
    {
    	echo "Login action";
    }
}
