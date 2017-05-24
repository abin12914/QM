<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

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

    /**
     * Handle an authentication attempt.
     */
    public function loginAction(Request $request)
    {
    	$userName = $request->get('user_name');
    	$password = $request->get('password');
    	 if (Auth::attempt(['user_name' => $userName, 'password' => $password, 'status' => '1'],false)) {
            // Authentication passed...
            $user   = Auth::user();
            $today  = strtotime('now');
            $userValidDate = strtotime($user->valid_till);
            // redirect to login if the user validation is completed
            if(!empty($user->valid_till) && $today > $userValidDate) {
                //logout user
                Auth::logout();
                return redirect()->back()->withInput()->with("fixed-message",'Your account "'. $user->user_name . '" has been expired. Please click <a href="#">here</a> for more info.')->with("fixed-alert-class","alert-warning");
            }
            return redirect(route('user-dashboard'))->with("message","Welcome " . $user->name . ". You are successfully logged in to the Quarry Manager.")->with("alert-class","alert-success");
        } else {
        	// Authentication fails...
            return redirect()->back()->withInput()->with("message","Login failed. Incorrect user name and password.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Redirect successfully logged users
     */
    public function dashboard()
    {
        return view('user.dashboard');
    }

    /**
     * Logsout users
     */
    public function logout()
    {
        Auth::logout();
        return redirect(route('login-view'))->with("message","Logout completed successfully.")->with("alert-class","alert-success");
    }

    /**
     * Return view for software licence
     */
    public function licence()
    {
        return view('public.license');
    }
}