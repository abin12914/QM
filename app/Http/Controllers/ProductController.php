<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    public function registerAction()
    {
    	
    }
}
