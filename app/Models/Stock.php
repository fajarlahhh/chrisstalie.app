<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stok extends Model
{
    use HasFactory;

    public function scopeAvailable(Builder $query): void
    {
        $query->where(fn($q) => $q->whereNull('date_out_stok')->orWhereNull('sale_id')->orWhereNull('selling_harga'));
    }

    public function scopeSold(Builder $query): void
    {
        $query->where(fn($q) => $q->whereNotNull('date_out_stok')->whereNotNull('sale_id')->whereNotNull('selling_harga'));
    }
    
    public function goods(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }
}
