<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Sale extends Model
{
    //

    /**
     * Get all of the saleDetail for the Sale
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function saleDetail(): HasMany
    {
        return $this->hasMany(SaleDetail::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(Pengguna::class);
    }

    public function pasien(): BelongsTo
    {
        return $this->belongsTo(Pasien::class);
    }
}
