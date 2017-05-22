<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleType;
use App\Http\Requests\VehicleRegistrationRequest;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Return view for vehicle registration
     */
    public function register()
    {
        $vehicleTypes = VehicleType::orderBy('name')->get();
    	return view('vehicle.register',[
                'vehicleTypes' => $vehicleTypes
            ]);
    }

     /**
     * Handle new vehicle registration
     */
    public function registerAction(VehicleRegistrationRequest $request)
    {
    	
    }
}
