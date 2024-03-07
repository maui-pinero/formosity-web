<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'is_default_address',
        'tag',
        'first_name',
        'last_name',
        'mobile_no',
        'street_address',
        'barangay',
        'city',
        'province',
        'zip_code',
        'note',
    ];

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFullAddressAttribute()
    {
        return "$this->street_address, $this->barangay, $this->city, $this->province, $this->zip_code";
    }

}
