<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegistrationRequest;
use Auth;
use Hash;
use App\Models\User;

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
            return redirect(route('user-dashboard'))->with("message","Welcome " . $user->name . ". You are successfully logged in to the Quary Manager.")->with("alert-class","alert-success");
        } else {
        	// Authentication fails...
            return redirect()->back()->withInput()->with("message","Login failed. Incorrect user name and password.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for registering new users
     */
    public function register()
    {
    	return view('public.register');
    }

     /**
     * Handle new user registration
     */
    public function registerAction(UserRegistrationRequest $request)
    {
    	$name 		= !empty($request->get('name')) ? $request->get('name') : '';
    	$userName 	= !empty($request->get('user_name')) ? $request->get('user_name') : '';
    	$email 		= !empty($request->get('email')) ? $request->get('email') : null;
    	$phone 		= !empty($request->get('phone')) ? $request->get('phone') : '';
    	$password 	= !empty($request->get('password')) ? $request->get('password') : '';
    	$role 	    = !empty($request->get('role')) ? $request->get('role') : '';
    	$validTill 	= !empty($request->get('valid_till')) ? $request->get('valid_till') : null;

    	$user = new User;
        $user->name         = $name;
        $user->user_name    = $userName;
        $user->email        = $email;
        $user->phone        = $phone;
        $user->password     = Hash::make($password);
        $user->role         = $role;
        $user->status       = 1;
        $user->valid_till   = $validTill;
        if($user->save()) {
            return redirect()->back()->with("message","User saved successfully.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the user data. Try reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view successfully logged users
     */
    public function dashboard()
    {
        return view('user.dashboard');
    }

    /**
     * Return view for registering new users
     */
    public function logout()
    {
        Auth::logout();
        return redirect(route('login-view'))->with("message","Logout completed successfully.")->with("alert-class","alert-success");
    }
}