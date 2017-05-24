<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProductRegistrationRequest;
use App\Models\Product;

class ProductController extends Controller
{
    /**
     * Return view for product registration
     */
    public function register()
    {
    	return view('product.register');
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

        $product = new Product;
        $product->name          = $name;
        $product->description   = $description;
        $product->rate_feet     = $ratePerFeet;
        $product->rate_ton      = $ratePerton;
        $product->status        = 1;
        if($product->save()) {
            return redirect()->back()->with("message","Product details saved successfully.")->with("alert-class","alert-success");
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
