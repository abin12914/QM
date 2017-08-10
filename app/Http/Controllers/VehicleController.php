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

    	return view('vehicle.register',[
                'vehicleTypes'  => $vehicleTypes
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
            return redirect()->back()->withInput()->with("message","Failed to save the truck details. Try again after reloading the page!<small class='pull-right'> #11/01</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for vehicle listing
     */
    public function list(Request $request)
    {
        $vehicleTypeId  = !empty($request->get('vehicle_type_id')) ? $request->get('vehicle_type_id') : 0;
        $bodyType       = !empty($request->get('body_type')) ? $request->get('body_type') : '0';
        $vehicleId      = !empty($request->get('vehicle_id')) ? $request->get('vehicle_id') : 0;

        $vehicleTypes       = VehicleType::where('status', '1')->get();
        $vehiclesCombobox   = Vehicle::where('status', '1')->get();

        $query = Vehicle::where('status', '1');

        if(!empty($vehicleTypeId) && $vehicleTypeId != 0) {
            $query = $query->where('vehicle_type_id', $vehicleTypeId);
        }

        if(!empty($bodyType) && $bodyType != '0') {
            $query = $query->where('body_type', $bodyType);
        }

        if(!empty($vehicleId) && $vehicleId != '0') {
            $query = $query->where('id', $vehicleId);
        }

        $vehicles = $query->with('vehicleType')->orderBy('created_at','desc')->paginate(10);
        
        return view('vehicle.list',[
                'vehicleTypes'      => $vehicleTypes,
                'vehiclesCombobox'  => $vehiclesCombobox,
                'vehicles'          => $vehicles,
                'vehicleTypeId'     => $vehicleTypeId,
                'bodyType'          => $bodyType,
                'vehicleId'         => $vehicleId
            ]);
    }
}
