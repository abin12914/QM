<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\VehicleTypeRegistrationRequest;
use App\Models\VehicleType;
use App\Models\Product;
use App\Models\Royalty;
use Carbon\Carbon;

class VehicleTypeController extends Controller
{
    /**
     * Return view for vehicle type registration
     */
    public function register()
    {   
        $products = Product::where('status','1')->get();

    	return view('vehicle-type.register',[
                'products' => $products
            ]);
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
                    $royaltyArray[$productId] = [
                            'amount'            => $amount,
                            'status'            => 1,
                        ];
                }
                
                if($vehicleType->products()->sync($royaltyArray)) {
                    return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
                } else {
                    //delete vehicle type record associated with the royalty saving
                    $vehicleType->delete();

                    return redirect()->back()->withInput()->with("message","Failed to save the truck type and royalty details. Try again after reloading the page!<small class='pull-right'> #13/01</small>")->with("alert-class","alert-danger");
                }
            } else {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the truck type and royalty details. Try again after reloading the page!<small class='pull-right'> #13/02</small>")->with("alert-class","alert-danger");
        }	
    }

    /**
     * Return view for vehicle type listing
     */
    public function chart(Request $request)
    {
        $vehicleTypeId  = !empty($request->get('vehicle_type_id')) ? $request->get('vehicle_type_id') : 0;
        $productId      = !empty($request->get('product_id')) ? $request->get('product_id') : 0;

        $vehicleTypesCombobox   = VehicleType::where('status', '1')->get();
        $products               = Product::where('status', '1')->get();

        $query = VehicleType::where('status', '1');

        if(!empty($vehicleTypeId) && $vehicleTypeId != 0) {
            $query = $query->where('id', $vehicleTypeId);
        }

        if(!empty($productId) && $productId != 0) {
            $query = $query->whereHas('products', function ($qry) use($productId) {
                $qry->where('products.id', $productId);
            });
        }

        $vehicleTypes = $query->with('products')->orderBy('generic_quantity','desc')->paginate(5);

        return view('vehicle-type.chart',[
                'vehicleTypesCombobox'  => $vehicleTypesCombobox,
                'products'              => $products,
                'vehicleTypes'          => $vehicleTypes,
                'vehicleTypeId'         => $vehicleTypeId,
                'productId'              => $productId
            ]);
    }

    /**
     * Return view for vehicle type listing
     */
    public function list(Request $request)
    {
        $fromDate   = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate     = !empty($request->get('to_date')) ? $request->get('to_date') : '';

        $vehicleTypes = VehicleType::where('status', 1)->get();

        $query = Royalty::where('status', 1);

        if(empty($fromDate) && empty($toDate)) {
            $query = $query->whereDate('date_time', Carbon::today()->toDateString());
        } else if(!empty($fromDate) && empty($toDate)){
            $searchFromDate = Carbon::createFromFormat('d-m-Y', $fromDate);

            $query = $query->whereDate('date_time', $searchFromDate->toDateString());
        } else if(empty($fromDate) && !empty($toDate)) {
            $searchToDate = Carbon::createFromFormat('d-m-Y', $toDate);

            $query = $query->whereDate('date_time', $searchToDate->toDateString());
        } else {
            $searchFromDate = Carbon::createFromFormat('d-m-Y H:i:s', $fromDate." 00:00:00");
            $searchToDate = Carbon::createFromFormat('d-m-Y H:i:s', $toDate." 23:59:59");

            $query = $query->whereBetween('date_time', [$searchFromDate, $searchToDate]);
        }

        $royaltyRecords = $query->with(['transaction', 'vehicle.vehicleType'])->orderBy('date_time','desc')->paginate(10);

        return view('vehicle-type.list',[
                'royaltyRecords'   => $royaltyRecords,
                'fromDate'  => $fromDate,
                'toDate'    => $toDate,
            ]);
    }
}
