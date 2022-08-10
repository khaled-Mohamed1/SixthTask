<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'loan_amount',
        'currency',
        'date',
        'loan_status',
        'installment_start_date',
        'installment_amount',
    ];

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}
