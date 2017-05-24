<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VehicleTypeRegistrationRequest;
use App\Models\VehicleType;

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
    public function registerAction(VehicleTypeRegistrationRequest $request)
    {
        $name               = $request->get('name');
        $description        = $request->get('description');
        $genericQuantity    = $request->get('generic_quantity');
        $scenerage          = $request->get('scenerage');

        $vehicleType = new VehicleType;
        $vehicleType->name              = $name;
        $vehicleType->description       = $description;
        $vehicleType->generic_quantity  = $genericQuantity;
        $vehicleType->scenerage         = $scenerage;
        $vehicleType->status            = 1;
        if($vehicleType->save()) {
            return redirect()->back()->with("message","Vehicle type details saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the vehicle type details. Try after reloading the page.")->with("alert-class","alert-danger");
        }	
    }

    /**
     * Return view for vehicle type listing
     */
    public function list()
    {
        $vehicletypes = VehicleType::paginate(10);
        if(!empty($vehicletypes)) {
            return view('vehicle-type.list',[
                    'vehicletypes' => $vehicletypes
                ]);
        } else {
            session()->flash('message', 'No vehicle type records available to show!');
            return view('vehicle-type.list');
        }
    }
}
