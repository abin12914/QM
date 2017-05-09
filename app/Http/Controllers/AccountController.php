<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Return view for account registration
     */
    public function register()
    {
    	return view('account.register');
    }

     /**
     * Handle new account registration
     */
    public function registerAction()
    {
    	
    }
}
