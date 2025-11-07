<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $table = 'addresses'; // Explicitly set table name

    protected $primaryKey = 'addressid'; // Primary key

    protected $fillable = [
        'userid',
        'addressType',
        'name',
        'apartmentNo',
        'buildingName',
        'streetArea',
        'city',
    ];

    public $timestamps = false;

    /**
     * Relation with User (users_admins table).
     */
    public function user()
    {
        return $this->belongsTo(UsersAdmin::class, 'userid', 'userid');
    }
}
