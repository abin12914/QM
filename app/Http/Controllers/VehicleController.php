<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Return view for vehicle registration
     */
    public function register()
    {
    	return view('vehicle.register');
    }

     /**
     * Handle new vehicle registration
     */
    public function registerAction()
    {
    	
    }
}
