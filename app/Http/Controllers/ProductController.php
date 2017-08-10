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

        return view('product.register', [
                'vehicleTypes' => $vehicleTypes 
            ]);
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
        $royalty        = $request->get('royalty');

        $vehicleTypeCount = VehicleType::where('status', 1)->count();

        $product = new Product;
        $product->name          = $name;
        $product->description   = $description;
        $product->rate_feet     = $ratePerFeet;
        $product->rate_ton      = $ratePerton;
        $product->status        = 1;
        if($product->save()) {
            if(!empty($royalty)) {
                foreach ($royalty as $vehicleTypeId => $amount) {
                    $royaltyArray[$vehicleTypeId] = [
                            'amount'            => $amount,
                            'status'            => 1,
                        ];
                }
            
                if($product->vehicleTypes()->sync($royaltyArray)) {
                    return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
                } else {
                    //delete product record associated with the royalty saving
                    $product->delete();

                    return redirect()->back()->withInput()->with("message","Failed to save the product details. Try again after reloading the page!<small class='pull-right'> #12/01</small>")->with("alert-class","alert-danger");
                }
            } else {
                return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the product details. Try again after reloading the page!<small class='pull-right'> #12/02</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for product listing
     */
    public function list()
    {
        $products = Product::paginate(10);
        if(empty($products) || count($products) == 0) {
            session()->flash('message', 'No product records available to show!');
        }
        
        return view('product.list',[
            'products' => $products
        ]);
    }
}
