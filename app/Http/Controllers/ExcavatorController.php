<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExcavatorController extends Controller
{
    /**
     * Return view for excavator registration
     */
    public function register()
    {
    	return view('excavator.register');
    }

     /**
     * Handle new excavator registration
     */
    public function registerAction()
    {
    	
    }
}
