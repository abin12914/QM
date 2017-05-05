<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaffController extends Controller
{
    /**
     * Return view for staff registration
     */
    public function register()
    {
    	return view('staff.register');
    }

     /**
     * Handle new staff registration
     */
    public function registerAction()
    {
    	
    }
}
