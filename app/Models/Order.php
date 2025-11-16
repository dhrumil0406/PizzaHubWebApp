<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'orderid';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $table = 'orders';

    protected $fillable = ['orderid', 'userid', 'fullname', 'email', 'addressid', 'address', 'phoneno', 'totalfinalprice', 'discountedtotalprice', 'paymentid', 'paymentmethod', 'orderstatus', 'orderdate'];

    public $timestamps = false;
}
