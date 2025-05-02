<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    public function scopeAvailable(Builder $query): void
    {
        $query->where(fn($q) => $q->whereNull('date_out_stock')->orWhereNull('sale_id')->orWhereNull('selling_price'));
    }

    public function scopeSold(Builder $query): void
    {
        $query->where(fn($q) => $q->whereNotNull('date_out_stock')->whereNotNull('sale_id')->whereNotNull('selling_price'));
    }
    
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Goods::class);
    }
}
