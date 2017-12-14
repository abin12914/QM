<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRegistrationRequest;
use App\Http\Requests\UserUpdationRequest;
use App\Http\Requests\OwnerRegistrationRequest;
use Hash;
use Auth;
use DateTime;
use App\Models\User;
use App\Models\Account;
use App\Models\AccountDetail;
use App\Models\Owner;
use App\Models\Transaction;

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

        if(!empty($validTill)) {
            //converting date and time to sql datetime format
            $validTill = date('Y-m-d H:i:s', strtotime($validTill.' '.'23:59:00'));
            $user->valid_till = $validTill;
        }
        if($user->save()) {
            return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the user details. Try again after reloading the page!<small class='pull-right'> #14/01</small>")->with("alert-class","alert-danger");
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
        $accountName        = $request->get('account_name');
        $financialStatus    = $request->get('financial_status');
        $openingBalance     = $request->get('opening_balance');
        $royaltyOwner       = !empty($request->get('royalty_owner')) ? $request->get('royalty_owner') : 0;

        $openingBalanceAccount = Account::where('account_name','Account Opening Balance')->first();
        if(!empty($openingBalanceAccount) && !empty($openingBalanceAccount->id)) {
            $openingBalanceAccountId = $openingBalanceAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the owner details. Try again after reloading the page!<small class='pull-right'> #14/02</small>")->with("alert-class","alert-danger");
        }

        if(!empty($royaltyOwner) && $royaltyOwner == 1){
            $royaltyAccountFlag = Account::where('relation', 'royalty owner')->first();
            if(!empty($royaltyAccountFlag) && !empty($royaltyAccountFlag->id)) {
                return redirect()->back()->withInput()->with("message","Failed to save the owner details.<br>Royalty ownership already assigned!<small class='pull-right'> #14/03</small>")->with("alert-class","alert-danger");
            }
        }

        if ($request->hasFile('image_file')) {
            $file               = $request->file('image_file');
            $extension          = $file->getClientOriginalExtension(); // getting image extension
            $fileName           = $userName.'_'.time().'.'.$extension; // renameing image
            $file->move(public_path().$destination, $fileName); // uploading file to given path
        }

        if(!empty($fileName)) {
            $image = $destination.$fileName;
        } else {
            $image = $destination."default_user.jpg";
        }

    	$user = new User;
        $user->name         = $name;
        $user->user_name    = $userName;
        $user->email        = $email;
        $user->phone        = $phone;
        $user->password     = Hash::make($password);
        $user->image        = $image;
        $user->role         = 'admin';
        $user->status       = 1;

        if(!empty($validTill)) {
            //converting date and time to sql datetime format
            $validTill = date('Y-m-d H:i:s', strtotime($validTill.' '.'23:59:00'));
            $user->valid_till = $validTill;
        }

        if($user->save()) {
            $account = new Account;
            $account->account_name      = $accountName;
            $account->description       = "Owner of the organization";
            $account->type              = "personal";
            $account->relation          = (empty($royaltyOwner) || $royaltyOwner == 0) ? "owner" : "royalty owner";
            $account->financial_status  = $financialStatus;
            $account->opening_balance   = $openingBalance;
            $account->status            = 1;
            if($account->save()) {
                $accountDetails = new AccountDetail;
                $accountDetails->account_id = $account->id;
                $accountDetails->name       = $name;
                $accountDetails->phone      = $phone;
                $accountDetails->email      = $email;
                $accountDetails->address    = $address;
                $user->image                = $image;
                $accountDetails->status     = 1;
                if($accountDetails->save()) {
                    if($financialStatus == 'debit') {//incoming [account holder gives cash to company] [Creditor]
                        $debitAccountId     = $openingBalanceAccountId;
                        $creditAccountId    = $account->id;
                        $particulars        = "Opening balance of ". $name . " - Debit [Creditor]";
                    } else if($financialStatus == 'credit'){//outgoing [company gives cash to account holder] [Debitor]
                        $debitAccountId     = $account->id;
                        $creditAccountId    = $openingBalanceAccountId;
                        $particulars        = "Opening balance of ". $name . " - Credit [Debitor]";
                    } else {
                        $debitAccountId     = $openingBalanceAccountId;
                        $creditAccountId    = $account->id;
                        $particulars        = "Opening balance of ". $name . " - None";
                    }

                    $dateTime = date('Y-m-d H:i:s', strtotime('now'));

                    $transaction = new Transaction;
                    $transaction->debit_account_id  = $debitAccountId;
                    $transaction->credit_account_id = $creditAccountId;
                    $transaction->amount            = !empty($openingBalance) ? $openingBalance : '0';
                    $transaction->date_time         = $dateTime;
                    $transaction->particulars       = $particulars;
                    $transaction->status            = 1;
                    $transaction->created_user_id   = Auth::user()->id;
                    if($transaction->save()) {
                        $owner = new Owner;
                        $owner->user_id     = $user->id;
                        $owner->account_id  = $account->id;
                        $owner->status      = 1;
                        if($owner->save()){
                            $flag = 1;
                        } else {
                            //delete the transaction, user, account, account detail if owner saving failed
                            $user->delete();
                            $account->delete();
                            $accountDetails->delete();
                            $transaction->delete();

                            $flag = 2;
                        }
                    } else {
                        //delete the user, account, account detail if opening balance transaction saving failed
                        $user->delete();
                        $account->delete();
                        $accountDetails->delete();

                        $flag = 3;
                    }
                } else {
                    //delete the user, account if account detail saving failed
                    $user->delete();
                    $account->delete();
                    
                    $flag = 4;
                }
            } else {
                //delete the user if account saving failed
                $user->delete();

                $flag = 5;
            }
        } else {
            $flag = 6;
        }

        if($flag == 1) {
            return redirect()->back()->with("message","Owner successfully saved as the Admin.")->with("alert-class","alert-success");
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the owner details. Try again after reloading the page!<small class='pull-right'> #14/04/". $flag ."</small>")->with("alert-class","alert-danger");
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

    /**
     * Return view for user listing
     */
    public function userList()
    {
        $users = User::paginate(15);

        if(empty($users)) {
            session()->flash('message', 'No users available to show!');
        }
        return view('user.list',[
                    'users' => $users
                ]);
    }

    /**
     * Return view for owner listing
     */
    public function ownerList()
    {
        $owners = Owner::with(['account.accountDetail', 'user'])->paginate(15);

        if(empty($owners)) {
            session()->flash('message', 'No owner record available to show!');
        }

        return view('owners.list',[
                    'owners' => $owners
                ]);
    }

    /**
     * Return view for registering new users
     */
    public function editProfile()
    {
        return view('user.edit-profile');
    }

    /**
     * Handle new user registration
     */
    public function updateProfile(UserUpdationRequest $request)
    {
        $name               = $request->get('name');
        $currentPassword    = $request->get('old_password');
        $password           = $request->get('password');

        if(Hash::check($currentPassword, Auth::User()->password)) {
            $user = Auth::User();
            $user->name         = $name;
            $user->password     = Hash::make($password);

            if($user->save()) {
                return redirect()->back()->with("message","Successfully updated.")->with("alert-class","alert-success");
            } else {
                return redirect()->back()->withInput()->with("message","Failed to update the user profile. Try again after reloading the page!<small class='pull-right'> #00/00</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Current password is invaild!<small class='pull-right'> #00/00</small>")->with("alert-class","alert-danger");
        }
    }
}
