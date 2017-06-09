<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRegistrationRequest;
use App\Models\Product;
use App\Models\VehicleType;
use App\Models\RoyaltyChart;

class ProductController extends Controller
{
    /**
     * Return view for product registration
     */
    public function register()
    {
        $vehicleTypes = VehicleType::where('status', '1')->get();

        if(count($vehicleTypes)) {
            return view('product.register', [
                    'vehicleTypes' => $vehicleTypes 
                ]);
        } else {
            return view('product.register');
        }
    }

     /**
     * Handle new product registration
     */
    public function registerAction(ProductRegistrationRequest $request)
    {
        $name           = $request->get('name');
        $description    = $request->get('description');
        $ratePerFeet    = $request->get('rate_feet');
        $ratePerton     = $request->get('rate_ton');
        $royalty            = $request->get('royalty');

        $product = new Product;
        $product->name          = $name;
        $product->description   = $description;
        $product->rate_feet     = $ratePerFeet;
        $product->rate_ton      = $ratePerton;
        $product->status        = 1;
        if($product->save()) {
            if(!empty($royalty)) {
                foreach ($royalty as $vehicleTypeId => $amount) {
                    $royaltyArray[] = [
                                        'vehicle_type_id'   => $vehicleTypeId,
                                        'product_id'        => $product->id,
                                        'amount'            => $amount,
                                        'status'            => 1,
                                        'created_at'        => date('Y-m-d H:i:s'),
                                        'updated_at'        => date('Y-m-d H:i:s')
                                    ];
                }
            
                if(RoyaltyChart::insert($royaltyArray)) {
                    return redirect()->back()->with("message","Product details saved successfully.")->with("alert-class","alert-success");
                } else {
                    return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the product details. Try after reloading the page.")->with("alert-class","alert-danger");   
                }
            } else {
                return redirect()->back()->with("message","Product details saved successfully.")->with("alert-class","alert-success");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the product details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for product listing
     */
    public function list()
    {
        $products = Product::paginate(10);
        if(!empty($products)) {
            return view('product.list',[
                    'products' => $products
                ]);
        } else {
            session()->flash('message', 'No product records available to show!');
            return view('product.list');
        }
    }
}
