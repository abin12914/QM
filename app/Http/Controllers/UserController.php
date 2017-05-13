<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\OwnerRegistrationRequest;
use Hash;
use Auth;
use App\Models\User;
use App\Models\Account;
use App\Models\Owner;

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
    	$name 		= $request->get('name');
    	$userName 	= $request->get('user_name');
    	$email 		= $request->get('email');
    	$phone 		= $request->get('phone');
    	$password 	= $request->get('password');
    	$role 	    = $request->get('role');
    	$validTill 	= $request->get('valid_till');

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
        $destination        = '/images/user/'; // image file upload path
        $flag =0;

        $name               = $request->get('name');
        $email              = $request->get('email');
        $phone              = $request->get('phone');
        $address            = $request->get('address');
        $userName           = $request->get('user_name');
        $validTill          = $request->get('valid_till');
        $password           = $request->get('password');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');

        if ($request->hasFile('image_file')) {
            $file               = $request->file('image_file');
            $extension          = $file->getClientOriginalExtension(); // getting image extension
            $fileName           = $userName.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
        }

    	$user = new User;
        $user->name         = $name;
        $user->user_name    = $userName;
        $user->email        = $email;
        $user->phone        = $phone;
        $user->password     = Hash::make($password);
        if(!empty($fileName)) {
            $user->image    = $destination.$fileName;
        }
        $user->role         = 'admin';
        $user->status       = 1;
        $user->valid_till   = $validTill;

        if($user->save()) {
            $account = new Account;
            $account->name              = $name;
            $account->description       = "Owner of the organization";
            $account->type              = "owner";
            $account->financial_status  = $financialStatus;
            $account->opening_balance   = $openingBalance;
            $account->status            = 1;
            if($account->save()) {
                $owner = new Owner;
                $owner->name        = $name;
                $owner->email       = $email;
                $owner->phone       = $phone;
                $owner->address     = $address;
                $owner->user_id     = $user->id;
                $owner->account_id  = $account->id;
                if(!empty($fileName)) {
                    $owner->image   = $destination.$fileName;
                }
                $owner->status      =1;
                if($owner->save()){
                    $flag =0;
                } else {
                    $flag = 3;
                }
            } else {
                $flag = 2;
            }
        } else {
            $flag = 1;
        }

        if($flag == 0) {
            return redirect()->back()->with("message","Owner successfully saved as the Admin.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the owner data. Try after reloading the page. Error code : ".$flag)->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view user profile
     */
    public function profileView()
    {
        $user = Auth::user();
        return view('user.profile',[
            'user' => $user
        ]);
    }
}
