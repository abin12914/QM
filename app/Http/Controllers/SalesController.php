<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Account;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Transaction;
use App\Models\UserIssuedCreditSale;
use App\Models\VehicleType;
use App\Models\RoyaltyChart;
use App\Models\Royalty;
use Auth;
use DateTime;
use App\Http\Requests\CreditSaleRegistrationRequest;
use App\Http\Requests\CashSaleRegistrationRequest;

class SalesController extends Controller
{
    /**
     * Return view for account registration
     */
    public function register()
    {
        $vehicles = Vehicle::get();
        $accounts = Account::where('type','personal')->get();
        $products = Product::get();
        $sales    = Sale::orderBy('date_time', 'desc')->take(5)->get();

        return view('sales.register',[
                'vehicles'      => $vehicles,
                'accounts'      => $accounts,
                'products'      => $products,
                'sales_records' => $sales,
            ]);
    }

    /**
     * Handle new credit sale registration
     */
    public function creditSaleRegisterAction(CreditSaleRegistrationRequest $request)
    {
        $vehicleId          = $request->get('vehicle_id');
        $purchaserAccountId = $request->get('purchaser_account_id');
        $date               = $request->get('date');
        $time               = $request->get('time');
        $productId          = $request->get('product_id');
        $measureType        = $request->get('measure_type');
        $quantity           = $request->get('quantity');
        $rate               = $request->get('rate');
        $billAmount         = $request->get('bill_amount');
        $discount           = $request->get('discount');
        $deductedTotal      = $request->get('deducted_total');

        $salesAccount = Account::where('account_name','Sales')->first();
        if($salesAccount) {
            $salesAccountId = $salesAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sales account not found.")->with("alert-class","alert-danger");
        }

        $royaltyAccount = Account::where('account_name','Sale Royalty')->first();
        if($royaltyAccount && !empty($royaltyAccount->id)) {
            $royaltyAccountId = $royaltyAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sale Royalty account not found.")->with("alert-class","alert-danger");
        }

        $royaltyOwnerAccount = Account::where('relation', 'royalty owner')->first();
        if($royaltyOwnerAccount && !empty($royaltyOwnerAccount->id)) {
            $royaltyOwnerAccountId = $royaltyOwnerAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sale Royalty owner not registered.")->with("alert-class","alert-danger");
        }

        if($measureType == 1) {
            if(($quantity * $rate) != $billAmount) {
                return redirect()->back()->withInput()->with("message","Something went wrong! Bill calculation error.")->with("alert-class","alert-danger");
            }
            if(($billAmount - $discount) != $deductedTotal) {
                return redirect()->back()->withInput()->with("message","Something went wrong! Discount deduction error.")->with("alert-class","alert-danger");
            }
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $purchaserAccountId;
        $transaction->credit_account_id = $salesAccountId; //sale account id
        $transaction->amount            = !empty($deductedTotal) ? $deductedTotal : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Credit sale";
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $sale = new Sale;
            $sale->transaction_id   = $transaction->id;
            $sale->vehicle_id       = $vehicleId;
            $sale->date_time        = $dateTime;
            $sale->product_id       = $productId;
            $sale->measure_type     = $measureType;
            if($measureType == 1) {
                $sale->quantity         = $quantity;
                $sale->rate             = $rate;
                $sale->discount         = $discount;
                $sale->total_amount     = $deductedTotal;
            } else {
                $sale->quantity         = 0;
                $sale->rate             = 0;
                $sale->discount         = 0;
                $sale->total_amount     = 0;
            }
            $sale->status           = 1;
            
            if($sale->save()) {
                $royaltyFlag = $this->saveRoyalty($sale->id, $vehicleId, $productId, $dateTime, $royaltyAccountId, $royaltyOwnerAccountId);
                if($royaltyFlag) {dd($royaltyFlag);
                    return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
                } else {dd($royaltyFlag);
                    return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the royalty details. Try after reloading the page.")->with("alert-class","alert-danger");
                }
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Handle new cash sale registration
     */
    public function cashSaleRegisterAction(CashSaleRegistrationRequest $request)
    {
        $vehicleId          = $request->get('vehicle_id_cash');
        $date               = $request->get('date_cash');
        $time               = $request->get('time_cash');
        $productId          = $request->get('product_id_cash');
        $measureType        = $request->get('measure_type_cash');
        $quantity           = $request->get('quantity_cash');
        $rate               = $request->get('rate_cash');
        $billAmount         = $request->get('bill_amount_cash');
        $discount           = $request->get('discount_cash');
        $deductedTotal      = $request->get('deducted_total_cash');
        $oldBalance         = $request->get('old_balance');
        $total              = $request->get('total');
        $payment            = $request->get('paid_amount');
        $balance            = $request->get('balance');

        $userId = Auth::user()->id;

        $cashAccount = Account::where('account_name','Cash')->first();
        if($cashAccount) {
            $cashAccountId = $cashAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Cash account not found.!")->with("alert-class","alert-danger");
        }

        $salesAccount = Account::where('account_name','Sales')->first();
        if($salesAccount) {
            $salesAccountId = $salesAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sales account not found.!")->with("alert-class","alert-danger");
        }
        if($measureType == 1) {
            if(($quantity * $rate) != $billAmount) {
                return redirect()->back()->withInput()->with("message","Something went wrong! Bill calculation error.")->with("alert-class","alert-danger");
            }
            if(($billAmount - $discount) != $deductedTotal) {
                return redirect()->back()->withInput()->with("message","Something went wrong! Discount deduction error.")->with("alert-class","alert-danger");
            }
            if(($deductedTotal + $oldBalance) != $total) {
                return redirect()->back()->withInput()->with("message","Something went wrong! Total bill calculation error.")->with("alert-class","alert-danger");
            }
            if(($total - $payment) != $balance) {
                return redirect()->back()->withInput()->with("message","Something went wrong! Balance calculation error.")->with("alert-class","alert-danger");
            }
        }

        $royaltyAccount = Account::where('account_name','Sale Royalty')->first();
        if($royaltyAccount && !empty($royaltyAccount->id)) {
            $royaltyAccountId = $royaltyAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sale Royalty account not found.")->with("alert-class","alert-danger");
        }

        $royaltyOwnerAccount = Account::where('relation', 'royalty owner')->first();
        if($royaltyOwnerAccount && !empty($royaltyOwnerAccount->id)) {
            $royaltyOwnerAccountId = $royaltyOwnerAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Sale Royalty owner not registered.")->with("alert-class","alert-danger");
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $cashAccountId;
        $transaction->credit_account_id = $salesAccountId; //sale account id
        $transaction->amount            = $deductedTotal;
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Cash sale";
        $transaction->status            = 1;
        $transaction->created_user_id   = $userId;
        if($transaction->save()) {
            $sale = new Sale;
            $sale->transaction_id   = $transaction->id;
            $sale->vehicle_id       = $vehicleId;
            $sale->date_time        = $dateTime;
            $sale->product_id       = $productId;
            $sale->measure_type     = $measureType;
            $sale->quantity         = $quantity;
            $sale->rate             = $rate;
            $sale->discount         = $discount;
            $sale->total_amount     = $deductedTotal;
            $sale->status           = 1;
            
            if($sale->save()) {

                $royaltyFlag = $this->saveRoyalty($sale->id, $vehicleId, $productId, $dateTime, $royaltyAccountId, $royaltyOwnerAccountId);
                if(!$royaltyFlag) {
                    return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the royalty details. Try after reloading the page.")->with("alert-class","alert-danger");
                }

                $userIssuedCreditSale = new UserIssuedCreditSale;
                $userIssuedCreditSale->vehicle_id         = $vehicleId;
                $userIssuedCreditSale->debit_amount       = $payment;
                $userIssuedCreditSale->credit_amount      = $deductedTotal;
                $userIssuedCreditSale->date_time          = $dateTime;
                $userIssuedCreditSale->transaction_id     = $transaction->id;
                $userIssuedCreditSale->created_user_id    = $userId;
                $userIssuedCreditSale->status             = 1;
                if($userIssuedCreditSale->save()) {
                    return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
                }
                else {
                    return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");    
                }
            } else {
                return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Something went wrong! Failed to save the sale details. Try after reloading the page.")->with("alert-class","alert-danger");
        }
    }

    /**
     * Handle royalty save for each sale [call from sale save action]
     */
    public function saveRoyalty($saleId, $vehicleId, $productId, $dateTime, $royaltyAccountId, $royaltyOwnerAccountId)
    {
        if(empty($saleId) || empty($royaltyAccountId)) {
            return false;
        }

        $vehicle = Vehicle::find($vehicleId);
        if(!empty($vehicle) && !empty($vehicle->id)) {
            $royaltyRecord = RoyaltyChart::where('vehicle_type_id', $vehicle->vehicle_type_id)->where('product_id', $productId)->first();
            if(!empty($royaltyRecord) && !empty($royaltyRecord->id)) {
                $royaltyAmount = $royaltyRecord->amount;
            } else {
                return false;
            }
        } else {
            return false;
        }

        $transaction = new Transaction;
        $transaction->debit_account_id  = $royaltyOwnerAccountId;
        $transaction->credit_account_id = $royaltyAccountId; //royalty account id
        $transaction->amount            = !empty($royaltyAmount) ? $royaltyAmount : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Royalty credited for sale ".$saleId;
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $royalty = new Royalty;
            $royalty->transaction_id    = $transaction->id;
            $royalty->sale_id           = $saleId;
            $royalty->vehicle_id        = $vehicleId;
            $royalty->date_time         = $dateTime;
            $royalty->amount            = $royaltyAmount;
            $royalty->status            = 1;
            if($royalty->save()) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Return view for account listing
     */
    public function list(Request $request)
    {
        $accountId      = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $fromDate       = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate         = !empty($request->get('to_date')) ? $request->get('to_date') : '';
        $vehicleId      = !empty($request->get('vehicle_id')) ? $request->get('vehicle_id') : 0;
        $productId      = !empty($request->get('product_id')) ? $request->get('product_id') : 0;
        $vehicleTypeId  = !empty($request->get('vehicle_type_id')) ? $request->get('vehicle_type_id') : 0;

        $accounts       = Account::where('type', 'personal')->where('status', '1')->get();
        $vehicles       = Vehicle::where('status', '1')->get();
        $vehicleTypes   = VehicleType::where('status', '1')->get();
        $products       = Product::where('status', '1')->get();
        

        if(!empty($accountId) && $accountId != 0) {
            $selectedAccount = Account::find($accountId);
            if(!empty($selectedAccount) && !empty($selectedAccount->id)) {
                $selectedAccountName = $selectedAccount->account_name;
            }
        } else {
            $selectedAccountName = '';
        }

        if(!empty($vehicleId) && $vehicleId != 0) {
            $selectedVehicle = Vehicle::find($vehicleId);
            if(!empty($selectedVehicle) && !empty($selectedVehicle->id)) {
                $selectedVehicleRegNumber = $selectedVehicle->reg_number;
            }
        } else {
            $selectedVehicleRegNumber = '';
        }

        if(!empty($productId) && $productId != 0) {
            $selectedProduct = Product::find($productId);
            if(!empty($selectedProduct) && !empty($selectedProduct->id)) {
                $selectedProductName = $selectedProduct->name;
            }
        } else {
            $selectedProductName = '';
        }

        if(!empty($vehicleTypeId) && $vehicleTypeId != 0) {
            $selectedVehicleType = VehicleType::find($vehicleTypeId);
            if(!empty($selectedVehicleType) && !empty($selectedVehicleType->id)) {
                $selectedVehicleTypeName = $selectedVehicleType->name;
            }
        } else {
            $selectedVehicleTypeName = '';
        }

        $query = Sale::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            //$query->load('transaction.debitAccount', 'transaction.creditAccount');
            $query = $query->whereHas('transaction', function ($q) use($accountId) {
                $q->whereHas('debitAccount', function ($qry) use($accountId) {
                    $qry->where('id', $accountId);
                });
            });
        }

        if(!empty($vehicleId) && $vehicleId != 0) {
            //$query->load('transaction.debitAccount', 'transaction.creditAccount');
            $query = $query->where('vehicle_id', $vehicleId);
        }

        if(!empty($productId) && $productId != 0) {
            //$query->load('transaction.debitAccount', 'transaction.creditAccount');
            $query = $query->where('product_id', $productId);
        }

        if(!empty($vehicleTypeId) && $vehicleTypeId != 0) {
            //$query->load('transaction.debitAccount', 'transaction.creditAccount');
            $query = $query->whereHas('vehicle', function ($q) use($vehicleTypeId) {
                $q->whereHas('vehicleType', function ($qry) use($vehicleTypeId) {
                    $qry->where('id', $vehicleTypeId);
                });
            });
        }

        if(!empty($fromDate)) {
            $searchFromDate = new DateTime($fromDate);
            $searchFromDate = $searchFromDate->format('Y-m-d');
            $query = $query->where('date_time', '>=', $searchFromDate);
        }

        if(!empty($toDate)) {
            $searchToDate = new DateTime($toDate." 23:59");
            $searchToDate = $searchToDate->format('Y-m-d H:i');
            $query = $query->where('date_time', '<=', $searchToDate);
        }

        $sales = $query->with(['transaction.debitAccount', 'vehicle.vehicleType'])->orderBy('date_time','desc')->paginate(10);
        
        return view('sales.list',[
                'accounts'              => $accounts,
                'vehicles'              => $vehicles,
                'products'              => $products,
                'vehicleTypes'          => $vehicleTypes,
                'sales'                 => $sales,
                'accountId'             => $accountId,
                'vehicleId'             => $vehicleId,
                'productId'             => $productId,
                'vehicleTypeId'         => $vehicleTypeId,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
            ]);
    }

    /**
     * /Return sales details for given vehicle id
     * /Return old balance for user issued credit on cash transactions
     */
    public function getLastSaleByVehicleId($vehicleId)
    {
        $oldBalance = $totalDebit = $totalCredit = 0;

        $sale = Sale::where('vehicle_id',$vehicleId)->orderBy('created_at', 'desc')->first();
        $userIssuedCreditSales = UserIssuedCreditSale::where('vehicle_id',$vehicleId)->get();

        if(!empty($userIssuedCreditSales)) {
            foreach ($userIssuedCreditSales as $userIssuedCreditSale) {
                $totalCredit    = $totalCredit + $userIssuedCreditSale->credit_amount;
                $totalDebit     = $totalDebit + $userIssuedCreditSale->debit_amount;
            }
            $oldBalance = $totalCredit - $totalDebit;
        }
        if(!empty($sale)) {
            $productId          = $sale->product_id;
            $purchaserAccountId = $sale->transaction->debit_account_id;
            $measureType        = $sale->measure_type;

            return([
                    'flag'                  => true,
                    'productId'             => $productId,
                    'purchaserAccountId'    => $purchaserAccountId,
                    'measureType'           => $measureType,
                    'oldBalance'            => $oldBalance
                ]);
        } else {
            return([
                    'flag'          => false,
                    'oldBalance'    => $oldBalance
                ]);
        }
    }
}
