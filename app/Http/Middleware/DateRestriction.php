<?php

namespace App\Http\Middleware;

use Closure;
use Route;
use App\Models\ProfitLoss;

class DateRestriction
{
    protected $router;

    public function __construct()
    {
        $this->router = Route::getCurrentRoute()->getActionName();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $lastRecord     = ProfitLoss::where('status', 1)->orderBy('to_date', 'desc')->first();

        if(!empty($lastRecord) && !empty($lastRecord->id))
        {
            $restrictedDate = $lastRecord->to_date;

            switch ($this->router) {
                case 'App\Http\Controllers\DailyStatementController@employeeAttendanceAction':
                    $this->date = !empty($request->get('attendance_date')) ? $request->get('attendance_date') : '';
                    break;

                case 'App\Http\Controllers\DailyStatementController@excavatorReadingsAction':
                    $this->date = !empty($request->get('excavator_date')) ? $request->get('excavator_date') : '';
                    break;

                case 'App\Http\Controllers\DailyStatementController@jackhammerReadingsAction':
                    $this->date = !empty($request->get('jackhammer_date')) ? $request->get('jackhammer_date') : '';
                    break;

                case 'App\Http\Controllers\MonthlyStatementController@employeeSalaryAction':
                    $this->date = !empty($request->get('emp_salary_start_date')) ? $request->get('emp_salary_start_date') : '';
                    break;

                case 'App\Http\Controllers\MonthlyStatementController@excavatorRentAction':
                    $this->date = !empty($request->get('excavator_from_date')) ? $request->get('excavator_from_date') : '';
                    break;

                case 'App\Http\Controllers\SalesController@cashSaleRegisterAction':
                    $this->date = !empty($request->get('date_cash')) ? $request->get('date_cash') : '';
                    break;

                case 'App\Http\Controllers\VoucherController@cashVoucherRegistrationAction':
                    $this->date = !empty($request->get('cash_voucher_date')) ? $request->get('cash_voucher_date') : '';
                    break;

                case 'App\Http\Controllers\VoucherController@creditVoucherRegistrationAction':
                    $this->date = !empty($request->get('credit_voucher_date')) ? $request->get('credit_voucher_date') : '';
                    break;
                
                default:
                    $this->date = !empty($request->get('date')) ? $request->get('date') : '';
                    break;
            }

            if(!empty($this->date)) {
                //date in submitted form
                $entryDate = \Carbon\Carbon::createFromFormat('d-m-Y', $this->date)->format('Y-m-d');

                if($restrictedDate >= $entryDate) {
                    return redirect()->back()->with("message","Request denied!. Transactions up to ". $restrictedDate ." has been closed permanantly.")->with("alert-class","alert-danger");
                }
            }
        }
        return $next($request);
    }
}
