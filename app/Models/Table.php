<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Table extends Model
{
    protected $guarded = [];
    protected $casts = [
        'is_unlock_requested' => 'boolean',
    ];
    public function orders(): HasMany
    {
    return $this->hasMany(Order::class);
    }
}
