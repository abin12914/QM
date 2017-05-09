<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabourController extends Controller
{
    /**
     * Return view for labour registration
     */
    public function register()
    {
    	return view('labour.register');
    }

     /**
     * Handle new labour registration
     */
    public function registerAction()
    {
    	
    }
}
