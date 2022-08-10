<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Installment extends Model
{
    use HasFactory;


    protected $fillable = [
        'loan_id',
        'installment_amount',
        'due_date',
        'payment_date',
        'paid_amount',
        'installment_status',
    ];

    public function LoanInstallment()
    {
        return $this->belongsTo(Loan::class, 'loan_id', 'id');
    }
}
