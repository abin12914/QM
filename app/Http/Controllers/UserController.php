<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegistrationRequest;
use Hash;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Return view for registering new users
     */
    public function register()
    {
    	return view('user.register');
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

    	if ($request->hasFile('image_file')) {
	      	$destination 		= '/images/user/'; // upload path
	      	$file 				= $request->file('image_file');
			$extension 			= $file->getClientOriginalExtension(); // getting image extension
	      	$fileName 			= $userName.'_'.time().'.'.$extension; // renameing image
	      	$file->move(public_path().$destination, $fileName); // uploading file to given path
	    }

    	$user = new User;
        $user->name         = $name;
        $user->user_name    = $userName;
        $user->email        = $email;
        $user->phone        = $phone;
        $user->password     = Hash::make($password);
        if(!empty($fileName)) {
        	$user->image 		= $destination.$fileName;
        }
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
     * Return view for registering new owner
     */
    public function ownerRegister()
    {
    	return view('owners.register');
    }

     /**
     * Handle new owner registration
     */
    public function ownerRegisterAction(OwnerRegistrationRequest $request)
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
}
