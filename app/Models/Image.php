<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $fillable=[
        'image',
        'menu_id'

    ];
    public function menu():BelongsTo{
        return $this->belongsTo(Menu::class);
    }

}
