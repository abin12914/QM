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
//use App\Models\RoyaltyChart;
use App\Models\Royalty;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Http\Requests\CreditSaleRegistrationRequest;
use App\Http\Requests\CashSaleRegistrationRequest;
use App\Http\Requests\WeighmentRegistrationRequest;
use App\Http\Requests\MultipleCreditSaleRegistrationRequest;

class SalesController extends Controller
{
    /**
     * Return view for sale registration
     */
    public function register()
    {
        $vehicles = Vehicle::with('vehicleType')->get();
        $accounts = Account::where('type','personal')->get();
        $products = Product::get();
        $sales    = Sale::with(['vehicle', 'transaction.debitAccount', 'product'])->orderBy('created_at', 'desc')->take(5)->get();

        return view('sales.register',[
                'vehicles'      => $vehicles,
                'accounts'      => $accounts,
                'products'      => $products,
                'sales_records' => $sales,
            ]);
    }

    /**
     * Return view for multiple sales registration
     */
    public function multipleSaleRegister()
    {
        $vehicles       = Vehicle::with('vehicleType')->get();
        $accounts       = Account::where('type','personal')->get();
        $cashAccount    = Account::find(1);
        $accounts->push($cashAccount); //attaching cash account to the accounts
        $products       = Product::get();
        $sales      = Sale::with(['vehicle', 'transaction.debitAccount', 'product'])->orderBy('created_at', 'desc')->take(5)->get();

        return view('sales.multiple-register',[
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
        $saveFlag       = 0;

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
        if(!empty($salesAccount) && !empty($salesAccount->id)) {
            $salesAccountId = $salesAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'> #02/01</small>")->with("alert-class","alert-danger");
        }

        $royaltyAccount = Account::where('account_name','Sale Royalty')->first();
        if(!empty($royaltyAccount) && !empty($royaltyAccount->id)) {
            $royaltyAccountId = $royaltyAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/02</small>")->with("alert-class","alert-danger");
        }

        $royaltyOwnerAccount = Account::where('relation', 'royalty owner')->first();
        if($royaltyOwnerAccount && !empty($royaltyOwnerAccount->id)) {
            $royaltyOwnerAccountId = $royaltyOwnerAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/03</small>")->with("alert-class","alert-danger");
        }

        $vehicle = Vehicle::where('id', $vehicleId)->first();
        if($vehicle && !empty($vehicle->id)) {
            $vehicleNumber  = $vehicle->reg_number;
            $vehicleType    = $vehicle->vehicleType->name;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/03/01</small>")->with("alert-class","alert-danger");
        }

        if($measureType == 1) {
            if(($quantity * $rate) != $billAmount) {
                return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/04</small>")->with("alert-class","alert-danger");
            }
            if(($billAmount - $discount) != $deductedTotal) {
                return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/05</small>")->with("alert-class","alert-danger");
            }
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $purchaserAccountId;
        $transaction->credit_account_id = $salesAccountId; //sale account id
        $transaction->amount            = !empty($deductedTotal) ? $deductedTotal : '0';
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = ("Credit sale :".$vehicleNumber." - ".$vehicleType." - [1 Load]".(($measureType == 2) ? ' (Weighment pending)' : ''));
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
                $royaltyFlag = $this->saveRoyalty($sale->id, $vehicleId, $productId, $dateTime, 1, $royaltyAccountId, $royaltyOwnerAccountId);
                if($royaltyFlag == 0) {
                    return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
                } else {
                    //delete the sale and transaction if associated sale royality saving failed.
                    $transaction->delete();
                    $sale->delete();

                    return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/06/".$royaltyFlag."</small>")->with("alert-class","alert-danger");
                }
            } else {
                //delete the transaction record if associated sale saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/07</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/08</small>")->with("alert-class","alert-danger");
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
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'>Cash account not found!.  #02/09</small>")->with("alert-class","alert-danger");
        }

        $salesAccount = Account::where('account_name','Sales')->first();
        if($salesAccount) {
            $salesAccountId = $salesAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'>Sales account not found!.  #02/10</small>")->with("alert-class","alert-danger");
        }

        $royaltyAccount = Account::where('account_name','Sale Royalty')->first();
        if($royaltyAccount && !empty($royaltyAccount->id)) {
            $royaltyAccountId = $royaltyAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'>Sale Royalty account not found!.  #02/11</small>")->with("alert-class","alert-danger");
        }

        $royaltyOwnerAccount = Account::where('relation', 'royalty owner')->first();
        if($royaltyOwnerAccount && !empty($royaltyOwnerAccount->id)) {
            $royaltyOwnerAccountId = $royaltyOwnerAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'>Sale royalty owner account not found!.  #02/12</small>")->with("alert-class","alert-danger");
        }

        $vehicle = Vehicle::where('id', $vehicleId)->first();
        if($vehicle && !empty($vehicle->id)) {
            $vehicleNumber  = $vehicle->reg_number;
            $vehicleType    = $vehicle->vehicleType->name;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/12/01</small>")->with("alert-class","alert-danger");
        }

        if($measureType == 1) {
            if(($quantity * $rate) != $billAmount) {
                return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'>Bill Amount calculation error!.  #02/13</small>")->with("alert-class","alert-danger");
            }
            if(($billAmount - $discount) != $deductedTotal) {
                return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'>Discount deduction error!.  #02/14</small>")->with("alert-class","alert-danger");
            }
            if(($deductedTotal + $oldBalance) != $total) {
                return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'>Total bill amount calculation error!.  #02/15</small>")->with("alert-class","alert-danger");
            }
            if(($total - $payment) != $balance) {
                return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'>Balance amount calculation error!.  #02/16</small>")->with("alert-class","alert-danger");
            }
        }


        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $cashAccountId;
        $transaction->credit_account_id = $salesAccountId; //sale account id
        $transaction->amount            = $deductedTotal;
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = "Cash sale :".$vehicleNumber." - ".$vehicleType." [1 Load]";
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

                $royaltyFlag = $this->saveRoyalty($sale->id, $vehicleId, $productId, $dateTime, 1, $royaltyAccountId, $royaltyOwnerAccountId);
                if($royaltyFlag != 0) {
                    //delete the sale and transaction if associated sale royality saving failed.
                    $transaction->delete();
                    $sale->delete();

                    return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'> #02/17/".$royaltyFlag."</small>")->with("alert-class","alert-danger");
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
                    //delete the sale and transaction and royality if associated user issued credit saving failed.
                    $royalityRecord = Royalty::where('sale_id', $sale->id)->first();
                    if(!empty($royaltyRecord) && !empty($royaltyRecord->id)) {
                        $royaltyRecord->delete();
                    }
                    $transaction->delete();
                    $sale->delete();

                    return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'> #02/18</small>")->with("alert-class","alert-danger");
                }
            } else {
                //delete the transaction if associated sale saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'> #02/19</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'> #02/20</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Handle royalty save for each sale [call from sale save action]
     */
    public function saveRoyalty($saleId, $vehicleId, $productId, $dateTime, $quantity, $royaltyAccountId, $royaltyOwnerAccountId)
    {
        if(empty($saleId) || empty($vehicleId) || empty($productId) || empty($dateTime) || empty($quantity) || empty($royaltyAccountId) || empty($royaltyOwnerAccountId)) {
            return 1;
        }

        $vehicle = Vehicle::find($vehicleId);
        if(!empty($vehicle) && !empty($vehicle->id)) {
            $vehicleNumber  = $vehicle->reg_number;
            $vehicleType    = $vehicle->vehicleType->name;
            //$royaltyRecord = RoyaltyChart::where('vehicle_type_id', $vehicle->vehicle_type_id)->where('product_id', $productId)->first();
            $vehicleTypeRecord = VehicleType::where('id', $vehicle->vehicle_type_id)->with('products')->first();
            if(!empty($vehicleTypeRecord) && !empty($vehicleTypeRecord->id)) {
                foreach($vehicleTypeRecord->products as $product) {
                    if($product->id == $productId) {
                        $royaltyAmount = ($product->pivot->amount * $quantity);
                    }
                }
            } else {
                return 2;
            }
        } else {
            return 3;
        }

        $royaltyTransaction = new Transaction;
        $royaltyTransaction->debit_account_id  = $royaltyAccountId; //royalty account id
        $royaltyTransaction->credit_account_id = $royaltyOwnerAccountId;
        $royaltyTransaction->amount            = !empty($royaltyAmount) ? $royaltyAmount : '0';
        $royaltyTransaction->date_time         = $dateTime;
        $royaltyTransaction->particulars       = "Royalty credited for ". $quantity ." Load. :".$vehicleNumber." - ".$vehicleType."[sale ". $saleId ."]";
        $royaltyTransaction->status            = 1;
        $royaltyTransaction->created_user_id   = Auth::user()->id;
        if($royaltyTransaction->save()) {
            $royalty = new Royalty;
            $royalty->transaction_id    = $royaltyTransaction->id;
            $royalty->sale_id           = $saleId;
            $royalty->vehicle_id        = $vehicleId;
            $royalty->date_time         = $dateTime;
            $royalty->amount            = $royaltyAmount;
            $royalty->status            = 1;
            if($royalty->save()) {
                return 0;
            } else {
                //delete the transaction if associated royalty saving failed.
                $royaltyTransaction->delete();

                return 4;
            }
        } else {
            return 5;
        }
    }

    /**
     * Return view for account listing
     */
    public function list(Request $request)
    {
        $totalAmount        = 0;
        $totalLoad          = 0;
        $totalSingleLoad    = 0;
        $totalMultipleLoad  = 0;
        $totalQuantity      = 0;
        $accountId      = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $fromDate       = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate         = !empty($request->get('to_date')) ? $request->get('to_date') : '';
        $vehicleId      = !empty($request->get('vehicle_id')) ? $request->get('vehicle_id') : 0;
        $productId      = !empty($request->get('product_id')) ? $request->get('product_id') : 0;
        $vehicleTypeId  = !empty($request->get('vehicle_type_id')) ? $request->get('vehicle_type_id') : 0;

        $accounts       = Account::where('type', 'personal')->orWhere('id', 1)->where('status', '1')->get();
        $vehicles       = Vehicle::where('status', '1')->get();
        $vehicleTypes   = VehicleType::where('status', '1')->get();
        $products       = Product::where('status', '1')->get();

        $query = Sale::where('status', 1);

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($qry) use($accountId) {
                $qry->where('debit_account_id', $accountId);
            });
        }

        if(!empty($vehicleId) && $vehicleId != 0) {
            $query = $query->where('vehicle_id', $vehicleId);
        }

        if(!empty($productId) && $productId != 0) {
            $query = $query->where('product_id', $productId);
        }

        if(!empty($vehicleTypeId) && $vehicleTypeId != 0) {
            $query = $query->whereHas('vehicle', function ($qry) use($vehicleTypeId) {
                $qry->where('vehicle_type_id', $vehicleTypeId);
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

        $totalQuery     = clone $query;
        $totalAmount    = $totalQuery->sum('total_amount');

        $totalMultipleLoadQuery = clone $query;
        $totalMultipleLoad      = $totalMultipleLoadQuery->where('measure_type', 3)->sum('quantity');

        $totalSingleLoadQuery = clone $query;
        $totalSingleLoad      = $totalSingleLoadQuery->whereIn('measure_type', [1,2])->count();

        $totalQuantityQuery = clone $query;
        $totalQuantitySale  = $totalQuantityQuery->with('vehicle')->get();

        foreach ($totalQuantitySale as $key => $sale) {
            if($sale->measure_type == 3) {
                $totalQuantity = $totalQuantity + ($sale->quantity * $sale->vehicle->volume);
            } else {
                $totalQuantity = $totalQuantity + $sale->vehicle->volume;
            }
        }

        $totalLoad = $totalMultipleLoad +$totalSingleLoad;

        $sales = $query->with(['transaction.debitAccount', 'vehicle.vehicleType', 'product'])->orderBy('id','desc')->paginate(15);
        
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
                'totalAmount'           => $totalAmount,
                'totalLoad'             => $totalLoad,
                'totalQuantity'         => $totalQuantity
            ]);
    }

    /**
     * Return view for weighment pending list
     */
    public function weighmentPending(Request $request)
    {
        $accountId      = !empty($request->get('account_id')) ? $request->get('account_id') : 0;
        $fromDate       = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate         = !empty($request->get('to_date')) ? $request->get('to_date') : '';
        $vehicleId      = !empty($request->get('vehicle_id')) ? $request->get('vehicle_id') : 0;

        $accounts       = Account::where('type', 'personal')->where('status', '1')->get();
        $vehicles       = Vehicle::where('status', '1')->get();

        $query = Sale::where('status', 1)->where('measure_type', 2)->where('quantity', 0);

        if(!empty($accountId) && $accountId != 0) {
            $query = $query->whereHas('transaction', function ($qry) use($accountId) {
                $qry->where('debit_account_id', $accountId);
            });
        }

        if(!empty($vehicleId) && $vehicleId != 0) {
            $query = $query->where('vehicle_id', $vehicleId);
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

        $sales = $query->with(['vehicle','transaction.debitAccount','product'])->orderBy('id','desc')->paginate(15);
        
        return view('sales.weighment-pending-list',[
                'accounts'              => $accounts,
                'vehicles'              => $vehicles,
                'sales'                 => $sales,
                'accountId'             => $accountId,
                'vehicleId'             => $vehicleId,
                'fromDate'              => $fromDate,
                'toDate'                => $toDate,
            ]);
    }

    /**
     * Return view for weighment registration
     */
    public function weighmentRegister(Request $request)
    {
        $saleId = !empty($request->get('sale_id')) ? $request->get('sale_id') : 0;

        if(!empty($saleId) && $saleId != 0) {
            $sale = Sale::where('id', $saleId)->where('measure_type', 2)->where('quantity', 0)->with(['vehicle', 'product', 'transaction.debitAccount'])->first();

            if(empty($sale) || empty($sale->id)) {
                return redirect(route('sales-weighment-pending-view'))->with("message","Something went wrong! Selected record not found. Try again after reloading the page!<small class='pull-right'> #02/21</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect(route('sales-weighment-pending-view'))->with("message","Something went wrong! Selected record not found. Try again after reloading the page!<small class='pull-right'> #02/22</small>")->with("alert-class","alert-danger");
        }

        return view('sales.weighment-register',[
                'sale' => $sale
            ]);
    }

    /**
     * Return view for weighment registration action
     */
    public function weighmentRegisterAction(WeighmentRegistrationRequest $request)
    {
        $saleId         = $request->get('sale_id');
        $quantity       = $request->get('quantity');
        $rate           = $request->get('rate');
        $billAmount     = $request->get('bill_amount');
        $discount       = $request->get('discount');
        $deductedTotal  = $request->get('deducted_total');

        if(!empty($saleId)){
            $sale = Sale::find($saleId);
            if(!empty($sale) && !empty($sale->id) && $sale->measure_type == 2 && $sale->quantity == 0) {
                if(($quantity * $rate) != $billAmount) {
                    return redirect()->back()->withInput()->with("message","Failed to save the weighment details. Try again after reloading the page!<small class='pull-right'>Bill Amount calculation error!. #02/23</small>")->with("alert-class","alert-danger");
                }
                if(($billAmount - $discount) != $deductedTotal) {
                    return redirect()->back()->withInput()->with("message","Failed to save the weighment details. Try again after reloading the page!<small class='pull-right'> #02/24</small>")->with("alert-class","alert-danger");
                }

                $vehicle = Vehicle::where('id', $sale->vehicle_id)->first();
                if($vehicle && !empty($vehicle->id)) {
                    $vehicleNumber  = $vehicle->reg_number;
                    $vehicleType    = $vehicle->vehicleType->name;
                } else {
                    return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/24/01</small>")->with("alert-class","alert-danger");
                }

                $transaction = Transaction::find($sale->transaction->id);
                $transaction->amount            = !empty($deductedTotal) ? $deductedTotal : '0';
                $transaction->particulars       = "Credit sale : ".$vehicleNumber." - ".$vehicleType." [1 Load] Weighment updated.";
                $transaction->created_user_id   = Auth::user()->id;
                if($transaction->save()) {
                    $sale->quantity         = $quantity;
                    $sale->rate             = $rate;
                    $sale->discount         = $discount;
                    $sale->total_amount     = $deductedTotal;
                    
                    if($sale->save()) {
                        return redirect(route('sales-weighment-pending-view'))->with("message","Successfully saved.")->with("alert-class","alert-success");
                    } else {
                        $transaction->amount        = 0;
                        $transaction->particulars   = "Credit sale (Weighment pending)";
                        $transaction->save();

                        return redirect(route('sales-weighment-pending-view'))->with("message","Failed to save the weighment details. Try again after reloading the page!<small class='pull-right'> #02/25</small>")->with("alert-class","alert-danger");
                    }
                } else {
                    return redirect(route('sales-weighment-pending-view'))->with("message","Failed to save the weighment details. Try again after reloading the page!<small class='pull-right'> #02/26</small>")->with("alert-class","alert-danger");
                }
            } else {
                return redirect()->back()->with("message","Failed to save the weighment details. Try again after reloading the page!<small class='pull-right'> #02/27</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->with("message","Failed to save the weighment details. Try again after reloading the page!<small class='pull-right'> #02/28</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Handle multiple credit sale registrations
     */
    public function multipleCreditSaleRegisterAction(MultipleCreditSaleRegistrationRequest $request)
    {
        $saveFlag       = 0;

        $vehicleId          = $request->get('vehicle_id');
        $purchaserAccountId = $request->get('purchaser_account_id');
        $date               = $request->get('date');
        $time               = $request->get('time');
        $productId          = $request->get('product_id');
        $quantity           = $request->get('quantity');
        $rate               = $request->get('rate');
        $billAmount         = $request->get('bill_amount');

        $salesAccount = Account::where('account_name','Sales')->first();
        if(!empty($salesAccount) && !empty($salesAccount->id)) {
            $salesAccountId = $salesAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details. Try again after reloading the page!<small class='pull-right'> #02/29</small>")->with("alert-class","alert-danger");
        }

        $royaltyAccount = Account::where('account_name','Sale Royalty')->first();
        if(!empty($royaltyAccount) && !empty($royaltyAccount->id)) {
            $royaltyAccountId = $royaltyAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/30</small>")->with("alert-class","alert-danger");
        }

        $royaltyOwnerAccount = Account::where('relation', 'royalty owner')->first();
        if($royaltyOwnerAccount && !empty($royaltyOwnerAccount->id)) {
            $royaltyOwnerAccountId = $royaltyOwnerAccount->id;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/31</small>")->with("alert-class","alert-danger");
        }

        $vehicle = Vehicle::where('id', $vehicleId)->first();
        if($vehicle && !empty($vehicle->id)) {
            $vehicleNumber  = $vehicle->reg_number;
            $vehicleType    = $vehicle->vehicleType->name;
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/31/01</small>")->with("alert-class","alert-danger");
        }

        if(($quantity * $rate) != $billAmount) {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/32</small>")->with("alert-class","alert-danger");
        }

        //converting date and time to sql datetime format
        $dateTime = date('Y-m-d H:i:s', strtotime($date.' '.$time.':00'));

        $transaction = new Transaction;
        $transaction->debit_account_id  = $purchaserAccountId;
        $transaction->credit_account_id = $salesAccountId; //sale account id
        $transaction->amount            = !empty($billAmount) ? $billAmount : 0;
        $transaction->date_time         = $dateTime;
        $transaction->particulars       = $purchaserAccountId != 1 ? ("Credit sale - ". $quantity ." Load - ".$vehicleNumber." - ".$vehicleType) : ("Cash sale - ". $quantity ." Load - ".$vehicleNumber." - ".$vehicleType);
        $transaction->status            = 1;
        $transaction->created_user_id   = Auth::user()->id;
        if($transaction->save()) {
            $sale = new Sale;
            $sale->transaction_id   = $transaction->id;
            $sale->vehicle_id       = $vehicleId;
            $sale->date_time        = $dateTime;
            $sale->product_id       = $productId;
            $sale->measure_type     = 3;
            $sale->quantity         = $quantity;
            $sale->rate             = $rate;
            $sale->discount         = 0;
            $sale->total_amount     = $billAmount;
            $sale->status           = 1;
            
            if($sale->save()) {
                $royaltyFlag = $this->saveRoyalty($sale->id, $vehicleId, $productId, $dateTime, $quantity, $royaltyAccountId, $royaltyOwnerAccountId);
                if($royaltyFlag == 0) {
                    return redirect()->back()->with("message","Successfully saved.")->with("alert-class","alert-success");
                } else {
                    //delete the sale and transaction if associated sale royality saving failed.
                    $transaction->delete();
                    $sale->delete();

                    return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/33/".$royaltyFlag."</small>")->with("alert-class","alert-danger");
                }
            } else {
                //delete the transaction record if associated sale saving failed.
                $transaction->delete();

                return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/34</small>")->with("alert-class","alert-danger");
            }
        } else {
            return redirect()->back()->withInput()->with("message","Failed to save the sale details.Try again after reloading the page!<small class='pull-right'> #02/35</small>")->with("alert-class","alert-danger");
        }
    }

    /**
     * Return view for sale statement
     */
    public function statement(Request $request)
    {
        $fromDate   = !empty($request->get('from_date')) ? $request->get('from_date') : '';
        $toDate     = !empty($request->get('to_date')) ? $request->get('to_date') : '';
        $productId  = !empty($request->get('product_id')) ? $request->get('product_id') : 0;

        $vehicleTypes   = VehicleType::where('status', 1)->orderBy('generic_quantity', 'desc')->get();
        $products       = Product::where('status', '1')->get();

        $query = Sale::where('status', 1);

        if(!empty($productId) && $productId != 0) {
            $query = $query->where('product_id', $productId);
        }

        if(empty($fromDate) && empty($toDate)) {
            $query = $query->whereDate('date_time', Carbon::today()->toDateString());
        } else if(!empty($fromDate) && empty($toDate)){
            $searchFromDate = Carbon::createFromFormat('d-m-Y', $fromDate);

            $query = $query->whereDate('date_time', $searchFromDate->toDateString());
        } else if(empty($fromDate) && !empty($toDate)) {
            $searchToDate = Carbon::createFromFormat('d-m-Y', $toDate);

            $query = $query->whereDate('date_time', $searchToDate->toDateString());
        } else {
            $searchFromDate = Carbon::createFromFormat('d-m-Y H:i:s', $fromDate." 00:00:00");
            $searchToDate = Carbon::createFromFormat('d-m-Y H:i:s', $toDate." 23:59:59");

            $query = $query->whereBetween('date_time', [$searchFromDate, $searchToDate]);
        }

        $salesCount         = [];
        $totalSaleCount     = 0;
        $singleSalecount    = 0;
        $multipleSalecount  = 0;

        foreach ($vehicleTypes as $vehicleType) {
            $singleSaleCountQuery   = clone $query; //cloning query
            $multipleSaleCountQuery = clone $query; //cloning query

            $vehicleTypeId = $vehicleType->id;
            $singleSalecount = $singleSaleCountQuery->whereHas('vehicle', function ($qry) use($vehicleTypeId) {
                                $qry->where('vehicle_type_id', $vehicleTypeId);
                            })->whereIn('measure_type', [1, 2])->count();

            $multipleSalecount = $multipleSaleCountQuery->whereHas('vehicle', function ($qry) use($vehicleTypeId) {
                                $qry->where('vehicle_type_id', $vehicleTypeId);
                            })->where('measure_type', 3)->sum('quantity');

            $totalSaleCount = $totalSaleCount + $singleSalecount + $multipleSalecount;

            $salesCount[] = [
                $vehicleType->id => ($singleSalecount + $multipleSalecount)
            ];
        }

        return view('sales.statement',[
                'vehicleTypes'      => $vehicleTypes,
                'products'          => $products,
                'salesCount'        => $salesCount,
                'totalSaleCount'    => $totalSaleCount,
                'fromDate'          => $fromDate,
                'toDate'            => $toDate,
                'productId'         => $productId
            ]);
    }

    /**
     * Return view for sale bill
     */
    public function saleBillPrint(Request $request)
    {
        $id = $request->id;
        $sale = Sale::with(['transaction.debitAccount.accountDetail'])->where('id', $id)->first();

        $date = new DateTime($sale->date_time);
        $date = $date->format('d-m-Y');
        $customer   = $sale->transaction->debitAccount->accountDetail->name;
        $address    = $sale->transaction->debitAccount->accountDetail->address;
        $saleId     = $sale->id;
        $saleType   = (($sale->transaction->debitAccount->id == 1) ? 'Cash' : 'Credit');

        return view('sales.bill',[
                'sale'      => $sale,
                'date'      => $date,
                'customer'  => $customer,
                'address'   => $address,
                'saleId'    => $saleId,
                'saleType'  => $saleType,
            ]);
    }

    /**
     * /Return sales details for given vehicle id
     * /Return old balance for user issued credit on cash transactions
     */
    public function getLastSaleByVehicleId($vehicleId)
    {
        $oldBalance = $totalDebit = $totalCredit = 0;

        $sale = Sale::where('vehicle_id',$vehicleId)->where('product_id', 1)->orderBy('created_at', 'desc')->first();
        $userIssuedCreditSales = UserIssuedCreditSale::where('vehicle_id',$vehicleId)->get();

        if(!empty($userIssuedCreditSales) && count($userIssuedCreditSales) > 0) {
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
            if($sale->measure_type == 1) {
                $rate   = $sale->total_amount;
            } elseif($sale->measure_type == 2) {
                $rate = '';
            } else {
                $rate   = $sale->rate;
            }

            return([
                    'flag'                  => true,
                    'productId'             => $productId,
                    'purchaserAccountId'    => $purchaserAccountId,
                    'measureType'           => $measureType,
                    'rate'                  => $rate,
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
