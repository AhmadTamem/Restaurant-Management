<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable=[
        'name',
        'category',
        'price',
        'description'
    ];
    public function image():HasMany{
        return $this->hasMany(Image::class);
    }
    public function OrderItem():HasMany{
        return $this->hasMany(OrderItem::class);

    }

}
