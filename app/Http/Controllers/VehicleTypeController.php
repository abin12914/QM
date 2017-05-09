<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VehicleTypeController extends Controller
{
    /**
     * Return view for vehicle type registration
     */
    public function register()
    {
    	return view('vehicle-type.register');
    }

     /**
     * Handle new vehicle type registration
     */
    public function registerAction()
    {
    	
    }
}
