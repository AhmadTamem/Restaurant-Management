<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity'

    ];
    public function order():BelongsTo
    {
        return $this->belongsTo(order::class);
    }

    public function menu():BelongsTo
    {
        return $this->belongsTo(menu::class);
    }
}
