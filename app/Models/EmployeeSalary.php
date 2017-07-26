<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeSalary extends Model
{
    public $timestamps = false;
    protected $table = 'employee_salary';

    /**
     * Get the employee details related to the attendance record
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }

    /**
     * Get the transaction details associated with the salary
     */
    public function transaction()
    {
        return $this->belongsTo('App\Models\Transaction','transaction_id');
    }
}
