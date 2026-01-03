<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    /** @use HasFactory<\Database\Factories\OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'product_id', 'customer_name', 'customer_phone', 'customer_address', 'quantity', 'total_price', 'status', 'whatsapp_sent', 'notes'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
