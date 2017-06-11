<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DailyStatementController extends Controller
{
    /**
     * Return view for daily statement registration
     */
    public function register()
    {
        return view('daily-statement.register');
    }

    /**
     * 
     */
    public function labourRegisterAction()
    {
        dd('x');
    }
}
