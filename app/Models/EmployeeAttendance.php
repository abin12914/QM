<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeAttendance extends Model
{
    public $timestamps = false;
    protected $table = 'employee_attendance';

    /**
     * Get the employee details related to the attendance record
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id');
    }
}
