<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_name',
        'salary',
        'bonus',
        'deduction',
        'net_salary',
        'payroll_date',
        'uuid',
    ];

    protected static function booted()
    {
        static::creating(function (Payroll $payroll) {
            if (! $payroll->uuid) {
                $payroll->uuid = (string) \Illuminate\Support\Str::uuid();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    protected $casts = [
        'payroll_date' => 'date:Y-m-d',
        'salary' => 'decimal:2',
        'bonus' => 'decimal:2',
        'deduction' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];
}
