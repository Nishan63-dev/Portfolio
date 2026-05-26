<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Income extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_name',
        'fee_type',
        'amount',
        'payment_date',
        'receipt_number',
    ];

    public function getRouteKeyName(): string
    {
        return 'receipt_number';
    }

    protected $casts = [
        'payment_date' => 'date:Y-m-d',
        'amount' => 'decimal:2',
    ];
}
