<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $guarded = [];
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    public function histories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }
}
