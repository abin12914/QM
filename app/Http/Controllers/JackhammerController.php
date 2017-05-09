<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class JackhammerController extends Controller
{
    /**
     * Return view for jackhammer registration
     */
    public function register()
    {
    	return view('jackhammer.register');
    }

     /**
     * Handle new jackhammer registration
     */
    public function registerAction()
    {
    	
    }
}
