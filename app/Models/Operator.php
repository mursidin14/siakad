<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    protected $fillable = [
        'employee_number',
        'user_id',
        'faculty_id',
        'departement_id',
    ];
}
