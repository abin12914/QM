<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VehicleTypeRegistrationRequest;
use App\Models\VehicleType;
use App\Models\Product;
use App\Models\RoyaltyChart;

class VehicleTypeController extends Controller
{
    /**
     * Return view for vehicle type registration
     */
    public function register()
    {   
        $products = Product::where('status','1')->get();

        if(count($products)) {
        	return view('vehicle-type.register',[
                    'products' => $products
                ]);
        } else {
            return view('vehicle-type.register');
        }
    }

     /**
     * Handle new vehicle type registration
     */
    public function registerAction(VehicleTypeRegistrationRequest $request)
    {
        $name               = $request->get('name');
        $description        = $request->get('description');
        $genericQuantity    = $request->get('generic_quantity');
        $royalty            = $request->get('royalty');

        $vehicleType = new VehicleType;
        $vehicleType->name              = $name;
        $vehicleType->description       = $description;
        $vehicleType->generic_quantity  = $genericQuantity;
        $vehicleType->status            = 1;
        if($vehicleType->save()) {
            if(!empty($royalty)) {
                foreach ($royalty as $productId => $amount) {
                    $royaltyArray[] = [
                                        'vehicle_type_id'   => $vehicleType->id,
                                        'product_id'        => $productId,
                                        'amount'            => $amount,
                                        'status'            => 1,
                                        'created_at'        => date('Y-m-d H:i:s'),
                                        'updated_at'        => date('Y-m-d H:i:s')
                                    ];
                }
            
                if(RoyaltyChart::insert($royaltyArray)) {
                    return redirect()->back()->with("message","Vehicle type details saved successfully.")->with("alert-class","alert-success");
                } else {
                    return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the vehicle type details. Try after reloading the page.")->with("alert-class","alert-danger");    
                }
            } else {
                return redirect()->back()->with("message","Vehicle type details saved successfully.")->with("alert-class","alert-success");
            }
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
