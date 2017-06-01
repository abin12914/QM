<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VehicleType;
use App\Models\Account;
use App\Http\Requests\VehicleRegistrationRequest;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    /**
     * Return view for vehicle registration
     */
    public function register()
    {
        $vehicleTypes   = VehicleType::orderBy('name')->get();
        //$accounts       = Account::orderBy('account_name')->get();
    	return view('vehicle.register',[
                'vehicleTypes'  => $vehicleTypes,
                //'accounts'      => $accounts
            ]);
    }

     /**
     * Handle new vehicle registration
     */
    public function registerAction(VehicleRegistrationRequest $request)
    {
    	$registrationNumber = $request->get('vehicle_reg_number');
        $description        = $request->get('description');
        $ownerName          = $request->get('owner_name');
        $vehicleType        = $request->get('vehicle_type');
        $volume             = $request->get('volume');
        $bodyType           = $request->get('body_type');

        $vehicle = new Vehicle;
        $vehicle->reg_number        = $registrationNumber;
        $vehicle->description       = $description;
        $vehicle->owner_name        = $ownerName;
        $vehicle->vehicle_type_id   = $vehicleType;
        $vehicle->volume            = $volume;
        $vehicle->body_type         = $bodyType;
        $vehicle->status            = 1;
        if($vehicle->save()) {
            return redirect()->back()->with("message","Truck details saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the truck details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for vehicle listing
     */
    public function list()
    {
        $vehicles = Vehicle::paginate(10);
        if(!empty($vehicles)) {
            return view('vehicle.list',[
                    'vehicles' => $vehicles
                ]);
        } else {
            session()->flash('message', 'No truck records available to show!');
            return view('vehicle.list');
        }
    }
}
