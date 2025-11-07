<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $table = 'payments';

    protected $primaryKey = 'paymentId';

    protected $fillable = [
        'userid',
        'orderid',
        'payment_method',
        'transaction_id',
        'ip',
        'amount',
        'currency',
        'status',
        'meta',
    ];

    protected $casts = [
        'meta' => 'array',
        'amount' => 'decimal:2',
    ];
}
