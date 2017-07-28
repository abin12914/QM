<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Models\Sale;
use App\Models\Account;
use App\Models\Product;

class LoginController extends Controller
{
	/**
     * Return view for public home page
     */
    public function publicHome()
    {
        return view('public.home');
    }

    /**
     * Return view for login
     */
    public function login()
    {
    	return view('public.login');
    }

    public function lockscreen(){
        $user = Auth::user();
        Auth::logout();

        return view('user.lockscreen',[
                'user'  => $user
            ]);
    }

    /**
     * Handle an authentication attempt.
     */
    public function loginAction(Request $request)
    {
    	$userName = $request->get('user_name');
    	$password = $request->get('password');
    	 
         if(Auth::attempt(['user_name' => $userName, 'password' => $password, 'status' => '1'],false)) {
            // Authentication passed...
            $user   = Auth::user();
            $today  = strtotime('now');
            $userValidDate = strtotime($user->valid_till);
            // redirect to login if the user validation is completed
            if(!empty($user->valid_till) && $today > $userValidDate) {
                //logout user
                Auth::logout();
                return redirect(route('login-view'))->with("fixed-message",'Your account "'. $user->user_name . '" has been expired. Please click <a href="#">here</a> for more info.')->with("fixed-alert-class","alert-warning");
            }
            return redirect(route('user-dashboard'))->with("message","Welcome " . $user->name . ". You are successfully logged in to the Quarry Manager.")->with("alert-class","alert-success");
        } else {
        	// Authentication fails...
            return redirect(route('login-view'))->with("message","Login failed. Incorrect user name and password.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Redirect successfully logged users
     */
    public function dashboard()
    {
        $pendingWeighmentsCount = Sale::where('status', 1)->where('measure_type', 2)->where('quantity', 0)->count();
        $pendingWeighmentsCount = ($pendingWeighmentsCount < 10) ? ('0'.$pendingWeighmentsCount) : $pendingWeighmentsCount;
        
        $accountsCount = Account::where('status', 1)->where('type', 'personal')->count();
        $accountsCount = ($accountsCount < 10) ? ('0'.$accountsCount) : $accountsCount;

        $salesCount = Sale::where('status', 1)->count();
        $salesCount = ($salesCount < 10) ? ('0'.$salesCount) : $salesCount;

        $productsCount = Product::where('status', 1)->count();
        $productsCount = ($productsCount < 10) ? ('0'.$productsCount) : $productsCount;

        return view('user.dashboard', [
                'pendingWeighmentsCount'    => $pendingWeighmentsCount,
                'accountsCount'             => $accountsCount,
                'salesCount'                => $salesCount,
                'productsCount'             => $productsCount
            ]);
    }

    /**
     * Logsout users
     */
    public function logout()
    {
        Auth::logout();
        return redirect(route('login-view'));
    }

    /**
     * Return view for software licence
     */
    public function licence()
    {
        return view('public.license');
    }

    /**
     * Return view for uncompleted pages
     */
    public function underConstruction()
    {
        return view('public.under-construction');
    }
}