<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;
use App\Models\Coupon;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function coupons()
    {
        return $this->belongsTo(Coupon::class);
    }
}
